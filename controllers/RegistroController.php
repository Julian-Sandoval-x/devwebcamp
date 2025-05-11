<?php

namespace Controllers;

use Model\Dia;
use MVC\Router;
use Model\Horas;
use Model\Evento;
use Model\Paquete;
use Model\Usuario;
use Model\Ponentes;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;
use Model\Regalo;

class RegistroController
{
    public static function crear(Router $router)
    {

        if (!is_auth()) {
            header("Location: /login");
            return;
        }

        // Verificar si el usuario ya tiene un registro (Plan)
        $registro = Registro::where('usuario_id', $_SESSION['id']);

        if (isset($registro) && ($registro->paquete_id === '3' || $registro->paquete_id === '2')) {
            header("Location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        // Verificar si el usuario ya tiene un pago realizado
        if (isset($registro) && $registro->paquete_id === '1') {
            header("Location: /finalizar-registro/conferencias");
            return;
        }



        $router->render('registro/crear', [
            'titulo' => 'Finalizar Registro'
        ]);
    }

    public static function gratis(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si el usuario no esta autenticado, redirigirlo al login

            if (!is_auth()) {
                header("Location: /login");
                return;
            }

            // Verificar si el usuario ya tiene un registro (Plan)
            $registro = Registro::where('usuario_id', $_SESSION['id']);

            if (isset($registro) && $registro->paquete_id === '3') {
                header("Location: /boleto?id=" . urlencode($registro->token));
                return;
            }


            // Generamos un token unico
            $token = substr(md5(uniqid(rand(), true)), 0, 8);

            // Crear un registro en la base de datos
            $datos = [
                'paquete_id' => 3,
                'pago_id' => '',
                'token' => $token,
                'usuario_id' => $_SESSION['id']
            ];

            // Asignamos los valores a nuestro Modelo
            $registro = new Registro($datos);

            // Guardamos el registro en la base de datos
            $resultado = $registro->guardar();

            // En caso de exito redirigimos al usuario a la pagina de boleto
            if ($resultado) {
                header("Location: /boleto?id=" . urlencode($registro->token));
                return;
            }
        }
    }

    public static function boleto(Router $router)
    {
        // Validar la URL
        $id = $_GET['id'];

        if (!$id || strlen($id) !== 8) {
            header("Location: /");
            return;
        }

        // Buscar el token en la BD
        $registro = Registro::where('token', $id);

        if (!$registro) {
            header("Location: /");
            return;
        }

        // Llenar las tablas de referencia
        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);


        $router->render('registro/boleto', [
            'titulo' => 'Asistencia a DevWebCamp',
            'registro' => $registro
        ]);
    }

    public static function pagar(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si el usuario no esta autenticado, redirigirlo al login

            if (!is_auth()) {
                header("Location: /login");
                return;
            }

            // Validar que POST no este vacio
            if (empty($_POST)) {
                echo json_encode([]);
                return;
            }

            // Crear el registro en la base de datos
            $datos = $_POST;

            // Generamos un token unico
            $datos['token'] = substr(md5(uniqid(rand(), true)), 0, 8);

            // Asignamos el id del usuario
            $datos['usuario_id'] = $_SESSION['id'];

            try {
                // Asignamos los valores a nuestro Modelo
                $registro = new Registro($datos);

                // Guardamos el registro en la base de datos
                $resultado = $registro->guardar();

                echo json_encode([
                    'resultado' => $resultado
                ]);
            } catch (\Throwable $th) {
                echo json_encode([
                    'resultado' => 'error',
                    'mensaje' => $th->getMessage()
                ]);
            }
        }
    }

    public static function conferencias(Router $router)
    {

        // Verificamos que este autenticado
        if (!is_auth()) {
            header("Location: /login");
            return;
        }

        // Validar que el usuario cuente el plan presencial
        $usuario_id = $_SESSION['id'];
        $registro = Registro::where('usuario_id', $usuario_id);

        if (isset($registro) && $registro->paquete_id === '2') {
            header("Location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        if ($registro->paquete_id !== '1') {
            header("Location: /");
            return;
        }

        // Redireccionar a boleto virtual en caso de haber finalizado su registro
        if (isset($registro->regalo_id) && $registro->regalo_id === '1') {
            header("Location: /boleto?id=" . urlencode($registro->token));
            return;
        }

        $eventos = Evento::ordenar('hora_id', 'ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Horas::find($evento->hora_id);
            $evento->ponente = Ponentes::find($evento->ponente_id);

            if ($evento->dia_id === "1" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_v'][] = $evento;
            }

            if ($evento->dia_id === "2" && $evento->categoria_id === "1") {
                $eventos_formateados['conferencias_s'][] = $evento;
            }
            if ($evento->dia_id === "1" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_v'][] = $evento;
            }

            if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
                $eventos_formateados['workshops_s'][] = $evento;
            }
        }

        $regalos = Regalo::all('ASC');

        // Manejando el registor mediante POST
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            // Revisar que el usuario esta autenticado
            if (!is_auth()) {
                header("Location: /login");
                return;
            }

            // Creamos el arreglo con los eventos
            $eventos = explode(',', $_POST['eventos']);

            if (empty($eventos)) {
                echo json_encode(['resultado' => false]);
                return;
            }

            // Obtener el registro de usuario
            $registro = Registro::where('usuario_id', $_SESSION['id']);
            if (!isset($registro) || $registro->paquete_id !== '1') {
                echo json_encode(['resultado' => false]);
                return;
            }

            // Arreglo para los eventos
            $eventos_array = [];

            // Validar la disponibilidad de los eventos seleccionados
            foreach ($eventos as $evento_id) {
                // Consultamos la base de datos para obtener el evento
                $evento = Evento::find($evento_id);

                // Comprobar que el evento exista y tenga cupo
                if (!isset($evento) || $evento->disponibles === "0") {
                    echo json_encode(['resultado' => false]);
                    return;
                }
                // Agregamos el evento al arreglo
                $eventos_array[] = $evento;
            }

            foreach ($eventos_array as $evento) {
                $evento->disponibles -= 1;
                $evento->guardar();

                // Almacenar el registro en la base de datos
                $datos = [
                    'evento_id' => (int) $evento->id,
                    'registro_id' => (int) $registro->id
                ];

                $registro_usuario = new EventosRegistros($datos);
                $registro_usuario->guardar();
            }

            // Almacenar el regalo
            $registro->sincronizar(['regalo_id' => $_POST['regalo']]);
            $resultado = $registro->guardar();

            if ($resultado) {
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]);
            } else {
                echo json_encode(['resultado' => false]);
            }

            return;
        }

        $router->render('registro/conferencias', [
            'titulo' => 'Elige Wokrshops y Conferencias',
            'eventos' => $eventos_formateados,
            'regalos' => $regalos
        ]);
    }
}

<?php

namespace Controllers;

use Classes\Paginacion;
use MVC\Router;
use Model\Ponentes;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController
{
    public static function index(Router $router)
    {
        if (!is_admin()) {
            header("Location: /login");
        }

        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if (!$pagina_actual || $pagina_actual < 1) {
            header("Location: /admin/ponentes?page=1");
        }


        $registros_por_pagina = 10;
        $total_registros = Ponentes::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header("Location: /admin/ponentes?page=1");
        }

        $ponentes = Ponentes::paginar($registros_por_pagina, $paginacion->offset());

        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencistas',
            'ponentes' => $ponentes,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router)
    {

        if (!is_admin()) {
            header("Location: /login");
        }

        $alertas = [];

        $ponente = new Ponentes;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header("Location: /login");
            }

            // Leemos la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {

                $carpeta_imagenes = "../public/img/speakers";

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Generar versiones PNG y WEBP
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                // Genera un nombre aleatorio a la imagen
                $nombre_imagen = md5(uniqid(rand(), true));

                // Agregamos el nombre de la imagen a POST
                $_POST['imagen'] = $nombre_imagen;
            }

            // Convertimos el arreglo de redes a un string y evitamos que escape las diagonales
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            // Sincronizamos los datos de POST con nuestro objeto
            $ponente->sincronizar($_POST);

            // Validamos que todos los campos esten llenos
            $alertas = $ponente->validar();

            // Guardamos el registro
            // Si las alertas estan vacias (No hay errores)
            if (empty($alertas)) {
                $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                // Insertar el registro en la base de datos
                $resultado = $ponente->guardar();

                if ($resultado) {
                    header("Location: /admin/ponentes");
                }
            }
        }

        $router->render('admin/ponentes/crear', [
            'titulo' => 'Registrar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function editar(Router $router)
    {

        if (!is_admin()) {
            header("Location: /login");
        }

        $alertas = [];

        // Validar el id
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /admin/ponentes");
        }

        // Obtener el ponente a editar
        $ponente = Ponentes::find($id);

        if (!$ponente) {
            header("Location: /admin/ponentes");
        }

        // Creamos una variable tmp para almacenar la imagen actual
        $ponente->imagen_actual = $ponente->imagen;

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            if (!is_admin()) {
                header("Location: /login");
            }
            if (!empty($_FILES['imagen']['tmp_name'])) {

                $carpeta_imagenes = "../public/img/speakers";

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }

                // Generar versiones PNG y WEBP
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                // Genera un nombre aleatorio a la imagen
                $nombre_imagen = md5(uniqid(rand(), true));

                // Agregamos el nombre de la imagen a POST
                $_POST['imagen'] = $nombre_imagen;
            } else {
                $_POST['imagen'] = $ponente->imagen_actual;
            }

            // Convertimos el arreglo de redes a un string y evitamos que escape las diagonales
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            // Sincronizamos los datos del POST con nuestro objeto
            $ponente->sincronizar($_POST);

            // Validamos los datos del formulario
            $alertas = $ponente->validar();

            // Verificamos que no haya ningun error
            if (empty($alertas)) {
                // Si existe una nueva imagen la guardaremos en el servidor
                if (isset($nombre_imagen)) {
                    $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");
                }

                // Actualizamos el registro en la base de datos
                $resultado = $ponente->guardar();

                if ($resultado) {
                    header("Location: /admin/ponentes");
                }
            }
        }

        $router->render('admin/ponentes/editar', [
            'titulo' => 'Editar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function eliminar()
    {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (!is_admin()) {
                header("Location: /login");
            }
            $id = $_POST['id'];

            $ponente = Ponentes::find($id);

            if (isset($ponente)) {
                header("Location: /admin/ponentes");
            }

            $resultado = $ponente->eliminar();

            if ($resultado) {
                header("Location: /admin/ponentes");
            }
        }
    }
}

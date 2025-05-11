<?php

namespace Controllers;

use Model\Evento;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {

        // Obtener los ultimos registros
        $registros = Registro::get(5);
        foreach ($registros as $registro) {
            $registro->usuario = Usuario::find($registro->usuario_id);
        }

        // Calcular los ingresos
        $virtuales = (float) Registro::where('paquete_id', 2);
        $presenciales = (float) Registro::where('paquete_id', 1);

        $ingresos = ($virtuales * 46.41) + ($presenciales * 189.54);

        // Obtener eventos con más y menos lugares disponibles
        $menos_lugares = Evento::ordenarLimite('disponibles', 'ASC', 5);
        $mas_lugares = Evento::ordenarLimite('disponibles', 'DESC', 5);


        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de Administracion',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_lugares' => $menos_lugares,
            'mas_lugares' => $mas_lugares
        ]);
    }
}

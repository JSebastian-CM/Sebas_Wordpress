<?php
include 'accion_1.php';

$acciones = [];

function add_action_prueba($nombre_hook, $nombre_function){
    global $acciones;
    if (!isset($acciones[$nombre_hook])) {
        $acciones[$nombre_hook] = [];
    }
    $acciones[$nombre_hook][] = $nombre_function;
}

function llamar_acciones($hook_a_llamar){
    global $acciones;
    if (isset($acciones[$hook_a_llamar])) {
        foreach ($acciones[$hook_a_llamar] as $funcion) {
            call_user_func($funcion);
        }
    }
}

llamar_acciones('prueba_hook');


llamar_acciones('prueba_2_hook');


llamar_acciones('prueba_3_hook');
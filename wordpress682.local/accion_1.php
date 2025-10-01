<?php


function mi_accion(){
    echo 'Hola';
}

add_action_prueba('prueba_hook', 'mi_accion');
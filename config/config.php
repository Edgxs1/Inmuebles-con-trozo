<?php
if (!defined('BASE_URL')) {

    if ($_SERVER['HTTP_HOST'] === 'tecweb.e-cec.org.mx') {
        define('BASE_URL', 'https://tecweb.e-cec.org.mx/4CV3/rosaedga/Proyecto/');
    } else {
        define('BASE_URL', 'http://192.168.100.3/tweb/ProyectoInmuebles/'); 
    }
}
?>
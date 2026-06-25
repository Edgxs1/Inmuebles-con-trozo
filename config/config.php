<?php
if (!defined('BASE_URL')) {

    $host = $_SERVER['HTTP_HOST'];

    if ($host === 'tecweb.e-cec.org.mx') {
        define('BASE_URL', 'https://tecweb.e-cec.org.mx/4CV3/rosaedga/ProyectoFinal/');
        define('DB_HOST', '127.0.0.1');
        define('DB_NAME', '2025proybtw');
        define('DB_USER', '202501btw'); 
        define('DB_PASS', '2025#01062'); 
    } else {

        define('BASE_URL', 'http://192.168.100.3/tweb/ProyectoInmuebles/');
        define('DB_HOST', '127.0.0.1');
        define('DB_NAME', '202501btw');
        define('DB_USER', 'admin');
        define('DB_PASS', 'holamundo');
    }
}
?>
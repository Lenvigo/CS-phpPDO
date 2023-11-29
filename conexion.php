
<?php

const SETTINGS_INI = 'db_settings.ini';

/**
 * Summary of getConnection
 * Crea un objeto PDO
 * @return PDO|null un objeto PDO si ha habido éxito creando la conexión, null en caso contrario
 */
function getConnection()
{
    //1- En el script conexion.php, modifica la función getConnection() para que lea los datos de conexión de un fichero db_settings.ini en lugar de almacenar directamente los datos de conexión el propio archivo php. En él deberán figurar:
    
        if (!$settings = parse_ini_file(SETTINGS_INI, true))
        throw new Exception("ERROR: Unable to open" . SETTINGS_INI);

    $con = null;
    $driver = $settings["database"]["driver"];
    $host = $settings["database"]["host"];
    $db = $settings["database"]["schema"];
    $user = $settings["database"]["username"];
    $pass = $settings["database"]["password"];
    $dsn = $driver . ":host=$host;dbname=$db";
    $persistent = $settings["database"]["persistent"];


    try {

        /* $con = new PDO($dsn, $user, $pass,  array( //creacion nuevo objeto de conexion PDO
            PDO::ATTR_PERSISTENT => true //habilita las conezxiones persistentes para mejorar rendimiento consultas posteriores
        )); */

        $con = new PDO($dsn, $user, $pass,  array(
            PDO::ATTR_PERSISTENT => $persistent
        ));

        //Esto no hace falta en versión PHP 8 y superiores: https://www.php.net/manual/en/pdo.error-handling.php
        //PDO::ERRMODE_EXCEPTION: As of PHP 8.0.0, this is the default mode.
        //$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $ex) { //captura de excepciones

        echo "Error de conexión: mensaje: " . $ex->getMessage(); //mensaje de error
    }
    return $con;
}

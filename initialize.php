<?php
// PARA CONEXION LOCALHOST (XAMPP)
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
// if(!defined('base_url')) define('base_url','http://localhost/Sistema de Facturación 2/');
// if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
// if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
// if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
// if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
// if(!defined('DB_NAME')) define('DB_NAME',"invoice_db");
// FINAL PARA CONEXION LOCALHOST (XAMPP)


// PARA EL RAILWAY DATABASE CONEXIÓN EN LA NUBE
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
// if(!defined('base_url')) define('base_url','https://sistema-de-facturacion-8yk6.onrender.com/');
// if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
// define('DB_SERVER', 'switchyard.proxy.rlwy.net');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', 'CSkndWRkJgACSBtpxwSMYXOkEVEmBWkw');
// define('DB_NAME', 'invoice_db');
// define('DB_PORT', 30688); 
// FINAL PARA EL RAILWAY DATABASE CONEXIÓN EN LA NUBE

// PARA EL CLEVER CLOUD CONEXIÓN EN LA NUBE
// Datos del desarrollador (sin cambios)
$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
// URL base (sin cambios, apunta a Render)
if(!defined('base_url')) define('base_url','https://sistema-de-facturacion-8yk6.onrender.com/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
if(!defined('dev_data')) define('dev_data',$dev_data);
// ==========================================================
// NUEVAS CONSTANTES DE CONEXIÓN (CLEVER CLOUD)
// ==========================================================
define('DB_SERVER', 'b0ecjoiz2jpdmx7luq7i-mysql.services.clever-cloud.com');
define('DB_USERNAME', 'uo8pp47qpjteiacx');
define('DB_PASSWORD', 'uo8pp47qpjteiacx');
define('DB_NAME', 'b0ecjoiz2jpdmx7luq7i');
define('DB_PORT', 3306);
// ==========================================================
// FINAL PARA EL CLEVER CLOUD CONEXIÓN EN LA NUBE
?>


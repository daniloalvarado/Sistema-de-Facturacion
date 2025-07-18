<?php
// PARA CONEXION LOCALHOST (XAMPP)
// if (!defined('DB_SERVER')) {
//     require_once("../initialize.php");
// }
// class DBConnection
// {
//     private $host = DB_SERVER;
//     private $username = DB_USERNAME;
//     private $password = DB_PASSWORD;
//     private $database = DB_NAME;

//     public $conn;

//     public function __construct()
//     {
//         if (!isset($this->conn)) {

//             $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

//             if (!$this->conn) {
//                 echo 'Cannot connect to database server';
//                 exit;
//             }
//         }
//     }
//     public function __destruct()
//     {
//         $this->conn->close();
//     }
// }
// FINAL PARA CONEXION LOCALHOST (XAMPP)


// PARA EL RAILWAY DATABASE CONEXIÓN EN LA NUBE
if (!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection
{
    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $port = DB_PORT; // 👈 AÑADIDO

    public $conn;

    public function __construct()
    {
        if (!isset($this->conn)) {
            // 👇 AÑADIDO EL PUERTO EN LA CONEXIÓN
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

            if ($this->conn->connect_error) {
                echo 'Cannot connect to database server: ' . $this->conn->connect_error;
                exit;
            }
        }
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
// FINAL PARA EL RAILWAY DATABASE CONEXIÓN EN LA NUBE

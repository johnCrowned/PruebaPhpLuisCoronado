<?php
header("Content-Type: text/html;charset=utf-8");
ini_set('display_errors',0);
require('constantes.ini');
class Database {
	 protected $conn;
 	public function __construct() {
		 try{
			 $this->conn = new PDO(DB_TIPO.DB_HOST.DB_BASE.COTEJAMIENTO,DB_USER,DB_PASS);
			 $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	 	 }
		 catch(PDOException $e ){
			echo "Fallo la Conexion: ".$e->getMessage();
		 }
	}
}
?>

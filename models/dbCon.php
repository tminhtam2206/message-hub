<?php
	require_once "./libs/config_database.php";
	
	class dbCon{
		private static $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;

		private static $options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_TIMEOUT => 180
		);

		private static $conn;
		
		public static function KetNoi(){
			if(!isset(self::$conn)){
				try{
					self::$conn = new PDO(self::$dsn, DB_USER, DB_PASS, self::$options);
				}
				catch(PDOException $e){
					echo $error_message = $e->getMessage();
					//include_once "views/layouts/error.php";
					exit();
				}
			}
			return self::$conn;
		}
		
		public static function HuyKetNoi(){
			self::$conn = null;
		}
	}
?>
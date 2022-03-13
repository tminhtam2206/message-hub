<?php  require_once "./models/dbCon.php";
	class Table_Model{
		private $Table;

		function __construct(){
			$this->Table = new dbCon();
			$this->Table = $this->Table->KetNoi();
		}

		public function GetSize(){
			try{
				require_once './libs/config_database.php';
				//size in Kib
				$qr = "SELECT table_schema 'database', ROUND(SUM(data_length + index_length) / 1024, 1) 'Size' FROM information_schema.tables GROUP BY table_schema";
				$cmd = $this->Table->prepare($qr);
				$cmd->execute();
				$result = $cmd->fetchAll();
				$total = 0;

				foreach ($result as $value) {
					if($value['database'] == DB_NAME)
						$total += $value['Size'];
				}

				return round($total/30720, 2).'%';
			}
			catch(PDOException $e){
				return $e->getTable();
			}
		}
	}
?>
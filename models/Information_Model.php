<?php  require_once "./models/dbCon.php";
	class Information_Model{
		private $Information;

		function __construct(){
			$this->Information = new dbCon();
			$this->Information = $this->Information->KetNoi();
		}

		public function Insert($ip_address){
			try{
				$qr = "INSERT INTO tbl_information(account_id, ip_address) VALUES (:account_id, :ip_address)";
				$cmd = $this->Information->prepare($qr);
				$cmd->bindValue(":account_id", $_SESSION['message_userID']);
				$cmd->bindValue(":ip_address", $ip_address);
				$cmd->execute();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function GetAll($account_id){
			try{
				$qr = "SELECT info.*, acc.username FROM tbl_information info, tbl_account acc WHERE info.account_id = acc.id AND info.account_id = :account_id ORDER BY info.date_create DESC LIMIT 0, 50";
				$cmd = $this->Information->prepare($qr);
				$cmd->bindValue(":account_id", $account_id);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return false;
			}
		}
	}
?>
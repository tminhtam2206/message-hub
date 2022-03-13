<?php  require_once "./models/dbCon.php";
	class Message_Model{
		private $Message;

		function __construct(){
			$this->Message = new dbCon();
			$this->Message = $this->Message->KetNoi();
		}

		public function GetMessage(){
			try{
				$qr = "SELECT * FROM tbl_message WHERE username_id = :username_id ORDER BY date_create DESC limit 0, 25";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $_SESSION['message_userID']);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function GetMessageFrom($form){
			try{
				$qr = "SELECT * FROM tbl_message WHERE username_id = :username_id ORDER BY date_create DESC limit $form, 25";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $_SESSION['message_userID']);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function Insert($mess){
			try{
				$qr = "INSERT INTO tbl_message(username_id, message) VALUES (:username_id, :message)";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $_SESSION['message_userID']);
				$cmd->bindValue(":message", $mess);
				$cmd->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function GetNow(){
			try{
				$qr = "SELECT * FROM tbl_message WHERE username_id = :username_id ORDER BY date_create DESC limit 0, 1";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $_SESSION['message_userID']);
				$cmd->execute();
				return $cmd->fetch();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function CountMessage(){
			try{
				$qr = "SELECT id FROM tbl_message WHERE username_id = :username_id";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $_SESSION['message_userID']);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function DeleteMessage($id){
			try{
				$qr = "DELETE FROM tbl_message WHERE id = :id";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":id", $id);
				$cmd->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}
		
		public function UpdateMessage($id, $mess){
			try{
				$qr = "UPDATE tbl_message SET message = :message WHERE id = :id";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":message", $mess);
				$cmd->bindValue(":id", $id);
				$cmd->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function GetDataMessage($account_id){
			try{
				$qr = "SELECT message FROM tbl_message WHERE username_id = :username_id";
				$cmd = $this->Message->prepare($qr);
				$cmd->bindValue(":username_id", $account_id);
				$cmd->execute();
				return $cmd->fetchAll();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}
	}
?>
<?php  require_once "./models/dbCon.php";
	class Forums_Model{
		private $Forums;

		function __construct(){
			$this->Forums = new dbCon();
			$this->Forums = $this->Forums->KetNoi();
		}

		public function GetForums(){
			try{
				$qr = "SELECT account.username, account.avatar, forum.* FROM tbl_forums forum, tbl_account account WHERE account.id = forum.account_id ORDER BY date_create DESC LIMIT 0, 25";
				$cmd = $this->Forums->prepare($qr);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function CountMessForums(){
			try{
				$qr = "SELECT account.username FROM tbl_forums forum, tbl_account account WHERE account.id = forum.account_id";
				$cmd = $this->Forums->prepare($qr);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function Insert($mess){
			try{
				$qr = "INSERT INTO tbl_forums(account_id, message) VALUES (:account_id, :message)";
				$cmd = $this->Forums->prepare($qr);
				$cmd->bindValue(":account_id", $_SESSION['message_userID']);
				$cmd->bindValue(":message", $mess);
				$cmd->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function GetNow(){
			try{
				$qr = "SELECT account.username, account.avatar, forum.* FROM tbl_forums forum, tbl_account account WHERE account.id = forum.account_id AND forum.account_id = :account_id ORDER BY date_create DESC LIMIT 0, 1";
				$cmd = $this->Forums->prepare($qr);
				$cmd->bindValue(":account_id", $_SESSION['message_userID']);
				$cmd->execute();
				return $cmd->fetch();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function GetNowForm($form){
			try{
				$qr = "SELECT account.username, account.avatar, forum.* FROM tbl_forums forum, tbl_account account WHERE account.id = forum.account_id ORDER BY date_create DESC LIMIT $form, 25";
				$cmd = $this->Forums->prepare($qr);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}
	}
?>
<?php  require_once "./models/dbCon.php";
	class Account_Model{
		private $Account;

		function __construct(){
			$this->Account = new dbCon();
			$this->Account = $this->Account->KetNoi();
		}

		public function Insert($user, $pass){
			try{
				$qr = "INSERT INTO tbl_account(username, password) VALUES (:username, :password)";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":username", $user);
				$cmd->bindValue(":password", $pass);
				$cmd->execute();
				return true;
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function GetInfomation($user){
			try{
				$qr = "SELECT * FROM tbl_account WHERE username = :username";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":username", $user);
				$cmd->execute();
				return $cmd->fetch();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}

		public function CheckLogin($user, $pass){
			try{
				$qr = "SELECT * FROM tbl_account WHERE username = :username AND password = :password";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":username", $user);
				$cmd->bindValue(":password", $pass);
				$cmd->execute();
				return $cmd->rowCount() > 0;
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function ChangeUsername($user, $pass){
			try{
				$qr = "UPDATE tbl_account SET username = :username WHERE id = :id AND password = :password";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":username", $user);
				$cmd->bindValue(":id", $_SESSION['message_userID']);
				$cmd->bindValue(":password", $pass);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function ChangePassword($new_pass, $old_pass){
			try{
				$qr = "UPDATE tbl_account SET password = :new_pass WHERE id = :id AND password = :old_password";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":new_pass", $new_pass);
				$cmd->bindValue(":id", $_SESSION['message_userID']);
				$cmd->bindValue(":old_password", $old_pass);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function ChangeUserPassword($user, $new_pass, $old_pass){
			try{
				$qr = "UPDATE tbl_account SET username = :username, password = :new_pass WHERE id = :id AND password = :old_password";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":username", $user);
				$cmd->bindValue(":new_pass", $new_pass);
				$cmd->bindValue(":id", $_SESSION['message_userID']);
				$cmd->bindValue(":old_password", $old_pass);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function CheckAccount($pass){
			try{
				$qr = "SELECT id FROM tbl_account WHERE id = :id AND password = :password";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":id", $_SESSION['message_userID']);
				$cmd->bindValue(":password", $pass);
				$cmd->execute();
				return $cmd->rowCount();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function UpdateAvatar($link){
			try{
				$qr = "UPDATE tbl_account SET avatar = :avatar WHERE id = :id";
				$cmd = $this->Account->prepare($qr);
				$cmd->bindValue(":avatar", $link);
				$cmd->bindValue(":id", $_SESSION['message_userID']);
				$cmd->execute();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function GetAccounts(){
			try{
				$qr = "SELECT * FROM tbl_account";
				$cmd = $this->Account->prepare($qr);
				$cmd->execute();
				return $cmd->fetchAll();
			}
			catch(PDOException $e){
				return false;
			}
		}
	}
?>
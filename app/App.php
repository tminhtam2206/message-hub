<?php  
	class App{
		function __construct(){
			$this->Routes();
		}

		public function Controller($controller){
			require_once "./controllers/".$controller.".php";
			return new $controller;
		}

		public function Routes(){
			if(isset($_GET['url']))
				$url = $_GET['url'];
			else
				$url = "home";
			
			if(isset($_GET['search']))
				$search = $_GET['search'];

			if(isset($search)){
				$this->Controller("TangKinhCac_Controller")->Search($search);
			}

			if(isset($url)){
				switch ($url) {
					case 'process-login':{
						$this->Controller("Message_Controller")->ProcessLogin();
						break;
					}

					case 'logout':{
						$this->Controller("Message_Controller")->ProcessLogout();
						break;
					}

					case 'ajax-push-message':{
						$this->Controller("Message_Controller")->AjaxPushMessage();
						break;
					}

					case 'ajax-get-message':{
						$this->Controller("Message_Controller")->AjaxGetMessage();
						break;
					}

					case 'process-update-account':{
						$this->Controller("Message_Controller")->ProcessUpdateAccount();
						break;
					}

					case 'process-delete-message':{
						$this->Controller("Message_Controller")->ProcessDeleteMessage();
						break;
					}

					case 'process-edit-message':{
						$this->Controller("Message_Controller")->ProcessEditMessage();
						break;
					}

					case 'process-database-size':{
						$this->Controller("Message_Controller")->ProcessDatabaseSize();
						break;
					}

					case 'process-change-avatar':{
						$this->Controller("Message_Controller")->ProcessChangeAvatar();
						break;
					}

					case 'forums':{
						if(isset($_SESSION['message_userID'])){
							$this->Controller("Message_Controller")->Forums();
						}else{
							header("Location: ./");
						}
						
						break;
					}

					case 'process-push-forums':{
						$this->Controller("Message_Controller")->ProcessPushForums();
						break;
					}

					case 'process-prev-forums':{
						$this->Controller("Message_Controller")->ProcessPrevForums();
						break;
					}

					case 'ajax-get-record-account':{
						$this->Controller("Message_Controller")->AjaxGetRecordAccount();
						break;
					}

					case 'ajax-get-data-mess':{
						$this->Controller("Message_Controller")->AjaxGetDataMess();
						break;
					}
					
					default:{
						if(isset($_SESSION['message_userID'])){
							$this->Controller("Message_Controller")->HomePage();
						}else{
							$this->Controller("Message_Controller")->Login();
						}
						break;
					}
				}
			}
			
		}
	}
?>
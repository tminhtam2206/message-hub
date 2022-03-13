<?php  
	require_once "./models/Account_Model.php";
	require_once "./models/Message_Model.php";
	require_once "./models/Table_Model.php";
	require_once "./models/Information_Model.php";
	require_once "./models/Forums_Model.php";

	class Message_Controller{
		private $Account;
		private $Message;
		private $Table;
		private $Information;
		private $Forums;

		function __construct(){
			$this->Account = new Account_Model();	
			$this->Message = new Message_Model();
			$this->Table = new Table_Model();
			$this->Information = new Information_Model();
			$this->Forums = new Forums_Model();
		}

		public function view($view, $data=[]){
			require_once "./views/".$view.".php";
		}

		public function Login(){
			$this->view("login");
		}

		public function ProcessLogin(){
			require_once './libs/middleware.php';
			require_once './libs/functions.php';

			$user = $_POST['username'];
			$pass = sha1(salt.$_POST['password']);

			if($this->Account->CheckLogin($user, $pass)){
				$account = $this->Account->GetInfomation($user);
				
				if($account['role'] == 'member'){
					setcookie("error_user", $user, time() + 2, "/");
					setcookie("error_message", "You can not access this page!", time() + 2, "/");
					header('Location: ./?url=login');
				}else{
					$_SESSION['message_userID'] = $account['id'];
					$_SESSION['message_userNAME'] = $account['username'];
					$_SESSION['message_userROLE'] = $account['role'];
					$_SESSION['message_userAVATAR'] = $account['avatar'];
					$_SESSION['message_userIPADRESS'] = get_client_ip();
					$_SESSION['message_userJOIN'] = $account['date_create'];

					$this->Information->Insert($_SESSION['message_userIPADRESS']);

					header('Location: ./');
				}
			}else{
				setcookie("error_user", $user, time() + 2, "/");
				setcookie("error_message", "Username or password is incorrect!", time() + 2, "/");
				header('Location: ./?url=login');
			}
		}

		public function ProcessLogout(){
			unset($_SESSION['message_userID']);
			unset($_SESSION['message_userNAME']);
			unset($_SESSION['message_userROLE']);
			unset($_SESSION['message_userAVATAR']);
			unset($_SESSION['message_userIPADRESS']);
			unset($_SESSION['message_userJOIN']);

			header('Location: ./');
		}

		public function HomePage(){
			require_once './libs/functions.php';

			$this->view("message", [
				'page'			=> 	'account_list',
				'db_size'		=>	$this->Table->GetSize(),
				'account_list'	=>	$this->Account->GetAccounts()
			]);
		}

		public function ProcessDatabaseSize(){
			echo $this->Table->GetSize();
		}
		
		public function ProcessChangeAvatar(){
			require_once './libs/functions.php';

			if(isset($_FILES['file']['name'])){
				if($_SESSION['message_userAVATAR'] != "./storage/user.jpeg"){
					unlink($_SESSION['message_userAVATAR']);
				}

				/* Getting file name */
				$filename = $_FILES['file']['name'];

				/* Location */
				$location = "./storage/"."avatar-".RandomCode()."-".$filename;
				$imageFileType = pathinfo($location, PATHINFO_EXTENSION);
				$imageFileType = strtolower($imageFileType);

				/* Valid extensions */
				$valid_extensions = array("jpg","jpeg","png");

				$response = 0;
				/* Check file extension */
				if(in_array(strtolower($imageFileType), $valid_extensions)) {
				  	/* Upload file */
				    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
				        $this->Account->UpdateAvatar($location);
				        $_SESSION['message_userAVATAR'] = $location;
				    }
				}
			}
		}

		public function Forums(){
			require_once './libs/functions.php';

			$this->view('message', [
				'page'			=>	'forums',
				'db_size'		=>	$this->Table->GetSize(),
				'mess_forums'	=>	$this->Forums->GetForums(),
				'num_mess'		=> 	$this->Forums->CountMessForums()
			]);
		}

		public function ProcessPushForums(){
			require_once './libs/functions.php';
			$this->Forums->Insert(FormatMessage($_POST['mess']));
			$data = $this->Forums->GetNow();
			$data_return = '';

			$data_return .= '<div class="direct-chat-msg right mt-4">
                <img class="direct-chat-img" src="'.getAvatar($data['avatar']).'" alt="message user image">
                <div class="direct-chat-text">
                    '.ConvertMessage($data['message']).'
                    <hr style="margin: 0px; padding: 0px;">
                    <span class="direct-chat-timestamp text-light">'.$data['username'].'</span> | 
                    <span class="direct-chat-timestamp text-light">'.ConvertDate($data['date_create']).'</span>
                </div>
            </div>';

			echo $data_return;
		}

		public function ProcessPrevForums(){
			require_once './libs/functions.php';

			$data = $this->Forums->GetNowForm($_POST['from']);
			$data_return = '';

			foreach ($data as $value) {
				if($value['account_id'] == $_SESSION['message_userID']){
					$data_return .= '<div class="direct-chat-msg right mt-4">
                        <img class="direct-chat-img" src="'.getAvatar($value['avatar']).'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>
                        </div>
                    </div>';
				}else{
					$data_return .= '<div class="direct-chat-msg mt-4">
                        <img class="direct-chat-img" src="'.getAvatar($value['avatar']).'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>   
                        </div>
                    </div>';
				}
			}
			echo $data_return;
		}

		public function AjaxGetRecordAccount(){
			require_once './libs/functions.php';

			$result = $this->Information->GetAll($_POST['accID']);
			$data_return = '<div class="table-responsive" style="height:400px;"><table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">USERNAME</th>
                    <th scope="col">IP ADDRESS</th>
                    <th scope="col">LOGIN DATE</th>
                </tr>
            </thead>
            <tbody>';

            $i = 1;
			foreach ($result as $value) {
				$data_return .= '<tr>
                    <th scope="row" width="60">'.$i.'</th>
                    <td>'.$value['username'].'</td>
                    <td>'.$value['ip_address'].'</td>
                    <td>'.ConvertDate($value['date_create']).'</td>
                </tr>';
                $i++;
			}

			$data_return .= '</tbody></table></div>';

			echo $data_return;
		}

		public function AjaxGetDataMess(){
			require_once './libs/functions.php';

			$data = $this->Message->GetDataMessage($_POST['accID']);
			$data_return = '';

			foreach ($data as $value) {
				$data_return .= $value['message'].FormatMessage("\n\n");
			}

			echo $data_return;
		}
	}
?>
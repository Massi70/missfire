<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webservices extends CI_Controller {

	public $_uId;
	public $_userData;
	public $_assignData;
	public $_checkUser;

	public function __construct()
	{
        parent::__construct();
		$this->load->model('userModel');
		$this->load->model('webservicesModel');
		$this->load->model('ServicesModel');
    }

	private $_headerData = array();
	private $_navData = array();
	private $_footerData = array();
	private $_jsonData = array();
	private $_Data = array();

	/* sign in and singup code starts*/
	public function signin()
	{
		$userId = $this->input->get_post('user_id');
		$title = $this->input->get_post('title');
		$userName = $this->input->get_post('user_name');
		$firstName = $this->input->get_post('first_name');
		$lastName = $this->input->get_post('last_name');
		$userEmail = $this->input->get_post('user_email');
		$userGender = $this->input->get_post('user_gender');

	try{
			if($userId == false || $userId == ""  ){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User ID Missing";
			}else if($userName == false || $userName == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Username Missing";
			}else if($firstName == false || $firstName== ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="first_name Missing";
			}else if($lastName == false || $lastName == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="last_name Missing";
			}else if($userEmail == false || $userEmail == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="user_email Missing";
			}else if($userGender == false || $userGender == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="user_gender Missing";
			}else{
				if($title=='Signin'){
					if($userId!=0)
					{
						$check = $this->webservicesModel->getUserDetails($userId);
						$this->_jsonData['status']="SUCCESS";
						if(count($check)>0)
						{
							$this->_jsonData['message']="User logged in successfully";
							$this->_jsonData['data']='';
						}else{
					//******** Array for insert values into the table user *************/
							$data= array(
								'user_id'=>$userId,
								'user_name'=>$userName,
								'first_name'=>$firstName,
								'last_name'=>$lastName,
								'user_email'=>$userEmail,
								'joined_date'=>date('Y-m-d'),
								'user_gender'=>$userGender,
								'user_coins'=>1000,
								);
								$this->db->insert('user',$data);
								$this->_jsonData['status']='SUCCESS';
								$this->_jsonData['message']="Data Inserted Successfully";
								$this->_jsonData['data']='';
							}
					}
				}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'signin',$_FILES);
		
	}
	
		/* sign in and singup cose ends*/
	
	/* select categories code start */
	public function getCategories()
	{
		try{
			$category = $this->userModel->getCategory();
			if(count($category)>0){
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="Categories Data";
				$this->_jsonData['data']=$category;
			}else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="No Category Data Found";
			}
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getCategories',$_FILES);
	}

		/* select categories code ends */		
		
		/*********** Submit Bet code Starts *******************/
		
		public function submitBet()
		{
			$aceptorId = $this->input->get_post('aceptor_id');
			$createrId = $this->input->get_post('creater_id');
			$txtTitle = $this->input->get_post('txt_title');
			$title = $this->input->get_post('title');
			$category = $this->input->get_post('category');
			$question = $this->input->get_post('question');
			$answerType = $this->input->get_post('answer_type');
			$myAnswer = $this->input->get_post('my_answer');
			$yourAnswer = $this->input->get_post('your_answer');
			$myImage = $this->input->get_post('my_image');
			$yourImage = $this->input->get_post('your_image');
			$myVideo = $this->input->get_post('my_video');
			$yourVideo = $this->input->get_post('your_video');
			$wager = $this->input->get_post('wager');
			$post = $this->input->get_post('post');
			$timelimit = $this->input->get_post('timelimit');
			$expire = $this->input->get_post('expire');
			$tountFriend = $this->input->get_post('tount_friend');
			$answerTitle = $this->input->get_post('answer_title');

		try{
			if($aceptorId == false || $aceptorId == "")
			{
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Acceptor Id is Missing";
			}else if($createrId == false || $createrId == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Creater Id is Missing";
			}else if($title == false || $title == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Title is Missing";
			}else if($category == false || $category == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Category is Missing";
			}else if($question == false || $question == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Question is Missing";
			}else if($answerType == false || $answerType == ""){
				//echo 'Hello World';
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Answer Type is Missing";
			}else if($myAnswer == false || $myAnswer == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="My Answer is Missing";
			}else if($yourAnswer == false || $yourAnswer == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Your Answer is Missing";
			}else if($wager == false || $wager == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Wager is Missing";
			}else if($post == false || $post == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Post is Missing";
			}else if($timelimit == false || $timelimit == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Time limit is Missing";
			}else if($expire == false || $expire == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Expire Data is Missing";
			}else if($tountFriend == false || $tountFriend == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Tount friend is Missing";
			}else{
				if($title=='Submitbet'){
					if($createrId!=0 && $aceptorId!=0){
						$data = array(
							'aceptor_id'=>$aceptorId,
							'creater_id'=>$createrId,
							'title'=>$txtTitle,
							'category'=>$category,
							'question'=>$question,
							'answer_type'=>$answerType,
							'my_answer'=>$myAnswer,
							'your_answer'=>$yourAnswer,
							'myImage'=>$my_image,
							'yourImage'=>$your_image,
							'myVideo'=>$my_video,
							'yourVideo'=>$your_video,							
							'wager'=>$wager,
							'post'=>$post,
							'timelimit'=>$timelimit,
							'expire'=>$expire,
							'tount_friend'=>$tountFriend,
							'answer_title'=>$answerTitle,
							);
						$res = $this->userModel->submit_bet($data);
						$this->userModel->submit_bet_histroy($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Bet submited";
						$this->_jsonData['data']='';
					}		
				}
			}
		}catch(Exceptoin $e){
			$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
				echo json_encode($this->_jsonData);	
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'submitBet',$_FILES);

	}

			/*********** Submit Bet code Starts *******************/

		public function submitRepropseBet()
		{
			$createrId= $this->input->get_post('user_id');
			$aceptorId = $this->input->get_post('creater_id');
			$txtTitle = $this->input->get_post('txt_title');
			$title = $this->input->get_post('title');
			$category = $this->input->get_post('category');
			$question = $this->input->get_post('question');
			$answerType = $this->input->get_post('answer_type');
			$myAnswer = $this->input->get_post('my_answer');
			$yourAnswer = $this->input->get_post('your_answer');
			$myImage = $this->input->get_post('my_image');
			$yourImage = $this->input->get_post('your_image');
			$myVideo = $this->input->get_post('my_video');
			$yourVideo = $this->input->get_post('your_video');
			$wager = $this->input->get_post('wager');
			$post = $this->input->get_post('post');
			$timelimit = $this->input->get_post('timelimit');
			$expire = $this->input->get_post('expire');
			$tountFriend = $this->input->get_post('tount_friend');
			$answerTitle = $this->input->get_post('answer_title');
			$categoryId = $this->input->get_post('category_id');
			$betId = $this->input->get_post('bet_id');
			$userCoins = $this->input->get_post('user_coins'); 
			
			try{
				if($aceptorId == false || $aceptorId == "")
				{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Acceptor Id is Missing";
				}else if($createrId == false || $createrId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Creater Id is Missing";
				}else if($title == false || $title == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Title is Missing";
				}else if($category == false || $category == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Category is Missing";
				}else if($question == false || $question == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Question is Missing";
				}else if($answerType == false || $answerType == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Answer Type is Missing";
				}else if($myAnswer == false || $myAnswer == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="My Answer is Missing";
				}else if($yourAnswer == false || $yourAnswer == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Your Answer is Missing";
				}else if($wager == false || $wager == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Wager is Missing";
				}else if($post == false || $post == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Post is Missing";
				}else if($timelimit == false || $timelimit == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Time limit is Missing";
				}else if($expire == false || $expire == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Expire Data is Missing";
				}else if($tountFriend == false || $tountFriend == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Tount friend is Missing";
				}else if($categoryId == false || $categoryId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="category Id is Missing";
				}else if($betId == false || $betId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="bet Id is Missing";
				}else if($userCoins == false || $userCoins == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="user coins is Missing";
				}else{
					if($title=='Reproposebet'){
						if($createrId!=0 && $aceptorId!=0){
							$data = array(
								'aceptor_id'=>$aceptorId,
								'creater_id'=>$createrId,
								'title'=>$txtTitle,
								'category'=>$category,
								'question'=>$question,
								'answer_type'=>$answerType,
								'answer_title'=>$answerTitle,
								'my_answer'=>$myAnswer,
								'your_answer'=>$yourAnswer,
								'my_image'=>$myImage,
								'your_image'=>$yourImage,
								'my_video'=>$myVideo,
								'you_video'=>$yourVideo,
								'wager'=>$wager,
								'post'=>$post,
								'timelimit'=>$timelimit,
								'expire'=>$expire,
								'tount_friend'=>$tountFriend,
								'category_id'=>$categoryId,
								'bet_id'=>$betId
								);
								
							$betOldData=$this->userModel->getSpecBet($betId);
							if($userCoins>=$wager){
								$this->userModel->update_bet_histroy($betOldData);
								$this->userModel->submitRepropseBet($data);
								$this->_jsonData['status']="SUCCESS";
								$this->_jsonData['message']="Bet Reproposed";
								$this->_jsonData['data']='';
							}else{
								$this->_jsonData['status']="FAILURE";
								$this->_jsonData['message']="User Coins is less then bet wager";
								$this->_jsonData['data']='';
							}
						}		
					}
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
			}
			echo json_encode($this->_jsonData);	
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'submitRepropseBet',$_FILES);
	}
	
	
		function acceptBet()
		{
			$userId= $this->input->get_post('user_id');
			$userCoins= $this->input->get_post('user_coins');
			$betId= $this->input->get_post('bet_id');
			$betWager =$this->input->get_post('bet_wager');
			
			try{
				if($userId == false || $userId == "")
				{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Id is Missing";
				}else if($userCoins == false || $userCoins == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Coins is Missing";
				}else if($betId == false || $betId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Id is Missing";
				}else if($betWager == false || $betWager == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Wager is Missing";
				}else{
					if($userCoins>=$betWager){
						$this->userModel->updateBet($userId,$betId);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Bet Accepted Successfully";
						$this->_jsonData['data']='';
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="User Coins is less then bet wager";
						$this->_jsonData['data']='';
					}
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
			}
			echo json_encode($this->_jsonData);	
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'acceptBet',$_FILES);
		}
		
		public function getCategoryData()
		{
			$this->load->helper('pagination_helper');
			$pagination=Pagination_helper::getInstance();
			$data['limit']='5';
			$ajax= isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '0';
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['page'] =($data['page']==false) ? 1 : $data['page']; 
			$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;
			
			$categoryId= $this->input->get_post('category_id');
			$userId= $this->input->get_post('user_id');
			
			try{
				if($categoryId > 0 && $categoryId <= 9 ){
					$data = $this->userModel->getAllBets($categoryId,$userId,$data);
					$data['getAllBetsCount']=$this->userModel->getAllBetsCount($categoryId,$userId,$data);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Bets Selected";
					$this->_jsonData['data']=$data;
				}
				else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Category Id is Missing";
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
			}
				echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getCategoryData',$_FILES);

		}
		
		
		public function getPendingBet()
		{
			$categoryId= $this->input->get_post('category_id');
			$userId= $this->input->get_post('user_id');
			try{
				if($userId != 0){
					if($categoryId > 0 && $categoryId <= 9 ){
						$data = $this->userModel->getBets($userId,$categoryId);
						if(count($data)>0){
							$this->_jsonData['status']="SUCCESS";
							$this->_jsonData['message']="Bets Selected";
						}else{
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="No Pending Bets";
						}
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="Category id is missing ";
					}
				}
				else
				{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="No Pending Bets";
				}
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Error Occured";
			}
				echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getPendingBet',$_FILES);

		}
		
		public function topBetter()
		{
			$categoryId= $this->input->get_post('category_id');
			try{
				if($categoryId > 0 && $categoryId <= 9 ){
					$data = $this->userModel->topBetter($categoryId);
					if(count($data)>0){
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Top Better Selected";
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No Any Top Better";
					}
					$this->_jsonData['data']=$data;
				}else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Category id is missing ";
				}
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Error Occured";
			}
				echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'topBetter',$_FILES);

		}
		
		public function searchBet()
		{
			$value = $this->input->get_post('value');	
			$categoryId = $this->input->get_post('category_id');	
			$userId = $this->input->get_post('user_id');
			try{
				if($userId != 0){
					if($categoryId > 0 && $categoryId <= 9 ){
						$data = $this->userModel->getSearchBets($userId,$categoryId,$value);
						if(count($data)>0){
							$this->_jsonData['status']="SUCCESS";
							$this->_jsonData['message']="Data Searched";
							$this->_jsonData['data']=$data;
						}else{
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="No data Searched";
						}
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="Category id is missing ";
					}
				}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="User id is missing ";
				}
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Error Occured";
			}
				echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'searchBet',$_FILES);
			
		}
}

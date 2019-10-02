<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Betcontroler extends CI_Controller {

	public $_uId;
	public $_userData;
	public $_assignData;
	public $_checkUser;

	public function __construct(){
        parent::__construct();

		$this->load->model('userModel');
		$this->load->model('webservicesModel');
		$this->load->model('ServicesModel');
		$this->load->model('compareModel');
   }

	private $_headerData = array();
	private $_navData = array();
	private $_footerData = array();
	private $_jsonData = array();
	private $_finalData = array();

	/* deleteBetStatus code starts*/

	public function deleteBetStatus(){
        $betId = $this->input->get_post('bet_id',TRUE) ? $this->input->get_post('bet_id',TRUE) :  0;
		$userId = $this->input->get_post('user_id',TRUE) ? $this->input->get_post('user_id',TRUE) :  0;
		try{
			if($betId==0){
				$data['status']='FAILURE';
				$data['message']='bet_id is missing';
			}else if($userId==0){
				$data['status']='FAILURE';
				$data['message']='user_id is missing';
			}else{
				$betData=$this->userModel->getSpecBet($betId);
				if(is_array($betData) && count($betData)>0){
					$deleteBetStatus=$this->userModel->deleteBets($betId);
					$data['status']='SUCCESS';
					$data['message']='Bet Deleted Successfully';   
				}else{
					$data['status']='FAILURE';
					$data['message']='No Bet Available'; 
				}

			}
        }catch(Exception $e){
            $data['status']='FAILURE';
            $data['message']='Error Occured';
        }
        $this->ServicesModel->createService($_REQUEST,$data,$_SERVER['SERVER_ADDR'],'deleteBetStatus',$_FILES);
        echo json_encode($data);
       
    }
	
		/********************* Delete bet status code ends **************/		

		/********************* my Bets code starts *********************/
	public function myBets()
	{
		$this->load->helper('pagination_helper');
		$pagination=Pagination_helper::getInstance();
		$data['limit']='5';
		$data['page'] = $this->input->get_post('page',TRUE) ? $this->input->get_post('page',TRUE) :  1;	
		$data['page'] =($data['page']==false) ? 1 : $data['page']; 
		$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;

        $catId = $this->input->get_post('cat_id',TRUE) ? $this->input->get_post('cat_id',TRUE) :  0;
		$data['category']=$this->userModel->getCategory();
		$userId = $this->input->get_post('user_id',TRUE) ? $this->input->get_post('user_id',TRUE) :  0;
		
		try{
			if($catId==0){
				$data['status']='FAILURE';
				$data['message']='cat_id is missing';
			}else
			if($userId==0){
				$data['status']='FAILURE';
				$data['message']='user_id is missing';
			}else{
				$myBets=$this->userModel->MyBets($userId,$catId,$data);
				if(is_array($myBets) && count($myBets)>0){
					$data['status']='SUCCESS';
					$data['message']='';   
					$data['data']=$myBets;   
				}else{
					$data['status']='FAILURE';
					$data['message']='No Bet Available';
				}
				}
        }catch(Exception $e){
            $data['status']='FAILURE';
            $data['message']='Error Occured';
        }
        $this->ServicesModel->createService($_REQUEST,$data,$_SERVER['SERVER_ADDR'],'paymentPackages',$_FILES);
        echo json_encode($data);
       
    }
		/********************* myBets code ends *********************/

		/********************* SignIn & SignUp code ends **************/	
	
	public function signin()
	{
		$data['user_id'] = $this->input->get_post('user_id');
		$title = $this->input->get_post('title');
		$data['user_name'] = $this->input->get_post('user_name');
		$data['first_name'] = $this->input->get_post('first_name');
		$data['last_name'] = $this->input->get_post('last_name');
		$data['user_email'] = $this->input->get_post('user_email');
		$data['user_gender'] = $this->input->get_post('user_gender');
	try{
			if($data['user_id'] == false || $data['user_id'] == ""  ){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User ID Missing";
			}else if($data['user_email'] == false || $data['user_email'] == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="user_email Missing";
			}
			else if ($title=='Signin'){
				if($data['user_id']!=0){
					$checkUser = $this->webservicesModel->checkUser($data);
					$this->_jsonData['status']="SUCCESS";
					if($checkUser['countUser']>0){
						$this->_jsonData['message']="User logged in successfully";
					}else{
						if($data['user_name'] == false || $data['user_name'] == ""){
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="Username Missing";
						}else if($data['first_name'] == false || $data['first_name']== ""){
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="first_name Missing";
						}else if($data['last_name'] == false || $data['last_name'] == ""){
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="last_name Missing";
						}else if($data['user_gender'] == false || $data['user_gender'] == ""){
							$this->_jsonData['status']="FAILURE";
							$this->_jsonData['message']="user_gender Missing";
						}else{
				/******** Array for insert values into the table user *************/
							$data['joined_date'] = date('Y-m-d');
							$data['user_coins'] = 1000;
							$result = $this->webservicesModel->addUser($data);
							if($result==true){
									$this->_jsonData['message']="Data Inserted Successfully";
							}else{
									$this->_jsonData['status']='FAILURE';
									$this->_jsonData['message']="Data not Inserted Successfully";
							}
						}
					}
					
					$check = array();
					$check = $this->webservicesModel->getUserDetails($data);
					$this->_jsonData['data']=$check;
						
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
	
		/********************* SignIn & SignUp code ends **************/	
	
		/********************* getCategories code Starts **************/	

	public function getCategories()
	{
		$categoryId = $this->input->get_post('category_id');
		try{
				$category = $this->userModel->getCategory();
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="Categories Data";
				$this->_jsonData['data']=$category;
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getCategories',$_FILES);
	}
		/********************* getCategories code ends **************/	
		
		/********************* submitBet code Starts **************/	
		
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
			if($aceptorId == false || $aceptorId == ""){
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

		/********************* submitBet code Ends ******************/	
		
		/********************* submitRepropseBet code Starts **************/	


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
				if($aceptorId == false || $aceptorId == ""){
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

		/********************* submitRepropseBet code ends **************/	

		/********************* acceptBet code Starts **************/	
	
		function acceptBet()
		{
			$userId= $this->input->get_post('user_id');
			$userCoins= $this->input->get_post('user_coins');
			$betId= $this->input->get_post('bet_id');
			$betWager =$this->input->get_post('bet_wager');
			
			try{
				if($userId == false || $userId == ""){
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

		/********************* acceptBet code ends **************/	

		/********************* getCategoryData code Starts **************/	

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
				}else{
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

		/********************* getCategoryData code ends **************/

		/********************* getPendingBet code starts **************/
	
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
				else{
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

		/********************* getPendingBet code ends **************/

		/********************* topBetter code Starts **************/

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
		
		/********************* topBetter code ends **************/
		
		/********************* searchBet code Starts **************/

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
	
		/********************* searchBet code ends **************/
		/********************* vote code starts **************/
	
	
		public function vote()
		{
			$betId = $this->input->get_post('bet_id');	
			$categoryId = $this->input->get_post('cat_id');
			$answerType = $this->input->get_post('answer_type');
			$userId = $this->input->get_post('user_id');
			try{
				if($betId == false || $betId== ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Id Missing";
				}else if($categoryId == false || $categoryId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Category Id Missing";
				}else if($answerType == false || $answerType == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Answer Type Missing";
				}else if($userId == false || $userId == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Id Missing";
				}
				else{
					$data = array(
								'bet_id'=>$betId,
								'cat_id'=>$categoryId,
								'answer_type'=>$answerType,
								'user_id'=>$userId,
					);
					$res=$this->userModel->vote($data);
					if($res){
						//echo 1;
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Voted Successfully";
						$this->_jsonData['data']='';
					}else{
						//echo  0;
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="You already voted for this bet..";
					}
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="You already voted.";
				$this->_jsonData['data']='';
			}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'vote',$_FILES);
		}

		/********************* vote code ends **************/
		
		/********************* openNotification code starts **************/
		
		public function openNotificationX()
		{
			$this->load->helper('pagination_helper');
			$pagination=Pagination_helper::getInstance();
			$ajax= isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '0';

			$data['title'] = $this->input->get_post('title');
			//$data['title'] = $_REQUEST['title'];
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['limit']='5';
			$data['page'] =($data['page']==false) ? 1 : $data['page']; 
			$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;
			$data['user_id'] = $this->input->get_post('user_id');
			try{
				if($data['title'] == false || $data['title'] == ""){
					$this->_jsonData['status']='FAILURE';
					$this->_jsonData['message']="Title is Missing";
				}elseif($data['user_id'] == false || $data['user_id'] == ""){
					$this->_jsonData['status']='FAILURE';
					$this->_jsonData['message']="User Id is Missing";
				}else{
						$data['user_notification_count']=$this->userModel->getBetNotifiCount($data['user_id']);			
						$data['notification_count']=$this->userModel->getNotificationsCount($data);
						$data['notification']=$this->userModel->getNotifications($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Got Notifications Successfully";
						$this->_jsonData['data']=$data;
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="No Notifications";
			}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'openNotification',$_FILES);
				
		}
		
		public function openNotification()
		{
			$this->load->helper('pagination_helper');
			$pagination=Pagination_helper::getInstance();
			$ajax= isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '0';

			//$title = $this->input->get_post('title');
			$data['title'] = $_REQUEST['title'];
			//var_dump($data['title']);
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['limit']='5';
			$data['page'] =($data['page']==false) ? 1 : $data['page']; 
			$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;
			$data['user_id'] = $this->input->get_post('user_id');
			try{
				if($data['user_id'] == false || $data['user_id'] == ""){
					$this->_jsonData['status']='FAILURE';
					$this->_jsonData['message']="User Id is Missing";
				}else{
					if($data['title'] == 'Notification' || $data['title'] == 'new_bets' || $data['title'] == 'Proposed Bets' || $data['title'] == 'General Forum Bets'){
						$data['user_notification_count']=$this->userModel->getBetNotifiCount($data['user_id']);			
						$data['notification_count']=$this->userModel->getNotificationsCount($data);
						$data['notification']=$this->userModel->getNotifications($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Got Notifications Successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']='FAILURE';
						$this->_jsonData['message']="Wrong Title for Notifications";
					}
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="No Notifications";
			}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'openNotification',$_FILES);
				
		}
		/********************* openNotification code ends **************/					
		
		/********************* readNotification code starts **************/

		function readNotificationX()
		{

			$data['title'] = $this->input->get_post('title');
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['notification_id'] = $this->input->get_post('notification_id');
			$data['bet_id'] = $this->input->get_post('bet_id');
			$data['status'] = $this->input->get_post('status');
			$data['user_id'] = $this->input->get_post('user_id');
			try {
				if($data['bet_id'] == false || $data['bet_id'] == "")
				{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Id is Missing";
				}elseif($data['user_id'] == false || $data['user_id'] == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Id is Missing";
				}else{
					if($data['status']==0){
						$this->userModel->updateNotifi($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Read Notifications Updated Successfully";
						$this->_jsonData['data']='';
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No Read Notifications";
					}
				}
					$data['notification']=$this->userModel->getNotifiByID($data);
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="No Read Notifications";
			}
				echo json_encode($this->_jsonData);
				$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'readNotification',$_FILES);

	}
	
		function readNotification()
		{

			$data['title'] = $this->input->get_post('title');
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['notification_id'] = $this->input->get_post('notification_id');
			$data['bet_id'] = $this->input->get_post('bet_id');
			$data['status'] = $this->input->get_post('status');
			$data['user_id'] = $this->input->get_post('user_id');
			try {
				if($data['bet_id'] == false || $data['bet_id'] == "")
				{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Id is Missing";
				}elseif($data['user_id'] == false || $data['user_id'] == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Id is Missing";
				}else{
					if($data['status']==0){
						$this->userModel->updateNotifi($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Read Notifications Updated Successfully";
						$this->_jsonData['data']='';
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No Read Notifications";
					}
				}
					$data['notification']=$this->userModel->getNotifiByID($data);
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="No Read Notifications";
			}
				echo json_encode($this->_jsonData);
				$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'readNotification',$_FILES);

	}

		/********************* readNotification code ends **************/		

		/********************* compareRanking code starts **************/		
		
		function compareRanking()
		{
			$this->load->helper('pagination_helper');
			$pagination=Pagination_helper::getInstance();
			$data['user_id'] = $this->input->get_post('user_id');
			$data['limit']='5';
			$ajax= isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '0';
			$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
			$data['page'] =($data['page']==false) ? 1 : $data['page']; 
			$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;
			$data['category_id'] = $this->input->get_post('category_id');
			$data['title'] = $this->input->get_post('title');
			$data['rankVar'] = isset($_REQUEST['rank']) ? $_REQUEST['rankVar'] : '1';

			try{
				if($data['user_id'] == false || $data['user_id'] == "" ){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Id is Missing";
				}else{
					$data['category']=$this->userModel->getCategory();
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Compare Ranking Updated Successfully";
					$this->_jsonData['data']=$data;
					if($ajax==0){
						if($data['category_id']==0){
							$data['category_id'] = $data['category'][0]['category_id'];
							$data['category_name'] = $data['category'][0]['category_name'];
						}
					}
					$data1['category_id'] = $data['category_id'];
					$users_Count_all =$this->compareModel->getUsersByCatCount($data);
					$data['users_Count'] = count($users_Count_all);
					$data['users_ranks']=$this->compareModel->getUsersByCatId($data);
				}
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Error occured";
			}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'compareRanking',$_FILES);
		}
		
			/********************* compareRanking code ends **************/		
			/********************* deatailBet code starts **************/		

		function detailBet()
		{	
			$data['user_id']=$this->input->get_post('user_id');
			$data['bet_id']=$this->input->get_post('bet_id');
			
			try{
				if($data['bet_id'] == false || $data['bet_id'] == ""){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Bet Id is Missing";
				}else{
					$data['detailbets']=$this->userModel->detailBet($data['bet_id'],$data['user_id']);			
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Details got Successfully";
					$this->_jsonData['data']=$data;
				}
			}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="No Bets Available";
			}
			echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'detailBet',$_FILES);
		}

			/********************* deatailBet code ends **************/		

} // controller ends


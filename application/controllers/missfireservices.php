<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MissfireServices extends CI_Controller {

	public $_uId;
	public $_userData;
	public $_assignData;
	public $_checkUser;
	public $paging = array();

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

		/*************************** sign up code starts ***************************/
	
	public function image(){
		try{
			if($_FILES['file']['name'] == false || $_FILES['file']['name'] == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Image Missing";
			}else{
				  $imgName = time();
				  $imgPath = BASEPATH."../uploads/".$imgName;
				  $image = base_url().'uploads/'.$imgName;

				  if(move_uploaded_file($_FILES["file"]["tmp_name"],$imgPath.".png")){
					  $this->load->library('imagethumb');
					  $this->imagethumb->image($imgPath.".png",100,100);
					  $data = array(
								'image'=>$imgName."_thumb.png",
								'image_path'=>$image."_thumb.png"
							);
					  $this->_jsonData['status']="SUCCESS";
					  $this->_jsonData['message']="Image Inserted Successfully";
					  $this->_jsonData['data']=$data; 
				  }else{
					    $this->_jsonData['status']="FAILURE";
				 	 $this->_jsonData['message']="Image can not be Inserted";
				  	$this->_jsonData['data']=''; 
				  }
				  
			}
				  echo json_encode($this->_jsonData);
		}catch(Exception $e){
				  $this->_jsonData['status']="FAILURE";
				  $this->_jsonData['message']="Error Occured";
				  $this->_jsonData['data']=$data; 
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'signup',$_FILES);

	}
	public function croptest(){
		echo "test";
		$this->load->library('imagethumb');
		 $new_image = $this->imagethumb->image('./uploads/1370360935.jpg',100,100);
		echo "New Image:".$new_image;			
		
	}
	public function editPicture(){
		$user_id = $this->input->get_post('user_id');
		try{
			if($_FILES['file']['name'] == false || $_FILES['file']['name'] == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Image Missing";
			}else{
				  $imgName = time();
				  $imgPath = BASEPATH."../uploads/".$imgName;
				  $image = base_url().'uploads/'.$imgName;
				  $userData=$this->webservicesModel->getUserData($user_id);
				
				  if(move_uploaded_file($_FILES["file"]["tmp_name"],$imgPath.".jpg")){
					  $this->load->library('imagethumb');
					  
				//	 $userData['user_image'] = $this->imagethumb->image($imgPath.".jpg",100,100);
					 $new_image = $this->imagethumb->image('./uploads/'.$imgName.'.jpg',100,100);
					 
					  $userData['user_image']=$imgName."_thumb.jpg";
					  $data = array(
								'image'=>$imgName."_thumb.jpg",
								'image_path'=>$image."_thumb.jpg",
							);
					  $res = $this->webservicesModel->updateUser($userData);
					  $this->_jsonData['status']="SUCCESS";
					  $this->_jsonData['message']="Image Updated Successfully";
					  $this->_jsonData['data']=$data; 
				  }else{
					    $this->_jsonData['status']="FAILURE";
				 	 $this->_jsonData['message']="Image can not be Updated";
				  	$this->_jsonData['data']=''; 
				  }
				  
			}
				  echo json_encode($this->_jsonData);
		}catch(Exception $e){
				  $this->_jsonData['status']="FAILURE";
				  $this->_jsonData['message']="Error Occured";
				  $this->_jsonData['data']=$data; 
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'editPicture',$_FILES);

	}
	public function testing(){
		 $ffmpegpath = "/usr/local/bin/ffmpeg";
		  $videoImage='testing_thumb.png';
		  $imgPath=BASEPATH."../uploads/1375098307.mov";
		  $output1=BASEPATH.'../uploads/'.$videoImage;
		echo  $command="$ffmpegpath -i ".$imgPath." -an -ss 00:00:01 -f image2 -vframes 1 $output1";
		  exec($command,$output,$return);
		  var_dump($output);
		  var_dump($return);
	
	}
	public function uploadVideo(){
		$user_id = $this->input->get_post('user_id');
		try{
			if($_FILES['file']['name'] == false || $_FILES['file']['name'] == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Video Missing";
			}else{
				  $imgName = time();
				  $imgPath = BASEPATH."../uploads/".$imgName;
				  $image = base_url().'uploads/'.$imgName;
				
				
				  if(move_uploaded_file($_FILES["file"]["tmp_name"],$imgPath.".mov")){
					  //Generate Tumbnail
					 /* $ffmpegpath = "/usr/local/bin/ffmpeg";
					  $videoImage=$imgName;
					  $output1=BASEPATH.'../uploads/'.$videoImage.".png";
					  $command="$ffmpegpath -i ".$imgPath.".mov"." -an -ss 00:00:01 -f image2 -vframes 1 $output1";
					  exec($command,$output,$return);
					  
					  
					  
					  $this->load->library('imagethumb');
					  $new_image = $this->imagethumb->image('./uploads/'.$videoImage.".png",100,100);*/
					 
					  //$userData['user_image']=$new_image;
					  $data = array(
								'image'=>$imgName."_thumb.png",
								'image_path'=>$imgName.".png",
								'video'=>$imgName.".mov"
							);
					
					  $this->_jsonData['status']="SUCCESS";
					  $this->_jsonData['message']="Image Updated Successfully";
					  $this->_jsonData['data']=$data; 
					 
				  }else{
					    $this->_jsonData['status']="FAILURE";
				 	 $this->_jsonData['message']="Image can not be Updated";
				  	$this->_jsonData['data']=''; 
				  }
				  
			}
				  echo json_encode($this->_jsonData);
		}catch(Exception $e){
				  $this->_jsonData['status']="FAILURE";
				  $this->_jsonData['message']="Error Occured";
				  $this->_jsonData['data']=$data; 
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'editPicture',$_FILES);

	}
	public function signup(){
		$user_image = $this->input->get_post('user_image');
		$user_name = $this->input->get_post('name');
		$user_email = $this->input->get_post('email');
		$password = $this->input->get_post('password');
		$birthdaydate = $this->input->get_post('birthdate');
		$phone = $this->input->get_post('phone_number');
	try{
			if($user_name == false || $user_name == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Name Missing";
			}else if($user_email == false || $user_email == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Email Missing";
			}else if($password == false || $password == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Password Missing";
			}else if($birthdaydate == false || $birthdaydate == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Birthdaydate Missing";
			}else if($phone == false || $phone == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="phone Missing";
			}else if($user_email!=""){
					$checkUser = $this->webservicesModel->checkUser($user_email);
					if(count($checkUser)>0){
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="Email Already Exists";
					}else{
						$data = array(
								'user_name'=>$user_name,
								'user_birthdaydate'=>$birthdaydate,
								'user_email'=>$user_email,
								'password'=>base64_encode($password),
								'phone_number'=>$phone,
								'user_image'=>$user_image
							);
						$res = $this->webservicesModel->addUser($data);
						$imagePath = base_url().'uploads/'.$user_image;
						$data['user_image'] = $imagePath;
						$data['user_id'] = $res;
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="User Data Inserted Successfully";
						$this->_jsonData['data']=$data; 
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'signup',$_FILES);
		
	}
	public function updateProfile(){
		
		$user_id = $this->input->get_post('user_id');
		$user_name = $this->input->get_post('name');
		$birthdaydate = $this->input->get_post('birthdate');
		$phone = $this->input->get_post('phone_number');
	try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="user_id Missing";
			}else if($user_name == false || $user_name == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Name Missing";
			}else if($birthdaydate == false || $birthdaydate == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Birthdaydate Missing";
			}else if($phone == false || $phone == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="phone Missing";
			}else{
					$userData = $this->webservicesModel->getUserData($user_id);
					if(count($userData)>0){
						$userData['user_name'] = $user_name;
						$userData['user_birthdaydate'] = $birthdaydate;
						$userData['phone_number'] = $phone;
						$userImg=$userData['user_image'];
						unset($userData['user_image']);
						$res = $this->webservicesModel->updateUser($userData);
						$userData['user_image']=$userImg;
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="User Data Updated Successfully";
						$this->_jsonData['data']=$userData; 
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="user not exits Exists";	
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'updateProfile',$_FILES);
		
	}
	public function signin(){
		$user_email = $this->input->get_post('user_email');
		$password = $this->input->get_post('password');

	try{
			if($user_email == false || $user_email == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Email Missing";
			}else if($password == false || $password == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Password Missing";
			}else if($user_email!="" && $password!=""){
					$checkUser = $this->webservicesModel->checklogin($user_email,base64_encode($password));
					if($checkUser !=""){
					 $login = $this->webservicesModel->login($user_email,base64_encode($password));
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="User Logged In Successfully";
						$this->_jsonData['data']=$login; 
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="User Email or Password donot match";
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
	public function getUserData(){
		$user_id = $this->input->get_post('user_id');

	try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else{
					$data = $this->webservicesModel->getUserData($user_id);
					if(count($data)>0){
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="User Logged In Successfully";
						$this->_jsonData['data']=$data; 
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No User Found";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getUserData',$_FILES);
		
	}
	public function getUsersWhoBlockedMe(){
		$user_id = $this->input->get_post('user_id');

	try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="user_id Missing";
			}else{
					$data = $this->webservicesModel->getUsersWhoBlockedMe($user_id);
					if(count($data)>0){
						$this->_jsonData['status']="SUCCESS";
				
						$this->_jsonData['data']=$data; 
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No User Found";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'getUserData',$_FILES);
		
	}
	public function forgetpassword(){
		$user_email = $this->input->get_post('user_email');

	try{
			if($user_email == false || $user_email == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Email Missing";
			}else{
					$checkUser = $this->webservicesModel->checkUser($user_email);
					if(count($checkUser)>0){
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Password Sent Successfully...";
						$data['message'] = "Hey there, you've got mail!";
						ob_start(); //Turn on output buffering ?>
                       		Hey ! you have Got Mail from MissFire 
                        <?php
							echo "YOur Forgotten Password Is : ".base64_decode($checkUser['password']);
						 $var = ob_get_clean();
												
						//$htmlMessage =  $this->load->view('email/basic', $data,true);
						$this->load->library('email');
						$this->email->from('no-reply@missfire.com', 'Missfire');
						$this->email->to($user_email);
						$this->email->subject('Forget Password');
						$this->email->message($var);
						$this->email->send();
						$this->email->print_debugger(); 
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No Email Exists";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'forgetpassword',$_FILES);
		
	}
	public function sendInvitation(){
		$user_email = $this->input->get_post('user_email');
		$user_id = $this->input->get_post('user_id');

	try{
			if($user_email == false || $user_email == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Email Missing";
			}else{
					$checkUser = $this->webservicesModel->checkUser($user_email);
					if(count($checkUser)>0){
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="User is already connected"; 
					}else{
						
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Invitation Sent Successfully...";
					/*	$data['message'] = "Hey there, you've got mail!";
						ob_start(); //Turn on output buffering ?>
                       		Hey ! you have Got Mail from MissFire 
                        <?php
							echo "YOur Forgotten Password Is : ".base64_decode($checkUser['password']);
						 $var = ob_get_clean();
												
						$htmlMessage =  $this->load->view('email/basic', $data,true);
						$this->load->library('email');
						$this->email->from('no-reply@missfire.com', 'Missfire');
						$this->email->to($user_email);
						$this->email->subject('Forget Password');
						$this->email->message($var);
						$this->email->send();
						$this->email->print_debugger();*/
						
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'forgetpassword',$_FILES);
		
	}
	public function friends(){
		$user_id = $this->input->get_post('user_id');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($user_id!=""){
					$checkUser = $this->webservicesModel->checkUserId($user_id);
					if($checkUser !=""){
						$data = $this->webservicesModel->getFriends($user_id);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Friends list got successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No User Id Found";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'friends',$_FILES);

	}
	public function friendsChat(){
		$user_id = $this->input->get_post('user_id');
		$type 	 = $this->input->get_post('type');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($user_id!=""){
					$checkUser = $this->webservicesModel->checkUserId($user_id);
					if($checkUser !=""){
						$data = $this->webservicesModel->getFriends2($user_id,$type);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Friends list got successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No User Id Found";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'friends',$_FILES);

	}
	public function addFriend(){
		$user_id = $this->input->get_post('user_id');
		$friend_id = $this->input->get_post('friend_id');
		$date = $this->input->get_post('date');
		//$date = strtotime(date('Y-M-d H:i:s'));		
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($friend_id == false || $friend_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Friend Id Missing";
			}else if($user_id!=""){
				$checkUser = $this->webservicesModel->checkFriend($user_id,$friend_id);
				if($checkUser == 0){
						$data = array(
								'user_id'=>$user_id,
								'friend_id'=>$friend_id,
								'status'=>0,
								'datetime'=>$date
							);
						$res = $this->webservicesModel->addFriends($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Friend Added Successfully";
						$this->_jsonData['data']=$data; 
				}else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="You both are already Friends";
				}
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'addFriend',$_FILES);
	}
	public function deleteFriend(){	
		$user_id=$this->input->get_post('user_id');
		$friend_id=$this->input->get_post('friend_id');

		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";	
			}else if($friend_id == false || $friend_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Friend Id Missing";	
			}else if($user_id != 0 && $friend_id !=0  ){
				
					$data = $this->webservicesModel->deleteFriends($user_id,$friend_id);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Friend Deleted Successfully";
					$this->_jsonData['data']='';	
				
			}else{
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="No Delete Id Found";	
			}
				echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="An Error Occured";
				$this->_jsonData['data']='';	
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'deleteFriend',$_FILES);

 	}
	public function friendRequest(){
		$user_id = $this->input->get_post('user_id');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($user_id!=""){
					$checkUser = $this->webservicesModel->checkUserId($user_id);
					if($checkUser !=""){
						$data = $this->webservicesModel->friendRequest($user_id);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Friends Request list got successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No User Id Found";
					}
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'addFriendRequest',$_FILES);

	}
	public function acceptCancelFriendRequest($acceptOrCancel='accept'){
		$user_id = $this->input->get_post('user_id');
		$friend_id = $this->input->get_post('friend_id');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($friend_id == false || $friend_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else{
				$dt=$this->webservicesModel->getUserFriendRequest($user_id,$friend_id);
				
				if($acceptOrCancel=='accept'){
					$dt['status']=1;
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Friend Added Successfully";
					$this->webservicesModel->acceptFriendRequest($dt);
				}else{
					$this->webservicesModel->deleteFriendRequest($dt['id']);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="Friend Request Canceled Successfully";
				}
				$this->_jsonData['data']='';
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'addFriendRequest',$_FILES);

	}
	public function searchFriends(){
			$user_name = $this->input->get_post('user_name');
			$user_id = $this->input->get_post('user_id');
			try{
				if($user_name == false || $user_name == ""){
					$this->_jsonData['status'] = "FAILURE";
					$this->_jsonData['data'] = "User Name Missing";	
				}else if($user_id == false || $user_id == ""){
					$this->_jsonData['status'] = "FAILURE";
					$this->_jsonData['data'] = "User Id Missing";	
				}else if($user_name!="" ){
					$data = $this->webservicesModel->getSearchFriends($user_name,$user_id);
					if(count($data)>0){
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Data Found successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']="FAILURE";
						$this->_jsonData['message']="No Data Found";
					}
				}else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="No Data Match Found";
				}
			}catch(Exception $e){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Error Occured";
			}
				echo json_encode($this->_jsonData);
			$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'searchFriends',$_FILES);
			
		}
	public function messages(){
		$user_id = $this->input->get_post('user_id');
		$sender_id = $this->input->get_post('sender_id');
		$message = $this->input->get_post('message');
		$type = $this->input->get_post('type');
		$groupType = $this->input->get_post('groupType');
		$video = $this->input->get_post('video');
	
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($sender_id == false || $sender_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Sender Id Missing";
			}else if($message == false || $message =="" ){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Message Missing";
			}else if($type == false || $type =="" ){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="type Missing";
			}else{
				$type1='text';
				
					if($type=='image'){
						$type1=$type;
					}
					
					
					
							
						/*end*/
						if($groupType=='group'){
							
							if($type=='video'){
								$data = array(
								'sender_id'=>$user_id,
								'thread_id'=>$sender_id,
								'message'=>$message,
								'status'=>'read',
								'date_added'=>date('Y-m-d H:i:s'),'message_type'=>'video','video'=>$video
								);
							
								
							}else{
								$data = array(
								'sender_id'=>$user_id,
								'thread_id'=>$sender_id,
								'message'=>$message,
								'status'=>'read',
								'date_added'=>date('Y-m-d H:i:s'),'message_type'=>$type1
								);
							
							}
							$res = $this->webservicesModel->addMessage($data);
							$data['id']=$res;
							
							
							 //$this->webservicesModel->enableStatusForThreadRecipents($threadId);
							
						}else{
							$threadData = $this->webservicesModel->getThread($user_id,$sender_id);
							if($type=='video'){
								
								$data = array(
									'user_id'=>$sender_id,
									'sender_id'=>$user_id,
									'thread_id'=>$threadData['id'],
									'message'=>$message,
									'status'=>'read',
									'date_added'=>date('Y-m-d H:i:s'),'message_type'=>'video','video'=>$video
								);
							}else{
							
							
							$data = array(
								'user_id'=>$sender_id,
								'sender_id'=>$user_id,
								'thread_id'=>$threadData['id'],
								'message'=>$message,
								'status'=>'read',
								'date_added'=>date('Y-m-d H:i:s'),'message_type'=>$type1
							);
							}
							$res = $this->webservicesModel->addMessage($data);
							$data['id']=$res;
							
						}
						$this->_jsonData['data']=$data;
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Message Sent Successfully";
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'messages',$_FILES);
	}	
	public function messageView(){
		$user_id = $this->input->get_post('user_id');
		$sender_id = $this->input->get_post('friend_id');
		$page_no = $this->input->get_post('page_no');
		$groupType = $this->input->get_post('groupType');

		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($sender_id == false || $sender_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Sender Id Missing";
			}else{
				
					  $page=$this->input->get('page');
					   if($page==false){
							   $page=1;
					   }
					   
					   $limit=15;
					   $offSet=($page>1) ? ($page-1) * $limit : 0;
					   
					   $data1['show_more']=0;
	   					if($groupType!='group'){
						$threadData = $this->webservicesModel->getThread($user_id,$sender_id);
						if(count($threadData) == 0){
							$insert = array(
								'user1_id'=>$user_id,
								'user2_id'=>$sender_id
							);
							$threadId = $this->webservicesModel->insertThread($insert);
						}else{
							$threadId=$threadData['id'];
						}
						$block= $this->webservicesModel->getBlockUsers($sender_id,$user_id);
						$block=($block>0) ? 1 : 0;
						$this->_jsonData['block']=$block;
					
						}else{
							$threadId=$sender_id;

							/***************************
							Get Thread Recipents
							****************************/
							$recipents=$this->webservicesModel->getGroupRecipentIds($user_id,$threadId);
							//$recipents=explode(",",$recipents);
							$this->_jsonData['recipents']=$recipents;
					}
						
					
						$total = $this->webservicesModel->showMessagesTotal($threadId);

						$data = $this->webservicesModel->showMessages($threadId,$offSet,$limit);
						$show_more=0;
						if($total>($page*$limit)){
							$show_more=1;
						}
						
						
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Viewed Messages successfully";
						$this->_jsonData['data']=$data;
						$this->_jsonData['thread_id']=$threadId;
						$this->_jsonData['show_more']=$show_more;
						$this->_jsonData['page']=$page;
					
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'messageView',$_FILES);

	}
	public function createGroup(){
		$user_id = $this->input->get_post('user_id');
		$groupName = $this->input->get_post('groupName');	
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="user_id is Missing";
			}else if($groupName == false || $groupName ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="groupName is Missing";
			}else{
				$dt=array('title'=>$groupName,'user1_id'=>$user_id,'type'=>'group');
				$id=$this->webservicesModel->insertThread($dt);
				$dt['id']=$id;
				$recipentData=array('user_id'=>$user_id,'thread_id'=>$id,'status'=>1);
				$this->webservicesModel->insertThreadRecipent($recipentData);
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="";
				$this->_jsonData['data']=$dt;
				
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'thread_create',$_FILES);
		
	}
	public function addOrRemoveRecipent(){
		$user_id = $this->input->get_post('user_id');
		$groupId = $this->input->get_post('thread_id');	
		$type = $this->input->get_post('type');	
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="groupId is Missing";
			}else if($groupId == false || $groupId ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="groupId is Missing";
			}else{
				if($type=='add'){
					$dt=$this->webservicesModel->getRecipentData($user_id,$groupId);
					$data=array('user_id'=>$user_id,'thread_id'=>$groupId);
					if(is_array($dt) && count($dt)>0){
						$data['status']=1;
						$this->webservicesModel->updateThreadRecipent($data);
					}else{	
						$data['status']=1;
						$this->webservicesModel->insertThreadRecipent($data);
					}
				}else{
					$data=array('user_id'=>$user_id,'thread_id'=>$groupId,'status'=>0);
					$this->webservicesModel->updateThreadRecipent($data);
				
				}
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="";
				
				
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'thread_create',$_FILES);
		
	}
	
	public function getThreadRecipents(){
		$user_id = $this->input->get_post('user_id');
		$threadId = $this->input->get_post('thread_id');
	
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else{
				
				$data=$this->webservicesModel->getGroupRecipents($user_id,$threadId);	
				$this->_jsonData['data']=$data;
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="";
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'messages',$_FILES);
	}	
	
	// pagination
	public function pagings($total_record,$page_no,$per_page){
		$this->paging['page_no'] = $page_no; 
		if($total_record > $per_page){
			  $this->paging['page'] = ceil($total_record/$per_page);
			  $limit_start  = (($page_no-1)*$per_page);
			  $this->paging['limit']       = " limit $limit_start , $per_page";
		  }else{
			  $this->paging['page'] = 1;
			  $this->paging['limit'] 	   = " limit 0 , $per_page";
		  }
		  return $this->paging;
	}
	public function sendMessage(){
		$id = $this->input->get_post('id');
		$user_id = $this->input->get_post('user_id');
		$sender_id = $this->input->get_post('sender_id');
		$message = $this->input->get_post('message');
		$date_added = $this->input->get_post('date_added');;
	
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($sender_id == false || $sender_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Sender Id Missing";
			}else if($message == false || $message =="" ){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Messnage Missing";
			}else{
						$data = array(
								'user_id'=>$sender_id,
								'sender_id'=>$user_id,
								'message'=>$message,
								'status'=>'read',
								'date_added'=>$date_added
							);
						$res = $this->webservicesModel->sendMessage($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Message Sent Successfully";
						$this->_jsonData['data']=$data;
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'sendMessage',$_FILES);
	} 	
	public function receivedMessage(){
		$user_id = $this->input->get_post('user_id');
		$id = $this->input->get_post('id');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else{
				$data = $this->webservicesModel->receiveMessage($user_id,$id);
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="Viewed Messages successfully";
				$this->_jsonData['data']=$data;
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'receiveMessage',$_FILES);

	}	
	public function sentMessage(){
		$sender_id = $this->input->get_post('sender_id');
		$id = $this->input->get_post('id');
		try{
			if($sender_id == false || $sender_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Sender Id Missing";
			}else{
				$data = $this->webservicesModel->sentMessage($sender_id,$id);
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="Viewed Messages successfully";
				$this->_jsonData['data']=$data;
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'sentMessage',$_FILES);

	}
	public function deleteMessage(){	
		$delete_id=$this->input->get_post('id');
		try{
			if($delete_id == false || $delete_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Delete Id Missing";	
			}else{
					$data = $this->webservicesModel->deleteMessage($delete_id);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Message Deleted Successfully";
			}
				echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="An Error Occured";
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'deleteMessage',$_FILES);

 	}
	public function updateImageMessage(){	
		$delete_id=$this->input->get_post('id');
		//var_dump($delete_id);
		try{
			if($delete_id == false || $delete_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Delete Id Missing";	
			}else{
					$data = $this->webservicesModel->updateImageMessage($delete_id);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Message Deleted Successfully";
			}
				echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="An Error Occured";
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'deleteMessage',$_FILES);

 	}
	public function sendEmail(){
		$user_id = $this->input->get_post('sender_id');
		$receiver_id = $this->input->get_post('receiver_id');
		$message = $this->input->get_post('message');
		try{
			if($user_id == false || $user_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Sender Id Missing";
			}else if($receiver_id == false || $receiver_id == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Receiver Id Missing";
			}else if($message == false || $message == ""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Message Missing";
			}else{
				$data = $this->webservicesModel->getUserData($user_id);
				$user_name = $data['user_name'];
				$user_email = $data['user_email'];
				$receiver =$this->webservicesModel->getUserData($receiver_id);
				$receiver_email = $receiver['user_email'];
				$receiver_name = $receiver['user_name'];
				
				$this->_jsonData['status']="SUCCESS";
				$this->_jsonData['message']="Email Sent successfully";
				$this->_jsonData['data']='';
				
				$this->load->library('email');
				$this->email->from($user_email, $user_name);
				$this->email->to($receiver_email);
				$this->email->subject($user_name.' has sent you a email via Missfire');
				$this->email->message($message);
				$this->email->send();
				$this->email->print_debugger(); 
			}
		echo json_encode($this->_jsonData);
	}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'sendEmail',$_FILES);

		
	}	
	public function blockUser(){
		$user_id = $this->input->get_post('user_id');
		$blocked_by = $this->input->get_post('blocked_by');
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($blocked_by == false || $blocked_by ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Blocked By Id Missing";
			}else{
				$blockUsers = $this->webservicesModel->getBlockUsers($user_id,$blocked_by);
				if($blockUsers == 1){
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="User Already Blocked";
				}else{
					$data = array(
							'user_id'=>$user_id,
							'blocked_by'=>$blocked_by,
							'datetime'=>date('Y-M-d H:i:s')
						);
					$res = $this->webservicesModel->insertBlockUsers($data);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="User Blocked Successfully";
				}
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'blockUser',$_FILES);
	}
	public function deleteBlockUser(){
		$user_id = $this->input->get_post('user_id');
		$blocked_by = $this->input->get_post('blocked_by');
		try{
			if($user_id == false || $user_id ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="User Id Missing";
			}else if($blocked_by == false || $blocked_by ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="blocked by Id Missing";
			}else{
				$blockUsers = $this->webservicesModel->getBlockUsers($user_id,$blocked_by);
				if(count($blockUsers) >0){
					$this->webservicesModel->deleteBlockUsers($user_id,$blocked_by);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="User Unblocked Successfully";
				}else{
					$this->_jsonData['status']="FAILURE";
					$this->_jsonData['message']="Can not be Blocked";
				}
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'deleteBlockUser',$_FILES);
	}
	/*public function clearHistory(){
		$threadId = $this->input->get_post('thread_id');
		try{
			if($threadId == false || $threadId ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Thread Id Missing";
			}else{
				 	$this->webservicesModel->clearHistoryMessages($threadId);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="User History Cleared Successfully";
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'clearHistory',$_FILES);
	}	*/
	public function clearHistory(){
		$friendId = $this->input->get_post('friend_id');
		$userId = $this->input->get_post('user_id');
		$type = $this->input->get_post('groupType');
		//var_dump($friendId);
		//var_dump($userId);
		try{
			if($friendId == false || $friendId ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Thread Id Missing";
			}else{
				if($type=='group'){
					$threadData = $this->webservicesModel->getThreadData($friendId);
					if($threadData['user1_id']==$userId){
						$this->webservicesModel->deleteThread($threadData['id']);
					}else{
						$this->webservicesModel->deleteThreadRecipent($threadData['id'],$userId);
					}
					
				}else{
					//var_dump($user_id);
					//var_dump($sender_id);
					$threadData = $this->webservicesModel->getThread($userId,$friendId);
					//var_dump($threadData );
					$this->webservicesModel->deleteThread($threadData['id']);
				}
				 
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="User History Cleared Successfully";
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'clearHistory',$_FILES);
	}	
	public function deleteMessage1(){
		$threadId = $this->input->get_post('thread_id');
		try{
			if($threadId == false || $threadId ==""){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Thread Id Missing";
			}else{
				 	$this->webservicesModel->deleteMessage1($threadId);
					$this->_jsonData['status']="SUCCESS";
					$this->_jsonData['message']="User History Cleared Successfully";
			}
			echo json_encode($this->_jsonData);
		}catch(Exception $e){
				$this->_jsonData['status']="FAILURE";
				$this->_jsonData['message']="Error Occured";
				$this->_jsonData['data']='';
		}
		$this->ServicesModel->createService($_REQUEST,$this->_jsonData,$_SERVER['SERVER_ADDR'],'deleteMessage',$_FILES);
	}

} // controller ends


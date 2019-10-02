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

		/********************* openNotification code starts **************/
		
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
					if($data['title'] == 'Notification'){
						$data['user_notification_count']=$this->userModel->getBetNotifiCount($data['user_id']);			
						$data['notification_count']=$this->userModel->getNotificationsCount($data);
						$data['notification']=$this->userModel->getNotifications($data);
						$this->_jsonData['status']="SUCCESS";
						$this->_jsonData['message']="Got Notifications Successfully";
						$this->_jsonData['data']=$data;
					}else{
						$this->_jsonData['status']='FAILURE';
						$this->_jsonData['message']="No Notification Found";
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
} // controller ends


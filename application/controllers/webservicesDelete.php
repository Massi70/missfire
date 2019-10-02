<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class WebservicesDelete extends CI_Controller 
{



	public $_uId;

	public $_userData;

	public $_assignData;

	public $_checkUser;



	public function __construct()

	{

        parent::__construct();

		$this->load->model('userModel');
   }



	private $_headerData = array();

	private $_navData = array();

	private $_footerData = array();

	private $_jsonData = array();

	private $_finalData = array();



	/* sign in and singup code starts*/

	public function delete()
	{
		$this->_jsonData['response']="No";
		$this->_jsonData['message']="Can Not Delete";
		$this->_jsonData['finalData']=$this->_finalData;
		
		$bet_id = $this->input->get_post('bet_id',TRUE) ? $this->input->get_post('bet_id',TRUE) :  0;	
		if($bet_id!=0){
		$this->userModel->deleteBets($bet_id);
		$this->_jsonData['response']="Yes";
		$this->_jsonData['message']="Bet delete successfully";
		}else{
			
			}
		echo json_encode($this->_jsonData);		

	}
	
}


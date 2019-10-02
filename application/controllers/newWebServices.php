<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class newWebservices extends CI_Controller {

	public $_uId;
	public $_userData;
	public $_assignData;
	public $_checkUser;

	public function __construct()
	{
        parent::__construct();
		$this->load->model('userModel');
		$this->load->model('webservicesModel');
    }

	private $_headerData = array();
	private $_navData = array();
	private $_footerData = array();
	private $_jsonData = array();
	private $_finalData = array();

public function paymentPackages(){
        try{
            $this->load->model('UserModel');
            $paymentPackages=$this->UserModel->getPaymentPackages();
            if(is_array($paymentPackages) && count($paymentPackages)>0){
                $data['status']='SUCCESS';
                $data['message']='';   
                $data['data']=$paymentPackages;   
            }else{
                $data['status']='FAILURE';
                $data['message']='No Payment Packages found';
            }
        }catch(Exception $e){
            $data['status']='FAILURE';
            $data['message']='Error Occured';
        }
        $this->ServicesModel->createService($_REQUEST,$data,$_SERVER['SERVER_ADDR'],'paymentPackages',$_FILES);
        echo json_encode($data);
       
    }
}
?>
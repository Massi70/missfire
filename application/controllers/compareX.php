<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Compare extends CI_Controller {
	public $_uId;
	public $_userData;
	public $_assignData;
	public $_checkUser;
	
	public function __construct(){
		parent :: __construct();
		$this->load->model('userModel');
		$this->load->model('FacebookModel');
		$this->load->model('compareModel');
		
		$_userData=$this->FacebookModel->authenticate();
		$this->_checkUser=$_userData['check_user'];
		$this->_uId=$_userData['user_data']['user_id'];
		$this->_assignData=array('userData'=>$_userData['user_data']);
	}
	
	
	function compare_ranking()
	{
		$this->load->helper('pagination_helper');
		$pagination=Pagination_helper::getInstance();
		$data['user_id']=$this->_uId;
		$data['limit']='3';
		$ajax= isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '0';
		$data['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
		$data['page'] =($data['page']==false) ? 1 : $data['page']; 
		$data['offSet'] = ($data['page']>1) ? ($data['page']-1)* $data['limit'] : 0;
		$data['category_id'] = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : 0;
		$data['title'] = isset($_REQUEST['title']) ? $_REQUEST['title'] : 0;
		$data['rankVar'] = isset($_REQUEST['rank']) ? $_REQUEST['rankVar'] : '1';
		
		$data['category']=$this->userModel->getCategory();
		
		if($ajax==0){
			
			if($data['category_id']==0){
				$data['category_id'] = $data['category'][0]['category_id'];
				$data['category_name'] = $data['category'][0]['category_name'];
			}
		}
		
		
		$data1['category_id'] = $data['category_id'] ;
		$data['my_ranks']=$this->compareModel->getMyRanksByCatId($data1);
		
		$data['users_Count']=$this->compareModel->getUsersByCatIdCount($data);
		$data['users_ranks']=$this->compareModel->getUsersByCatId($data);
		
		
		$paging = $pagination->create($data['page'],base_url().'compare/compare_ranking/?category_id='.$data['category_id'] ,$data['users_Count'] ,'rankDiv' ,base_url().'images/ajax-loader.gif','paging_spinner',$data['limit']);
		
		$data['paging']=$paging['html'];
		
		
		
		//** get ranking with respect to category
		
		
		if($ajax==0){
			$this->load->view('ranking',$data);
		}else{
			$this->load->view('rank_page',$data);
		}
		
	}
	
	
}?>
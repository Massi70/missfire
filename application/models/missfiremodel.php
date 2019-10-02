<?php 
class MissfireModel extends CI_Model {

	private $table;

    public function __construct(){

        // Call the Model constructor

        parent::__construct();

		$this->load->library('memcached_library');

		

    }

	

    public function getUserData($uId,$refresh=0){

		$key=APP_NAME.'user-'.$uId;

		$data = $this->memcached_library->get($key);

	

		if($refresh==1){

			$data =false;

		}

		if($data==false){

			$this->db->select('user_id,user_name,user_email,user_image')->from('users')->where('user_id',$uId);

			$query= $this->db->get();

			$data=$query->row_array();

			$this->memcached_library->set($key,$data);

		}

		return $data;

	}

	

	public function createUser($data){

		if($this->db->insert('users', $data)){

			 $userId=$this->db->insert_id() ;

			 $this->getUserData($userId,1);

		 	 return  $userId;

		}else{

			return false;

		}

    }
}

?>
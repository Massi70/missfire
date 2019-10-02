<?php
class CompareModel extends CI_Model {
	private $table;
    public function __construct(){
        // Call the Model constructor
        parent::__construct();
		$this->load->library('memcached_library');
		
    }
	
  	
	
	public function getUsersByCatIdCount($data){
		
		  $sql= "SELECT  user_id FROM `user`, bet WHERE ((bet.acceptor_id=user.user_id OR  bet.creater_id=user.user_id) AND  bet.category_id ='".$data['category_id']."') GROUP BY user_id  
";

		$query=$this->db->query($sql);
		$data = $query->result_array();
		$data['userCount'] = count($data);
		return $data['userCount'];
	}
	
	
	public function getUsersByCatId($data1){
		
		$offSet =$data1['offSet'] ;
		$limit = $data1['limit'];
		$key=APP_NAME.'user_rank';
		$data = $this->memcached_library->get($key);
		if($data==false){
		
			 $sql="
				SELECT user_id,user_name,first_name,last_name, (SELECT
    SUM(b.xp_point) AS xp_point 
    FROM bet_vote_history  AS  b
    WHERE b.category_id ='".$data1['category_id']."'
    AND b.TYPE = 'better'
    AND b.user_id = user.user_id
  ) AS xp_point ,(SELECT
    SUM(b.coins) AS coins
    FROM bet_vote_history  AS  b
    WHERE b.category_id = '".$data1['category_id']."'
    AND b.TYPE = 'better'
    AND b.user_id = user.user_id
  ) AS coins ,
  (SELECT
    COUNT(bet_id) AS total_bet
    FROM bet 
    WHERE category_id = '".$data1['category_id']."'
      AND (acceptor_id = user.user_id
         OR creater_id = user.user_id)
  ) AS total_bet,
  (SELECT
    COUNT(bet_id) AS bet_win
    FROM bet 
    WHERE category_id = '".$data1['category_id']."'
      AND winner = user.user_id
         
  ) AS bet_win
FROM `user`,
  bet
WHERE ((bet.acceptor_id = user.user_id
         OR bet.creater_id = user.user_id)
       AND bet.category_id = '".$data1['category_id']."') GROUP BY user_id 
       ORDER BY xp_point DESC			 
			  limit $offSet, $limit ";
			 $query=$this->db->query($sql);
			$data=$query->result_array();
			$this->memcached_library->set($key,$data);
		}
		
		return $data;
	}
	
	
	public function getMyRanksByCatId($data1){
		
		$key=APP_NAME.'my_rank';
		$data = $this->memcached_library->get($key);
		if($data==false){
		
			 $sql="
				SELECT user_id,user_name,first_name,last_name, (SELECT
    SUM(b.xp_point) AS xp_point 
    FROM bet_vote_history  AS  b
    WHERE b.category_id ='".$data1['category_id']."'
    AND b.TYPE = 'better'
    AND b.user_id = user.user_id
  ) AS xp_point ,(SELECT
    SUM(b.coins) AS coins
    FROM bet_vote_history  AS  b
    WHERE b.category_id = '".$data1['category_id']."'
    AND b.TYPE = 'better'
    AND b.user_id = user.user_id
  ) AS coins ,
  (SELECT
    COUNT(bet_id) AS total_bet
    FROM bet 
    WHERE category_id = '".$data1['category_id']."'
      AND (acceptor_id = user.user_id
         OR creater_id = user.user_id)
  ) AS total_bet,
  (SELECT
    COUNT(bet_id) AS bet_win
    FROM bet 
    WHERE category_id = '".$data1['category_id']."'
      AND winner = user.user_id
         
  ) AS bet_win
FROM `user`,
  bet
WHERE ((bet.acceptor_id = user.user_id
         OR bet.creater_id = user.user_id)
       AND bet.category_id = '".$data1['category_id']."') GROUP BY user_id 
       ORDER BY xp_point DESC			 
			  ";
			 $query=$this->db->query($sql);
			$data=$query->result_array();
			$this->memcached_library->set($key,$data);
		}
		
		return $data;
	}
	
	
	
	
	


	
	
	
}
?>
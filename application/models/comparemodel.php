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
	
	
	
	public function getUsersByCatCount($data1){
		
			$query = $this->db->query("SET @rownum := 0");
			$query = $this->db->query("SET @rownum1 := 0");
			$sql="SELECT abcd.*,
(SELECT COUNT(bet_id) FROM bet b WHERE b.winner = abcd.user_id AND b.category_id = '".$data1['category_id']."')
AS total_wins,
(SELECT COUNT(bet_id) FROM bet b WHERE (b.creater_id = abcd.user_id OR b.acceptor_id = abcd.user_id) AND b.category_id ='".$data1['category_id']."')
AS total_bets,(SELECT first_name FROM `user` u WHERE u.user_id = abcd.user_id) AS first_name
,(SELECT last_name FROM `user` u WHERE u.user_id = abcd.user_id) AS last_name
 FROM (
SELECT *,@rownum := @rownum + 1 AS `level` FROM (
		SELECT *
		FROM (
		SELECT user_id,category_id,SUM(xp_point) AS xp_point
		, SUM(coins) AS total_coins
		
		 FROM bet_vote_history 
				 GROUP BY category_id,user_id ) dat 
		WHERE dat.category_id ='".$data1['category_id']."' 
		ORDER BY xp_point DESC
		
		) AS abc  
		) abcd 
	WHERE user_id ='".$data1['user_id']."'
UNION
SELECT abcd.*,
(SELECT COUNT(bet_id) FROM bet b WHERE b.winner = abcd.user_id AND b.category_id ='".$data1['category_id']."')
AS total_wins,
(SELECT COUNT(bet_id) FROM bet b WHERE (b.creater_id = abcd.user_id OR b.acceptor_id = abcd.user_id) AND b.category_id = '".$data1['category_id']."')
AS total_bets,(SELECT first_name FROM `user` u WHERE u.user_id = abcd.user_id) AS first_name
,(SELECT last_name FROM `user` u WHERE u.user_id = abcd.user_id) AS last_name
 FROM (
SELECT *,@rownum1 := @rownum1 + 1 AS `level` FROM (
		SELECT *
		FROM (
		SELECT user_id,category_id,SUM(xp_point) AS xp_point
		, SUM(coins) AS total_coins
		
		 FROM bet_vote_history 
				 GROUP BY category_id,user_id ) dat 
		WHERE dat.category_id ='".$data1['category_id']."' 
		ORDER BY xp_point DESC
		
		) AS abc  
		) abcd 
	WHERE user_id != '".$data1['user_id']."' AND user_id != '0' " ;
			// $query = $this->db->query($query);
			 //$query->row_array();
			
			$query = $this->db->query($sql);			 
			$data=$query->result_array();
			
		
		
		
		return $data;
	}
	
	
	public function getUsersByCatId($data1){
		
		$offSet =$data1['offSet'] ;
		$limit = $data1['limit'];
		$key=APP_NAME.'user_rank';
		$data = $this->memcached_library->get($key);
		if($data==false){
			$query = $this->db->query("SET @rownum := 0");
			$query = $this->db->query("SET @rownum1 := 0");
			$sql="SELECT abcd.*,
(SELECT COUNT(bet_id) FROM bet b WHERE b.winner = abcd.user_id AND b.category_id = '".$data1['category_id']."')
AS total_wins,
(SELECT COUNT(bet_id) FROM bet b WHERE (b.creater_id = abcd.user_id OR b.acceptor_id = abcd.user_id) AND b.category_id ='".$data1['category_id']."')
AS total_bets,(SELECT first_name FROM `user` u WHERE u.user_id = abcd.user_id) AS first_name
,(SELECT last_name FROM `user` u WHERE u.user_id = abcd.user_id) AS last_name
 FROM (
SELECT *,@rownum := @rownum + 1 AS `level` FROM (
		SELECT *
		FROM (
		SELECT user_id,category_id,SUM(xp_point) AS xp_point
		, SUM(coins) AS total_coins
		
		 FROM bet_vote_history 
				 GROUP BY category_id,user_id ) dat 
		WHERE dat.category_id ='".$data1['category_id']."' 
		ORDER BY xp_point DESC
		
		) AS abc  
		) abcd 
	WHERE user_id ='".$data1['user_id']."'
UNION
SELECT abcd.*,
(SELECT COUNT(bet_id) FROM bet b WHERE b.winner = abcd.user_id AND b.category_id ='".$data1['category_id']."')
AS total_wins,
(SELECT COUNT(bet_id) FROM bet b WHERE (b.creater_id = abcd.user_id OR b.acceptor_id = abcd.user_id) AND b.category_id = '".$data1['category_id']."')
AS total_bets,(SELECT first_name FROM `user` u WHERE u.user_id = abcd.user_id) AS first_name
,(SELECT last_name FROM `user` u WHERE u.user_id = abcd.user_id) AS last_name
 FROM (
SELECT *,@rownum1 := @rownum1 + 1 AS `level` FROM (
		SELECT *
		FROM (
		SELECT user_id,category_id,SUM(xp_point) AS xp_point
		, SUM(coins) AS total_coins
		
		 FROM bet_vote_history 
				 GROUP BY category_id,user_id ) dat 
		WHERE dat.category_id ='".$data1['category_id']."' 
		ORDER BY xp_point DESC
		
		) AS abc  
		) abcd 
	WHERE user_id != '".$data1['user_id']."' AND user_id != '0'   limit $offSet, $limit  " ;
			// $query = $this->db->query($query);
			 //$query->row_array();
			
			$query = $this->db->query($sql);			 
			$data=$query->result_array();
			$this->memcached_library->set($key,$data);
		}
		
		
		
		return $data;
	}
	
	
	
	
	
	
	


	
	
	
}
?>
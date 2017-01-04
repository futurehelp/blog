<?

class messages {
	var $db;
	var $msg_id;
	var $user1_id;
	var $user2_id;
	var $msg_state;
	var $msg_message;
	var $msg_timestamp;
	
	function send_message(&$db, $u1_id, $u2_id, $message){
		$this->db=$db;
		$msg_ts=date('y-m-d h-m-s');
		$result=$this->db->query("SELECT id FROM users WHERE username='".$u1_id."'");
		$row=$result->fetchRow();
		$u1_id=$row['id'];
		$result=$this->db->query("SELECT id FROM users WHERE username='".$u2_id."'");
		$row=$result->fetchRow();
		$u2_id=$row['id'];
		$result=$this->db->query("INSERT INTO messages (msg_id, user1_id, user2_id, msg_message, msg_timestamp) VALUES (NULL, '$u1_id', '$u2_id', '$message', '$msg_ts')");
		return 1;
	}

	function get_unread(&$db, $user_id){
		$this->db=$db;
		$result=$this->db->query("SELECT * FROM messages WHERE user2_id='".$user_id."' AND msg_state='0'");
		return $result->countRows();
	}

	function change_state(&$db, $msg_id){
		$this->db=$db;
		$result=$this->db->query("UPDATE messages SET msg_state=1 WHERE msg_id='".$msg_id."'");
	}

	function get_message(&$db, $message_id){
		$this->db=$db;
		$result=$this->db->query("SELECT * FROM users, messages WHERE users.id=messages.user1_id AND msg_id='".$message_id."'");
		$row=$result->fetchRow();  
		if($row){
			$user1_id=$row['user1_id'];
			$user2_id=$row['user2_id'];
			$user1=$row['username'];
		}
	}

}

?>

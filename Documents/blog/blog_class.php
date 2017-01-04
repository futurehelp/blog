<?php

//Main Blog Class
//Classes: Members, Member Profile, 
class members {
var $db;

function members_online(&$db){
$this->db=$db;
$result=$this->db->query("SELECT * FROM users WHERE online='1'");
while($row=$result->fetchRow()){
			echo "<a href='forum_posts.php?viewmember=".$row['username']."'>";
echo "<img src='images/".$row['profile_img']."' no border height=100 width=100></a>";
echo "<a href='forum_posts.php?viewmember=".$row['username']."'>";
echo $row['username']."</a>";
		}
}

}

class member_profile {
	var $db;
var $id;
	var $username;
	var $email;
	var $fName;
	var $lName;
	var $language;
	var $phone;
	var $profile_img;
	var $blog_posts;
	var $messages;
var $user_created;
var $last_signon;
	
	function member_profile(&$db){
		$this->db=$db;
		$result=$this->db->query("SELECT * FROM users WHERE username='".$_SESSION['user_logged_in']."'");
		$row=$result->fetchRow();
$this->id=$row['id'];
		$this->username=$row['username'];
		$this->email=$row['email'];
		$this->fName=$row['fName'];
		$this->lName=$row['lName'];
		$this->language=$row['language'];
		$this->phone=$row['phone'];
$this->blog_posts=$row['blog_posts'];
$this->messages=$row['messages'];
$this->user_created=$row['user_created'];
$this->last_signon=$row['last_signon'];
		$this->profile_img=$row['profile_img'];
	}
	function update_profile($email, $fName, $lName, $language, $phone){
		$result=$this->db->query("UPDATE users SET email='".$email."', fName='".$fName."', lName='".$lName."
', language='".$language."', phone='".$phone."' WHERE username='".$this->username."'");
		$this->email=$email;
		$this->fName=$fName;
		$this->lName=$lName;
		$this->language=$language;
		$this->phone=$phone;
	return $result;
	}
	
	function upload_picture($filename){
		$i = strrpos($filename,".");
         	if (!$i) { return ""; }
         	$l = strlen($filename) - $i;
         	$extension = substr($filename,$i+1,$l);
 		$extension = strtolower($extension);
 		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension !="mov") && ($extension != "mp3") )
 		{
 			//echo '<h1>Unknown extension!</h1>';
 			$errors=1;
 		}
 		else
 		{
			$day=date('d');
			$month=date('m');
			$year=date('y');
			$hour=date('H');
			$min=date('i');
			$sec=date('s');

			$imagename=$month.$day.$year.$hour.$min.$sec.".".$extension;
			$newname="images/".$imagename;


			$copied = copy($_FILES['image']['tmp_name'], $newname);
			if (!$copied) 
			{
				//echo '<h1>Copy unsuccessfull!</h1>';
				$errors=1;
			}
		}


	if(!$errors){ return $imagename; }
else{ return 0; }
}

function update_profile_img($imagename){


		$result=$this->db->query("UPDATE users SET profile_img='".$imagename."' WHERE username='".$this->username."'");
 		$this->profile_img=$imagename;

	}

function update_blog_img($imagename){

echo "imagename: ".$imagename;

		$result=$this->db->query("UPDATE blog, users SET blog_picture='".$imagename."' WHERE users.id=blog.user_id AND username='".$this->username."'");
 		$this->profile_img=$imagename;

	}

function blog_post($title,$message,$blog_picture,$blog_location){
$date=date('Y-m-d h-m-s');
$result=$this->db->query("SELECT * FROM blog WHERE blog_title='".$title."' AND blog_message='".$message."'");
if($result->countRows()==0){
$result=$this->db->query("INSERT INTO blog (blog_id, user_id, blog_title, blog_message, root_id, blog_timestamp, blog_picture, blog_location) VALUES (NULL, '$this->id', '$title','$message', '0', '$date', '$blog_picture', '$blog_location')");
}
}

function blog_feed(){
$result=$this->db->query("SELECT * FROM blog WHERE user_id='".$this->id."'");
$this->blog_posts=array();
while($row=$result->fetchRow()){
$this->blog_posts[]=$row['blog_id'];
}
}
function blog_count($user_id){
$result=$this->db->query("SELECT * FROM blog WHERE user_id='".$user_id."' AND root_id='0'");
return $result->countRows();
}

function tagged_count($username){
$result=$this->db->query("SELECT * FROM blog WHERE blog_message LIKE '%@".$username." %'");
if($result->countRows()>1) return $result->countRows();
else return 0;
}

function send_notification($type, $username, $user_email, $recipient, $handle)
{
$subject="Trill Blog Notification";
if($type==1){
//send message notification
$message="Message from ".$username;
$message.="\nClick the link to view your inbox.\n http://www.trillblog.com/blog/messages.php";
}else if($type==2){
$message=$handle." you were tagged in a post!";
$message.="\nClick the link to view your posts.\n http://www.trillblog.com/blog/posts.php";
}

ini_set('sendmail_from', 'me@domain.com');
$header = "From: ". $username . " <" . $user_email . ">\r\n";

if(mail($recipient, $subject, $message)){
 } else {
//echo "error"; 
}

}




function get_posts(){
	$result=$this->db->query("SELECT * FROM users WHERE name='".$this->username."'");
	$this->topic_posts=array();
	$this->message_posts=array();
	while($row=$result->fetchRow()){
		if($row['parent_id']==0){ $this->topic_posts[]=$row['id']; }
		else { $this->message_posts[]=$row['id']; }
	}
}
	
}
	


?>
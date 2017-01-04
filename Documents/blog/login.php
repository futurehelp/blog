<?php

session_start();
$errorMessage = '';
if (isset($_POST['username']) && isset($_POST['password'])) {
require('sql_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));
$username=strtolower($_POST['username']);
$result=$db->query("SELECT * FROM users WHERE username = '".$username."'");
if($result){
$row = $result->fetchRow();
$username= $row['username'];
$password=$row['password'];

}
$post_pass=md5($_POST['password']);

if ($username === $username && $post_pass === $password) {

$_SESSION['basic_is_logged_in'] = true;
$_SESSION['user_logged_in']=$username;
$last_signon=date('Y-m-d h-m-s');
$result=$db->query("UPDATE users SET online='1', last_signon='".$last_signon."' WHERE username='".$username."' AND password='".$password."'");

header('Location: member.php');
exit;
} else {
echo '<h1>Sorry, wrong user id / password</h1>';
}
}
?>
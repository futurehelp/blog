<?php 

require('sql_class.php');
require('registration_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$tmp = new registration() ; 

$email=$_POST['email'];
$resetcode=$_POST['resetcode'];
$password=md5($_POST['password']);
$tmp->change_password($db, $email, $resetcode, $password) ; 

?>
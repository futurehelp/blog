<?php
session_start();

if (!isset($_SESSION['basic_is_logged_in']) 
    || $_SESSION['basic_is_logged_in'] !== true) {

    header('Location: login.html');
    exit;
}

require('sql_class.php');
require('blog_class.php');
require('messages_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$member=new member_profile($db);
$messages=new messages($db);

$msg_user=$_POST['msg_user'];
$msg_message=$_POST['msg_message'];
$msg_email=$db->query("SELECT email FROM users WHERE username='".$msg_user."'");
$row=$msg_email->fetchRow();
$email=$row['email'];

$result=$messages->send_message($db, $member->username, $msg_user, $msg_message);

$notification=$member->send_notification(1, $member->username, $member->email, $email, '');

header('Location: messages.php');

?>

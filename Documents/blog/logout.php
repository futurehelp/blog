<?php
session_start();
    
require("sql_class.php");
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$result=$db->query("UPDATE users SET online=0 WHERE username='".$_SESSION['user_logged_in']."'");

session_destroy();
header("Location: index.php");

?>
<?php 
require('sql_class.php');
require('registration_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$tmp = new registration() ; 
$tmp->start_registration($db) ; 

?>
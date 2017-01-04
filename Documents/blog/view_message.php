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
$msg_id=$_GET['msg'];


if(isset($_POST['upload']))
{
$image=$_FILES['image']['name'];

	$filename = stripslashes($_FILES['image']['name']);
	$result=$member->upload_picture($filename);
}




?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
<link href="style/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="scripts/custom.js"></script>

		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Trill Blog</title>
		<link rel="stylesheet" href="css/960.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="css/template.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="css/colour.css" type="text/css" media="screen" charset="utf-8" />
		<!--[if IE]><![if gte IE 6]><![endif]-->
		<script src="js/glow/1.7.0/core/core.js" type="text/javascript"></script>
		<script src="js/glow/1.7.0/widgets/widgets.js" type="text/javascript"></script>
		<link href="js/glow/1.7.0/widgets/widgets.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript">
			glow.ready(function(){
				new glow.widgets.Sortable(
					'#content .grid_5, #content .grid_6',
					{
						draggableOptions : {
							handle : 'h2'
						}
					}
				);
			});
		</script>
		<!--[if IE]><![endif]><![endif]-->
	</head>
	<body>

		<h1 id="head">Trill Blog</h1>
		
		<ul id="navigation">
			<li><a href="member.php">Profile</a></li>
			<li><a href="posts.php">Posts</a></li>
			<li><span class="active">Messages</span></li>
<li><a href="logout.php">Log out</a></li>
			<li><a href="#">Search <input type="text" name="keyword" value=""></a></li>
		</ul>

			<div id="content" class="container_16 clearfix">
				<div class="container_16">
					<div class="box">
						<h2><? echo $member->username; ?></h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<p><strong>Last Signed In : </strong><? echo $member->last_signon; ?> <br />
<strong>User since : </strong> <? echo $member->user_created; ?></p>
					</div>
					
					<div class="box">
						<h2>Inbox</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
<table>
<tbody>
<tr>
<td></td>
<td>From</td>
<td>Message</td>
<td>Date</td>
</tr>

						<?php
$user_id=$member->id;
$result=$db->query("SELECT * FROM messages, users WHERE messages.user1_id=users.id AND msg_id='".$msg_id."' AND user2_id='".$user_id."'");
$rows=$result->countRows();
if($rows>0){
$row=$result->fetchRow();
$user1_id=$row['username'];
$msg_message=$row['msg_message'];
echo "<tr><td><input type='checkbox' name='".$row['msg_id']."'></td>";
echo "<td><a href='member_profile.php?user=".$row['username']."'>".$row['username']."</a></td>";
echo "<td><a href='view_message.php?msg=".$row['msg_id']."'>".$row['msg_message']."</a></td>";
echo "<td>".$row['msg_timestamp']."</td></tr>";
$result=$messages->change_state($db, $msg_id);
}
?>



</tbody>
</table>
					</div>
					
				</div>
<? if($rows>0){ ?>
<div class="box">
						<h2>Reply to User</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
						<table>
<tr>
<td>
<p>
								<label for="msg_user">Message<small></small> </label>
</p>
<p>
<? echo $msg_message; ?>
</p>
</td>
<td><form action="send_message.php" method="post">
						<td>	<p>
								<label for="msg_user">User<small>Alpha-numeric characters only.</small> </label>
								<input type="text" name="msg_user" value="<? echo $user1_id; ?>" readonly="readonly" />
							</p></td>
							<td><p>
								<label for="msg_message">Message <small>Parsed by Markdown.</small> </label>
								<textarea name="msg_message"></textarea>
							</p>
							<p>
								<input type="submit" value="post" />
							</p>
</td>
</tr>
</table>
						</form>
					</div>
<? } ?>
				
				</div>
			</div>
		<div id="foot">
			
		</div>
	</body>
</html>



				
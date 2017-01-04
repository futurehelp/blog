<?php
// like i said, we must never forget to start the session
session_start();

// is the one accessing this page logged in or not?
if (!isset($_SESSION['basic_is_logged_in']) 
    || $_SESSION['basic_is_logged_in'] !== true) {

    // not logged in, move to login page
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

if(isset($_POST['blog']))
{

$result=$member->blog_post($_POST['blog_title'],$_POST['blog_message'],"","");
header('Location: post_result.php');
}

if(isset($_POST['upload']))
{
$image=$_FILES['image']['name'];

//if ($image) 

	$filename = stripslashes($_FILES['image']['name']);
	$imagename=$member->upload_picture($filename);
$result=$member->update_profile_img($imagename);
	//echo $result;
}


if(isset($_POST['update']))
{
	$result=$member->update_profile($_POST['email'], $_POST['fName'], $_POST['lName'],$_POST['language'], $_POST['phone'], $_POST['b_type']);
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Trill Blog</title>

<link rel="icon" href="favicon.ico" type="image/x-icon">
	<!-- Load CSS -->
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<!-- Load Fonts -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold" type="text/css" />
	<!-- Load jQuery library -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<!-- Load custom js -->
	<script type="text/javascript" src="scripts/custom.js"></script>

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
			<li><span class="active">Profile</span></li>
<li><a href="feed.php">Feeds</a></li>
			<li><a href="posts.php">Posts</a></li>
			<li><a href="messages.php">Messages</a></li>
<li><a href="logout.php">Log out</a></li>
<li><form method="post" action="search_blog.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form></li>
</ul>


			<div id="content" class="container_16 clearfix">
				<div class="grid_5">
					<div class="box">
						<h2><? echo $member->username; ?></h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<p><strong>Last Signed In : </strong><? echo $member->last_signon; ?> <br />
<strong>User since : </strong> <? echo $member->user_created; ?></p>
					</div>
					
					<div class="box">
						<h2>Messages</h2>
						<div class="utils">
							<a href="#">Inbox</a>
						</div>
						<p class="center">Have <a href="messages.php">
<?php
echo $messages->get_unread($db,$member->id); ;
?>
</a> unread messages.</p>
					</div>
					<div class="box">
						<h2>Message User</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
						<form action="send_message.php" method="post">
							<p>
								<label for="msg_user">User<small>Alpha-numeric characters only.</small> </label>
								
<input type="text" id="search" name="msg_user" autocomplete="off">

		<!-- Show Results -->
		
		<ul id="results"></ul>
							</p>
							<p>
								<label for="msg_message">Message <small>Parsed by Markdown.</small> </label>
								<textarea name="msg_message"></textarea>
							</p>
							<p>
								<input type="submit" value="post" />
							</p>
						</form>
					</div>
				</div>
				<div class="grid_6">
					
					<div class="box">
						<h2>Quick Post</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
						<form action="member.php" method="post">
							<p>
								<label for="title">Title <small>Alpha-numeric characters only.</small> </label>
								<input type="text" name="blog_title" />
							</p>
							<p>
								<label for="post">Post <small>Parsed by Markdown.</small> </label>
								<textarea name="blog_message"></textarea>
							</p>
							<p>
								<input type="submit" name="blog" value="post" />
							</p>
						</form>
					</div>
<div class="box">
<h2>Profile</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<table>
							<tbody>
								<tr>
<form method="POST" action="member.php">
									<td><input type="textarea" style="width: 100px" name="fName" length="5" width="5" value="<? echo $member->fName; ?>"></td><tr>
<tr>
<td><input type="text" style="width: 100px" name="lName" value="<? echo $member->lName; ?>"></td></tr>
									
								</tr>
								
								<tr>
									<td><input type="text" name="email" value="<? echo $member->email; ?>"></td>
								</tr>
								<tr>
									<td><input type="text" name="phone" value="<? echo $member->phone; ?>"></td>
</tr>
<tr>
<td><input type="submit" name="update" value="Update Profile"></td>
</form>
								</tr>
<tr>
<form action="member.php" method="POST" enctype="multipart/form-data">
<td><input type="text" style="width: 300px" class="post" name="new_profile_img" size="30" value=<? echo $member->profile_img; ?> />
<input type="hidden" name="profile_img" value="<? echo $member->profile_img; ?>"></td></tr>
<tr><td><input type="file" name="image"><input type="submit" name="upload" value="Upload New Photo"></form></td> </tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="grid_5">

					<div class="box">
						<h2>Friends</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<table>
							<tbody>
								
<?
$result=$db->query("SELECT * FROM friends, users WHERE users.id=friends.friend_id AND friends.user_id='".$member->id."'");
while($row=$result->fetchRow()){
?>
<tr>
<td><img src="images/<? echo $row['profile_img']; ?>" width=30 height=30></td>
<td><a href="member_profile.php?user=<? echo $row['username']; ?>"><? echo $row['username']; ?></a></td>
<td><? 
if($row['online']==1)echo "online";
else echo "offline"; ?>
</td>
</tr>
<?
}
?>
	
							</tbody>
						</table>
<form method="post" action="search.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form>
					</div>
					
			</div>
		
		</div>

<div id="foot">
			<div class="container_16 clearfix">
				<div class="grid_16">
					© 2013 Trill Blog
				</div>
			</div>
		</div>
	</body>
</html>
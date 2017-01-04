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

$username=$_GET['user'];

//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$result=$db->query("SELECT * FROM users WHERE username='".$username."'");
$row=$result->fetchRow();
$friend_id=$row['id'];
$emailAddress=$row['email'];
$phoneNumber=$row['phone'];
$user_created=$row['user_created'];
$firstName=$row['fName'];
$lastName=$row['lName'];
$last_signon=$row['last_signon'];
$profile_img=$row['profile_img'];

$member=new member_profile($db);
$user_id=$member->id;

if(isset($_POST['blog']))
{
$result=$member->blog_post($_POST['blog_title'],$_POST['handle']." ".$_POST['blog_message'],"","");
$notification=$member->send_notification(2,$member->username, $member->email, $email, $_POST['handle']);
header('Location: post_result.php');
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
<li><a href="feed.php">Feeds</a></li>
			<li><a href="posts.php">New Post</a></li>
			<li><a href="messages.php">Messages</a></li>
<li><a href="logout.php">Log out</a></li>
			<li><a href="#">Search <input type="text" name="keyword" value=""></a></li>
		</ul>

			<div id="content" class="container_16 clearfix">
				<div class="grid_5">
					<div class="box">
						<h2><? echo $username; ?></h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<img src="images/<? echo $profile_img; ?>" width=250 height=250>
					</div>
					<div class="box">
						<h2><? echo $username; ?></h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<table>
							<tbody>
								<tr>
									<td><? echo $firstName; ?></td>
									<td><? echo $lastName; ?></td>
								</tr>

<p><strong>Last Signed In : </strong><? echo $last_signon; ?> <br />
<strong>User since : </strong> <? echo $user_created; ?></p>
							</tbody>
						</table>
					</div>
					
				</div>



				<div class="grid_6">
					
<div class="box">
						<h2>Posts</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<table>
							<tbody>
								<tr>
									<td><a href="posts.php?user_id=<? echo $friend_id; ?>">Created Posts</a></td>
									<td>
<a href="posts.php?user_id=<? echo $friend_id; ?>"><? echo $member->blog_count($friend_id); ?></a>


</td>
								</tr>
<tr>
									<td><a href="posts.php?user_id=<? echo $friend_id; ?>">Tagged Posts</a></td>
									<td>
<a href="posts.php?user_id=<? echo $friend_id; ?>"><? echo $member->tagged_count($username); ?></a>
</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					<div class="box">
						<h2>Message User</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
						<form action="send_message.php" method="post">
							<p>
								<label for="msg_user">User<small>Alpha-numeric characters only.</small> </label>
								

<input type="text" name="msg_user" value="<? echo $username; ?>" readonly="readonly" />
							</p>
							<p>
								<label for="msg_message">Message <small>Parsed by Markdown.</small> </label>
								<textarea name="msg_message"></textarea>
							</p>
							<p>
								<input type="submit" value="Message" />
							</p>
						</form>
					</div>
				</div>
				<div class="grid_5">
<?

$result=$db->query("SELECT * FROM friends WHERE user_id='".$user_id."' AND friend_id='".$friend_id."'");
$rows=$result->countRows();
if(($user_id != $friend_id) && ($rows<1)){
?>
<div class="box">

						<h2>Add Friend</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<form method="post" action="add_friend.php">
<input type="hidden" name="friend_id" value="<? echo $friend_id; ?>">
<input type="submit" name="friend" value="Add Friend">
</form>
					</div>
<? } ?>
<div class="box">
						<h2>Quick Post</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
						<form action="member_profile.php?user=<? echo $username; ?>" method="post">
							<p>
								<label for="title">Title <small>Alpha-numeric characters only.</small> </label>
								<input type="text" name="blog_title" />
							</p>
							<p>
								<label for="post">Post <small>Parsed by Markdown.</small> </label><input type="hidden" name="handle" value="<? echo "@".$username; ?>">
								<textarea name="blog_message"></textarea>
							</p>
							<p>
								<input type="submit" name="blog" value="Post" />
							</p>
						</form>
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
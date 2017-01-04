<?php
session_start();

if (!isset($_SESSION['basic_is_logged_in']) 
    || $_SESSION['basic_is_logged_in'] !== true) {

    header('Location: login.html');
    exit;
}
require('sql_class.php');
require('blog_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$user_id=$_GET['user_id'];
$member=new member_profile($db);
$blog_feed=array();

if(isset($_POST['blog']))
{
	$image=$_FILES['image']['name'];

	if($image !=""){
		$filename = stripslashes($_FILES['image']['name']);
		$imagename=$member->upload_picture($filename);
		echo $result;
	}

$result=$member->blog_post($_POST['blog_title'],$_POST['blog_message'],$imagename,$location);

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
			<li><span class="active">Posts</li>
			<li><a href="messages.php">Messages</a></li>
<li><a href="logout.php">Log out</a></li>
			<li><form method="post" action="search.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form></a></li></ul>

			<div id="content" class="container_16 clearfix">
				
				<div class="container_16">
<div class="box">
						<h2>Post</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
<table>
<tr>
<td>
						<form action="add_comment.php" method="post" enctype="multipart/form-data">
							<p>
								<label for="title">Title <small>Alpha-numeric characters only.</small> </label>
								<input type="text" name="blog_title" />
							</p></td>
<td>							<p>
								<label for="post">Post <small>Parsed by Markdown.</small> </label>
								<textarea name="blog_message" rows="4" cols="40"></textarea>
							</p>
</td>
<td>
<p>
<label for="post">Media <small>Parsed by Markdown.</small> </label>
<input type="hidden" name="user_id" value="<? echo $user_id; ?>">
<input type="file" name="image">
</p>
							<p>
								<input type="submit" name="blog" value="post" />
							</p>


						</form>
</td></tr></table>
					</div>	
					<div class="box">
						<h2>Blog Posts</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
<table>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<form method="POST" action="delete_post.php">
<?
if(isset($_GET['user_id'])) $user_id=$_GET['user_id'];
else $user_id=$member->id;

$result=$db->query("SELECT * FROM blog, users WHERE users.id=blog.user_id AND user_id='".$user_id."' AND root_id='0' ORDER BY blog_timestamp DESC");
while($row=$result->fetchRow()){
?>
	<tr>
	<td align=vleft><input type="checkbox" name="blog_id" value="<? echo $row['blog_id']; ?>"></td>
	<td align=vleft><img src="images/<? echo $row['profile_img']; ?>" width=20 height=20></td>
	<td align=vleft><a href="view_post.php?post_id=<?echo $row['blog_id'];?>"><? echo $row['blog_title']; ?></a></td>
	<td align=vleft><? echo $row['blog_message']; ?></td>
<?
$result2=$db->query("SELECT * FROM blog WHERE root_id='".$row['blog_id']."'");
$row2=$result2->fetchRow();
$rowCount=$result2->countRows();
if($rowCount<1) $rowCount=0;
?>
	<td align=vleft>Replies: <? echo $rowCount; ?></td>
	<td align=vleft><? echo $row['blog_timestamp']; ?></td>
	</tr>
<?
}
?>
<tr><td align=vleft><input type="submit" value="Delete"></td></tr>
</table></form>
 
					</div>
<?
if(!isset($_GET['user_id'])){
?>
<div class="box">
						<h2>Tagged Post</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
<table>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?


$result=$db->query("SELECT * FROM users, blog WHERE blog.user_id=users.id AND blog.blog_message LIKE '%@".$member->username." %' ORDER BY blog_timestamp DESC");
while($row=$result->fetchRow()){
?>
	<tr>
	<td align=vleft><img src="images/<? echo $row['profile_img']; ?>" width=20 height=20></td>
	<td align=vleft><a href="member_profile.php?user=<?echo $row['username'];?>"><? echo $row['username']; ?></a></td>
	<td align=vleft><a href="view_post.php?post_id=<?echo $row['blog_id'];?>"><? echo $row['blog_title']; ?></a></td>
	<td align=vleft><? echo $row['blog_message']; ?></td>
	<td align=vleft>Replies: <? echo $rowCount; ?></td>
	<td align=vleft><? echo $row['blog_timestamp']; ?></td>
</tr>
<?
}
?>
</table>
</div>
<? } ?>
				


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
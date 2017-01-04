<?php

session_start();

if (!isset($_SESSION['basic_is_logged_in']) 
    || $_SESSION['basic_is_logged_in'] !== true) {

    // not logged in, move to login page
    header('Location: login.html');
    exit;
}
require('sql_class.php');
require('blog_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));


$member=new member_profile($db);
$blog_id=$_GET['post_id'];
$user_id=$member->id;

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
			<li><form method="post" action="search_blog.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form></li>
		</ul>

			<div id="content" class="container_16 clearfix">
				
				<div class="container_16">
					<div class="box">
						<h2>Blog Posts</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>

<table>
<?

$result=$db->query("SELECT * FROM users, blog WHERE users.id=blog.user_id AND blog_id='".$blog_id."'");
$row=$result->fetchRow();
$blog_title=$row['blog_title'];
?>
<tr>
<td align=center><a href="member_profile.php?user=<? echo $row['username']; ?>"><img src="images/<? echo $row['profile_img']; ?>" width=20 height=20 noborder></a></td>
<td align=center><a href="member_profile.php?user=<? echo $row['username']; ?>"><? echo $row['username']; ?></a></td>
<td align=center><? echo $row['blog_timestamp']; ?></td>
</tr>
</table>
<table>
<tr>
<td align=center><a href="view_post.php?post_id=<?echo $row['blog_id'];?>"><? echo $blog_title; ?></a></td>
</table>
<table align=center>
<tbody>
<td align=center><? echo $row['blog_message']; ?></td>
</tbody>
</table>
<table align=center>
<tbody>
<?
if($row['blog_picture']!=""){ 
//check extension
$filename=$row['blog_picture'];
$i = strrpos($filename,".");
         	if (!$i) { return ""; }
         	$l = strlen($filename) - $i;
         	$extension = substr($filename,$i+1,$l);
 		$extension = strtolower($extension);
if(($extension == "jpg") || ($extension == "jpeg") || ($extension == "png") || ($extension == "gif")){
?>
<td align=center><a href="images/<? echo $row['blog_picture']; ?>" noborder><img src="images/<? echo $row['blog_picture']; ?>" width=100 height=100></a></td>
<? } 
elseif($extension=="mov"){
?>
<td align=center>
<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" WIDTH="160"HEIGHT="144" CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab">
<PARAM name="SRC" VALUE="sample.mov">
<PARAM name="AUTOPLAY" VALUE="true">
<PARAM name="CONTROLLER" VALUE="false">
<EMBED SRC="images/<? echo $row['blog_picture']; ?>" WIDTH="400" HEIGHT="225" AUTOPLAY="true" CONTROLLER="true" PLUGINSPAGE="http://www.apple.com/quicktime/download/">
</EMBED>
</OBJECT>
</td>
<?
}
elseif($extension=="mp3"){
?>
<td align=center>
<audio controls>
  <source src="images/<? echo $row['blog_picture']; ?>" type="audio/mpeg">
</audio>
</td>
<?
}
}
?>
</tbody>
</table>
</div>

<div class="box">
						<h2>Blog Comments</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
<table>
<?
$result=$db->query("SELECT * FROM users, blog WHERE users.id=blog.user_id AND root_id='".$blog_id."'");
while($row=$result->fetchRow()){
?>
<tr>
<td align=vleft><a href="member_profile.php?user=<? echo $row['username']; ?>"><img src="images/<? echo $row['profile_img']; ?>" width=20 height=20 noborder></a></td>
<td align=center><a href="member_profile.php?user=<? echo $row['username']; ?>"><? echo $row['username']; ?></a></td>

<td align=center><? echo $row['blog_timestamp']; ?></td>
</tr></table>
<table>
<tr>
<td align=center><a href="view_post.php?post_id=<?echo $row['blog_id'];?>"><? echo $row['blog_title']; ?></a></td>
</table>
<table>
<tbody>
<td align=vleft><? echo $row['blog_message']; ?></td>
<?
if($row['blog_picture']!=""){ 
//check extension
$filename=$row['blog_picture'];
$i = strrpos($filename,".");
         	if (!$i) { return ""; }
         	$l = strlen($filename) - $i;
         	$extension = substr($filename,$i+1,$l);
 		$extension = strtolower($extension);

if(($extension == "jpeg") && ($extension == "png") && ($extension == "gif")){
?>
<td><a href="images/<? echo $row['blog_picture']; ?>" noborder><img src="images/<? echo $row['blog_picture']; ?>" width=100 height=100></a></td>
<? } 
elseif($extension=="mov"){
?>
<td align=center>
<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" WIDTH="160"HEIGHT="144" CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab">
<PARAM name="SRC" VALUE="sample.mov">
<PARAM name="AUTOPLAY" VALUE="true">
<PARAM name="CONTROLLER" VALUE="false">
<EMBED SRC="images/<? echo $row['blog_picture']; ?>" WIDTH="400" HEIGHT="225" AUTOPLAY="true" CONTROLLER="true" PLUGINSPAGE="http://www.apple.com/quicktime/download/">
</EMBED>
</OBJECT>
</td>
<?
}
elseif($extension=="mp3"){
?>
<td align=center>
<audio controls>
  <source src="images/<? echo $row['blog_picture']; ?>" type="audio/mpeg">
</audio>
</td>
<?
}
}
?>
</tbody>
</table>
<? } ?>
					</div>
				
<div class="box">
						<h2>Add Comment</h2>
						<div class="utils">
							<a href="#">Advanced</a>
						</div>
<table>
<tr>

						<form action="add_comment.php" method="post" enctype="multipart/form-data">
							
								<input type="hidden" name="blog_title" value="<? echo $blog_title; ?>"/>
							
<td>							<p>
								<label for="post">Post <small>Parsed by Markdown.</small> </label>
								<textarea name="blog_message"></textarea>
							</p></td>
<td>							<p>
<input type="hidden" name="user_id" value="<? echo $user_id; ?>">
<input type="hidden" name="root_id" value="<? echo $blog_id; ?>">
<input type="file" name="image">
								<input type="submit" name="blog" value="post" />
							</p></td>
						</form></tr></table>
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
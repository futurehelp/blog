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


if(isset($_POST['upload']))
{
	$image=$_FILES['image']['name'];

//if ($image) 

	$filename = stripslashes($_FILES['image']['name']);
	$result=$member->upload_picture($filename);
	//echo $result;
}


if(isset($_POST['update']))
{
	$result=$member->update_profile($_POST['email'], $_POST['fName'], $_POST['lName'],$_POST['language'], $_POST['phone'], $_POST['b_type']);
	if($result==1){ echo "GREAT SUCCESS!!!"; } else { echo "NO SUCCESS!!!"; }
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
<!-- Load CSS -->
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<!-- Load Fonts -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold" type="text/css" />
	<!-- Load jQuery library -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<!-- Load custom js -->
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
			<li><a href="posts.php">Posts</a></li>
			<li><span class="active">Messages</span></li>
<li><a href="logout.php">Log out</a></li>
			<li><form method="post" action="search_blog.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form></li>
		</ul>

			<div id="content" class="container_16 clearfix">
				
					
					<div class="box">
						<h2>Inbox</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
<form method="post" action="delete_message.php">
<table>
<tbody>
<tr>
<td><input type="submit" value="Delete"></td>
<td>From</td>
<td>Message</td>
<td>Date</td>
</tr>

						<?php
$user_id=$member->id;

$result=$db->query("SELECT * FROM messages, users WHERE messages.user1_id=users.id AND user2_id='".$user_id."' ORDER BY msg_timestamp DESC");
while($row=$result->fetchRow()){

echo "<tr><td><input type='checkbox' name='msg_id[]' value='".$row['msg_id']."'></td>";
echo "<td><a href='member_profile.php?user=".$row['username']."'>".$row['username']."</a></td>";
echo "<td><a href='view_message.php?msg=".$row['msg_id']."'>".$row['msg_message']."</a></td>";
echo "<td>".$row['msg_timestamp']."</td>";
if($row['msg_state']==0) echo "<td>Unread</td>";
else echo "<td>Read</td>";
echo "</tr>";
echo "\n";
}
?>



</tbody>
</table>
</form>
				</div>

<div class="box">
						<h2>Sent Messages</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
<table>
<tbody>
<tr>
<td>To</td>
<td>Message</td>
<td>Date</td>
</tr>

						<?php
$user_id=$member->id;

$result=$db->query("SELECT * FROM users,messages WHERE users.id=messages.user2_id AND user1_id='".$user_id."' ORDER BY msg_timestamp DESC");
while($row=$result->fetchRow()){
	echo "<td><a href='member_profile.php?user=".$row['username']."'>".$row['username']."</a></td>";
	echo "<td><a href='view_message.php?msg=".$row['msg_id']."'>".$row['msg_message']."</a></td>";
	echo "<td>".$row['msg_timestamp']."</td></tr>";
}
?>



</tbody>
</table>
					</div>
					
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
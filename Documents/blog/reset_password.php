<?php 
require('sql_class.php');
require('registration_class.php');
require('blog_includes.php');
//Connect to the database
$db=&new MySQL(array('host'=>$dbHostname,'user'=>$dbUsername,'password'=>$dbPassword,'database'=>$dbName));

$tmp = new registration() ; 
$email=$_GET['email'];
$resetcode=$_GET['resetcode'];
$reset=$tmp->recover($db, $email, $resetcode) ; 
if($reset==1){
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
			<li><span class="active">Profile</span></li>
<li><a href="feed.php">Feeds</a></li>
			<li><a href="newpost.php">New Post</a></li>
			<li><a href="messages.php">Messages</a></li>
			<li><form method="post" action="search_blog.php"><input type="text" name="keyword" value=""><input type=submit name=submit value="Search"></form></li>
		</ul>

			<div id="content" class="container_16 clearfix">
				<div class="grid_5">
					<div class="box">
						<h2>Reset Password</h2>
						<div class="utils">
							<a href="#">View More</a>
						</div>
						<form method="post" action="recover.php">
<input type="hidden" name="email" value="<? echo $email; ?>">
<input type="hidden" name="resetcode" value="<? echo $resetcode; ?>">
Enter new password: <input type="password" name="password">
Re-enter new password: <input type="password" name="password2">
<input type="submit" name="submit" value="Submit">
</form>


					</div>
					
					
				</div>
				
				
			</div>
		<div id="foot">
			<div class="container_16 clearfix">
				<div class="grid_16">
					Trill Blog is a registered trademark of drewraines.com
				</div>
			</div>
		</div>
	</body>
</html>
<? } else { echo "error"; } ?>
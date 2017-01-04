<?
class registration 
    { 
	var $db;

    function change_password(&$db, $email, $resetcode, $password)
    {
        $this->db=$db;
        $result=$this->db->query("UPDATE users SET password='".$password."' WHERE email='".$email."'");
        $delete=$this->db->query("DELETE FROM pass_recover WHERE email='".$email."' AND resetcode='".$resetcode."'");
        header("Location: index.php");

    }

    function recover(&$db, $email, $resetcode)
    {
        $this->db=$db;
        //check if recover is in progress
        $result=$this->db->query("SELECT * FROM pass_recover WHERE email='".$email."' AND resetcode='".$resetcode."'");
        if($result->countRows()>0){
            return 1;
        }
    }

    function recover_email(&$db, $recipient)
    {
        $this->db=$db;
        $result=$this->db->query("SELECT * FROM users WHERE email='".$recipient."'");
        if($result->countRows()>0)
        {
            $date=date('Y-m-d h-m-s');
            $resetcode=md5($recipient.$date);
            $result=$this->db->query("INSERT INTO pass_recover (email, resetcode) VALUES ('$recipient', '$resetcode')");
            $email = "support@trillblog.com";
            $subject="Password recovery";
            $header = "From: < " . $email . " > \r\n";
            $message = "Hello " . $recipient . ",\r\n\r\n" ; 
                                                    
                                                    $message .= "To recovery your password click on the following link or copy-paste it in your browser :\r\n\r\n" ; 
                                                    $message .= "http://" . $_SERVER['HTTP_HOST'] . "/blog/reset_password.php?resetcode=" .$resetcode . "&email=".$recipient."\r\n\r\n" ; 
            //ini_set('sendmail_from', 'me@domain.com');
            if(mail($recipient, $subject, $message, $header))
            {
                //Email success!
            }
            else 
            { 
                echo "Email error";
            }
                                                    
        }
        else
        { 
            echo "Email address not found."; 
        }

    }

    function start_registration(&$db) 
    { 
        session_start(); 
        $this->db=$db;
        $username = strtolower($_POST['username']); 
        $passwordold = $_POST['password'] ; 
        $password = md5($passwordold) ;
        $emailAddress = $_POST['emailAddress'] ;  
        $language="eng";
        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];
        $phoneNumber=$_POST['phoneNumber'];
        $activation = $_GET['activation'] ; 
        $user = $_GET['user'] ; 
        if(!isset($activation))
        {
                

            /* prepare the vars */ 
            $activatecode = $username . $password ; 
            $code = md5($activatecode) ; 
            /* to how send to mail */ 
            $to = $email ; 
            /* prepare the subject */ 
            $subject = "You need to confirm your registration to " . $_SERVER['HTTP_HOST'] ; 
            /* start writing the message */ 
            $message = "Hello " . $username . ",\r\n\r\n" ; 
            $message .= "Thank you for registering at www.trillbog.com\r\n Your account is created and must be activated before you can use it.\r\n" ;
            $message .= "To activate the account click on the following link or copy-paste it in your browser :\r\n\r\n" ; 
            $message .= "http://" . $_SERVER['HTTP_HOST'] . "/register_send.php?user=" . $username . "&activation=" . $code . "\r\n\r\n" ; 
            $message .= "After activation you may login to http://www.trillblog.com using the following username and password:\r\n\r\n" ; 
            $message .= "Username - " . $username . "\r\nPassword - " . $passwordold . "\r\n" ; 

            /* To send HTML mail, you can set the Content-type header. */ 
            $headers  = "MIME-Version: 1.0"; 
            $headers .= "Content-type: text/html; charset=iso-8859-1"; 

            /* set up additional headers */ 
            $headers .= "To: " . $to . "<br>\n" ; 
            $headers .= "From: " . $from . $addmail ; 

            /* writing data in the base */ 
            $username=strtolower($username);
            $result=$this->db->query("INSERT INTO registration (id,username,password,email,language,fName,lName,phone,activation,activationcode) VALUES (NULL,'$username','$password','$emailAddress','$language','$firstName','$lastName','$phoneNumber','0','$code')");

            if ($result == false) die("Failed " . $query); 
            else 
            { 
                /* everything went well so we can mail it now */ 
                $headers = 'From: support@trillblog.com' . "\r\n" .
                'Reply-To: drew@drewraines.com'. "\r\n" .
                'X-Mailer: PHP/' . phpversion();
                $fromEmail="support@trillblog.com";
                $ehead = "From: ".$fromEmail."\r\n";
                //$mailsend=mail("$emailAddress","$subject","$message","$ehead");
                //     echo "mailsend: ".$mailsend."<BR>";
                if(mail($emailAddress, $subject, $message, $headers))
                {
                    //Email Sent //
                } else { echo "error"; }
            }
        } 
        else 
        { 
            /* controle if the validation link is right */ 
            $x = 0 ; 
            $result=$this->db->query("SELECT username, password, activationcode, language, email, fName, lName, phone FROM registration WHERE username='$user'"); 
            if ($result == false) die("Failed " . $query); 
            $row=$result->fetchRow();
            if($row){
                $check1=$row['activationcode'];
                $check2=$row['username'];
                $password=$row['password'];
                $email=$row['email'];
                $fName=$row['fName'];
                $lName=$row['lName'];
                $phone=$row['phone'];
                $language=$row['language'];
            }
            
            if($activation == $check1 AND $user == $check2) 
            { 
                $x = 1 ; 
                //$result2=$this->db->query("UPDATE registration SET activation = '1' WHERE username = '$user' AND activationcode='$activation'");
                $result2=$this->db->query("DELETE FROM registration WHERE username='".$user."'");
                if ($result2 == false) die("Failed " . $query2); 
            } 
            else 
                $x = -1;                
        } 

                /* give a confirmation message to the user */ 
        if($x == 1) {
            $date=date('Y-m-d h-m-s');

            $result=$this->db->query("INSERT INTO users (id,username,password,email,language,fName,lName,phone,online,profile_img,blog_posts,messages,last_signon,user_created) VALUES (NULL,'$user','$password','$email','$language','$fName','$lName','$phone','0','0','0','0','$date','$date')");

            header("Location: index.php"); }
        else {
            header("Location: index.php");
        }
    } 
} 

?>
<?php
session_start();

require "mail.php";

// initialize variables
$username = "";
$email = "";
$forgotemail = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'flashcardDB');


// REGISTER USER
if(isset($_POST['reg_user'])) {
    // recieve all input vallues from the form
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    //ensure that the form is correctly filled
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }

    //check email is not already in use
    $email_check_quary = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($db, $email_check_quary);
    $emailUsed = mysqli_fetch_assoc($result);

    if($emailUsed) { array_push($errors, "Email already in use."); }
    
    //MX checks if email has valid email exchange records
    if(!empty($email)) {
        list($local, $domain) = explode('@', $email);
        if(!checkdnsrr($domain, "MX")) { 
            array_push($errors, "Please enter a valid email"); 
        } 
    }
    
    //change top style of toggle password button based on size of error array
    $top = 61;
    if(count($errors) > 2) {
        $top = 65;
    } else if(count($errors) > 1) {
        $top = 63;
    }

    if(count($errors) == 0) {
        $password = md5($password); //encrypt the password before saving in the database

        $query = "INSERT INTO users (email, password)
                                VALUES('$email', '$password')";
        mysqli_query($db, $query);
        $query = "SELECT * FROM users WHERE email='$email'";
  	    $results = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($results);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $email;
  	    $_SESSION['success'] = "You are now logged in";
        
        //send email
          send_mail($email,'REGISTRATION CONFIRMATION',
          "This is a email confirming your registration.");
        header('location: ../app.php');
        die;
    }
}

// LOGIN USER
if(isset($_POST['login_user'])) {
    //recieve all input values from the form
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    //ensure that the form is correctly filled
    if(empty($email)) { array_push($errors, "Email is required"); }
    if(empty($password)) { array_push($errors, "Password is required"); }
    
    //MX checks if email has valid email exchange records
    if(!empty($email)) {
        list($local, $domain) = explode('@', $email);
        if(!checkdnsrr($domain, "MX")) { 
            array_push($errors, "Please enter a valid email"); 
        } 
    }

    //change top style of toggle password button based on size of error array
    $top = 58;
    if(count($errors) > 2) {
        $top = 62;
    } else if(count($errors) > 1) {
        $top = 60;
    }
    
    if(count($errors) == 0) {
        $password = md5($password);
  	    
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  	    $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_assoc($results);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $email;
            $_SESSION['success='] = "You are now logged in";
            header('location: ../app.php');
            die;
        } else {
            array_push($errors, "Wrong email/password combination");
        }
    }
}

// FORGOT PASSWORD
if(isset($_POST['forgot_user'])) {
    //recieve all input values from the form
    $email = mysqli_real_escape_string($db, $_POST['email']);

    $expire = time() + (60 * 5); //expires after 5 minutes
    $code = rand(100000, 999999);

    //ensure that the form is correctly filled
    if(empty($email)) { array_push($errors, "Email is required"); }
    
    //MX checks if email has valid email exchange records
    if(!empty($email)) {
        list($local, $domain) = explode('@', $email);
        if(!checkdnsrr($domain, "MX")) { 
            array_push($errors, "Please enter a valid email"); 
        } 
    }

    if(count($errors) == 0) {
        $query = "SELECT * FROM users WHERE email='$email'";
  	    $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['email'] = $email;
            $query = "INSERT INTO codes (email, code, expire)
                            VALUES('$email', '$code', '$expire')";
            mysqli_query($db, $query);

            //send email here
            send_mail($email,'Password Reset',
                    "Your code is: ".$code.".<br><br>Code will expire in 5 minutes.");
            header('location: index.php?mode=enter_code');
            die;
        } else {
            array_push($errors, "Email not in our system");
        }
    }
}

// ENTER CODE
if(isset($_POST['enter_code'])) {
    //recieve all input values from the form
    $code = mysqli_real_escape_string($db, $_POST['code']);
    $expire = time();
    $email = mysqli_real_escape_string($db, $_SESSION['email']);
    
    //ensure that the form is correctly filled
    if(empty($code)) { array_push($errors, "Code is required"); }
    
    if(count($errors) == 0) {
        $query = "SELECT * FROM codes WHERE email='$email' AND code='$code' 
                    ORDER BY code_id DESC LIMIT 1";
                    //order by id desc gives us the latest code
  	    $results = mysqli_query($db, $query);
        if(mysqli_num_rows($results) > 0) {
            $row = mysqli_fetch_assoc($results);
            $code_used = $row['code_used'];
            if(!$code_used && ($row['expire'] > $expire)) {
                $query = "UPDATE codes SET code_used=1 WHERE code='$code'";
                mysqli_query($db, $query);
                echo $code_used;
                header('location: index.php?mode=new_password');
                die;
            } else {
                array_push($errors, "Code already used or expired");
            }
        } else {
            array_push($errors, "Incorrect code");
        }
    }
    
}

// SET NEW PASSWORD
if(isset($_POST['new_password'])) {
    $email = mysqli_real_escape_string($db, $_SESSION['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    //ensure that the form is correctly filled
    if(empty($password)) { array_push($errors, "Password is required"); }

    if(count($errors) == 0) {
        $password = md5($password);
  	    
        $query = "UPDATE users SET password='$password' WHERE email='$email' LIMIT 1";
        mysqli_query($db, $query);
        $query = "SELECT * FROM users WHERE email='$email'";
  	    $results = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($results);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $email;
        $_SESSION['success'] = "You are now logged in";
        header('location: ../app.php');
        die;
    }
}

//Close connection to database
mysqli_close($db);
?>
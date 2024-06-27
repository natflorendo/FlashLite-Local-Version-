<?php include('login.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlashLite Login</title>
    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    $mode = "login_user";
    if(isset($_GET['mode'])) {
        $mode = $_GET['mode'];
    }

    //something is posted
    if(count($_POST) > 0) {
        switch($mode) {
            case 'login_user':
                break; 
            case 'reg_user':
                break;
            case 'forgot_user':
                break;
            case 'enter_code':
                break;
            case 'new_Password':
            
        }
    }
    ?>
    <div class="signup-msg hide">Please use a unique password because idk how password security works :)</div>
    <canvas width="800" height="650"></canvas>
    <div class="container">
        <?php
        switch($mode) {
            case 'login_user':
            ?>
                <form method="post" action="index.php?mode=login_user">
                    <h1 class = "header">Log In</h1>
                    <?php include('errors.php'); ?>
                    <label for="email">Email:</label>
                    <input type="email" class="email" name="email" placeholder="email here..."/>
                    <label class="password-lbl" for="password">Password:</label>
                    <div class="password-con">
                        <input type="password" class="password" name="password" placeholder="Password here..."/>
                        <button type="button" class="toggle-password" style="top: <?php echo $top; ?>%;">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                    <button type="submit" class="submit-btn" name="login_user">Sign In</button>
                    <button type="submit" formaction="index.php?mode=forgot_user" class="forgot-password">
                        Forgot Password?
                    </button>
                    <span class="have-account">Don't have an account? 
                        <button type="submit" formaction="index.php?mode=reg_user" class="account">Sign Up</button>
                    </span>
                </form>
           <?php           
                break;
            case 'reg_user':
            ?>
                <form method="post" action="index.php?mode=reg_user">
                    <h1 class = "header">Register</h1>
                    <?php include('errors.php'); ?>
                    <label for="email">Email:</label>
                    <input type="email" class="email" name="email" placeholder="email here..."/>
                    <label class="password-lbl" for="password">Create Password:</label>
                    <div class="password-con">
                        <input type="password" class="password" name="password" placeholder="Password here..."/>
                        <button type="button" class="toggle-password" style="top: <?php echo $top; ?>%;">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                    <button type="submit" class="submit-btn" name="reg_user">Sign Up</button>
                    <span class="have-account">Already have an account? 
                        <button type="submit" formaction="index.php?mode=login_user" class="account">Sign In</button>
                    </span>
                </form>
                <script>
                    document.querySelector(".signup-msg").classList.remove("hide");
                </script>
            <?php
                break;
            case 'forgot_user':
            ?>
                <form method="post" action="index.php?mode=forgot_user">
                    <h1 class = "header">Forgot Password</h1>
                    <?php include('errors.php'); ?>
                    <label for="email">Email:</label>
                    <input type="email" class="email" name="email" placeholder="email here..."/>
                    <div class="password-con hide">
                        <input type="password" class="password" name="password" placeholder="Password here..."/>
                        <button type="button" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></button>
                    </div>
                    <button type="submit" class="submit-btn" name="forgot_user">Send Code</button>
                    <span class="have-account">Already have an account? 
                        <button type="submit" formaction="index.php?mode=login_user" class="account">Sign In</button>
                    </span>
                </form>
            <?php
                break;
            case 'enter_code':
            ?>
                <form method="post" action="index.php?mode=enter_code">
                    <h1 class = "header">Enter Code</h1>
                    <?php include('errors.php'); ?>
                    <input type="text" class="email" name="code" placeholder="code here..."/>
                    <div class="password-con hide">
                        <input type="password" class="password" name="password" placeholder="Password here..."/>
                        <button type="button" class="toggle-password"><i class="fa-solid fa-eye-slash"></i></button>
                    </div>
                    <div>Code will expire in 5 minutes</div>
                    <button type="submit" class="submit-btn" name="enter_code">Enter</button>
                    <span class="have-account">Already have an account? 
                        <button type="submit" formaction="index.php?mode=login_user" class="account">Sign In</button>
                    </span>
                </form>
            <?php
                break;
            case 'new_password':
            ?>
                <form method="post" action="index.php?mode=new_password">
                    <h1 class = "header">New Password</h1>
                    <?php include('errors.php'); ?>
                    <input type="email" class="email hide" name="email" placeholder="email here..."/>
                    <div class="password-con">
                        <input type="password" class="password" name="password" placeholder="Password here..."/>
                        <button type="button" class="toggle-password" style="top: 47%;">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                    <button type="submit" class="submit-btn" name="new_password">Change Password</button>
                    <span class="have-account">Already have an account? 
                        <button type="submit" formaction="index.php?mode=login_user" class="account">Sign In</button>
                    </span>
                </form>
            <?php
                break;
        }
        ?>
        <div class="ear-l"></div>
        <div class="ear-r"></div>
        <div class="panda-face">
            <div class="blush-l"></div>
            <div class="blush-r"></div>
            <div class="eye-l">
                <div class="eyeball-l"></div>
            </div>
            <div class="eye-r">
                <div class="eyeball-r"></div>
            </div>
            <div class="nose"></div>
            <div class="mouth"></div>
        </div>
        <div class="hand-l"></div>
        <div class="hand-r"></div>
        <div class="paw-l"></div>
        <div class="paw-r"></div>
    </div>

    <!-- Script -->
     <script src="login.js"></script>
</body>
</html>
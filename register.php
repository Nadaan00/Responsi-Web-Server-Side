<?php
    require_once "config.php";

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
            $username_err = "Username can only contain letters, numbers, and underscores.";
        } else{
            $sql = "SELECT id FROM user WHERE username = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);
    
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "This username is already taken.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password."; 
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
    
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password."; 
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
    
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); 
        
                if(mysqli_stmt_execute($stmt)){
                    header("location: login.php");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($link);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #DDA0DD ;
        }

        h2 {
            text-align: center;
            font-weight: 300;
        }

        p {
            text-align: center;
        }

        .wrapper {
            width: 400px;
	        background: white;
	        margin: 80px auto;
	        padding: 30px 20px;
        }

        label {
	        font-size: 11pt;
        }

        .form_control {
	        box-sizing : border-box;
	        width: 100%;
	        padding: 10px;
	        font-size: 11pt;
	        margin-bottom: 20px;
            margin-left: 20px;
        }

        .btn-submit {
	        background: #800080;
	        color: white;
	        font-size: 11pt;
	        width: 85%;
	        border: none;
	        border-radius: 3px;
	        padding: 10px 20px;
            margin-top: 20px;
        }

        .btn-reset {
	        background: #800080;
	        color: white;
	        font-size: 11pt;
	        width: 85%;
	        border: none;
	        border-radius: 3px;
	        padding: 10px 10px;
            margin-top: 20px;
        }

        .tulisan_login{
	        text-align: center;
	        text-transform: uppercase;
        }

        .a {
	        color: #232323;
	        text-decoration: none;
	        font-size: 10pt;
        }

        .form-group label, input{
            margin-left: 30px;
        }

        .invalid-feedback {
            color: red;
            margin-left: 30px;
            font-size: 90%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="tulisan-login">Sign Up</h2>
        <p>Please fill this form to create an account.</p>
            
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label><br>
                <input type="text" name="username" class="form-control 
                <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php  echo $username; ?>">
                <br><span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div> 
            
            <div class="form-group">
                <label>Password</label><br>
                <input type="password" name="password" class="form-control 
                <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <br><span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label><br>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'isinvalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <br><span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn-submit" value="Submit">
                <input type="reset" class="btn-reset" value="Reset">
            </div>
            
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div> 
</body>
</html>
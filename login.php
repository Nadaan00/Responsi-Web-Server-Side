<?php
    session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }

    require_once "config.php";

    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter username.";
        } else{
            $username = trim($_POST["username"]);
        }
 
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password.";
        } else{
            $password = trim($_POST["password"]);
        }
 
        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, username, password FROM user WHERE username = ?";
 
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
 
                $param_username = $username;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){ 
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                session_start();
   
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username; 
                                header("location: index.php");
                            } else{
                                $login_err = "Invalid username or password.";
                            }       
                        }
                    } else{
                        $login_err = "Invalid username or password.";
                    }
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
    <title>Login</title>
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

        .btn {
	        background: #800080;
	        color: white;
	        font-size: 11pt;
	        width: 85%;
	        border: none;
	        border-radius: 3px;
	        padding: 10px 20px;
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
            margin-left: 35%;
            font-size: 80%;
        }

        .alert {
            color: red;
            text-align: center;
            font-size: 90%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
    <h2> Login </h2>
    <p> Please fill in your credentials to login </p>
 
    <?php 
        if(!empty($login_err)){
            echo '<div class="alert">' . $login_err . '</div>';
        } 
    ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <br><div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control 
            <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <br><span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div> 
 
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control 
            <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <br><span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
 
        <div class="form-group">
            <input type="submit" class="btn" value="Login">
        </div>
 
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>
 </div>
</body>
</html>   
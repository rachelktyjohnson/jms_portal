<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

// Include config file
require_once "config.php";

$username = "";
$errormessage = "";

if (!empty($_POST)){

  $query = $pdo->prepare("SELECT * FROM students WHERE username = ?");
  $query->execute([$_POST['username']]);
  $username = $_POST['username'];
  $user = $query->fetch();

  if ($user && password_verify($_POST['password'], $user['password']))
  {
    session_start();

    // Store data in session variables
    $_SESSION["loggedin"] = true;
    $_SESSION["username"] = $username;

    // Redirect user to welcome page
    header("location: dashboard.php");

  } else {
      $errormessage = "Error logging in. Please check your username and password.";
  }
}


?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>JMS Portal</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="https://use.typekit.net/qmr2evg.css">
        <link rel="stylesheet" href="css/login-styles.css">
    </head>
    <body>
      <div class="loginContainer">
        <img src="img/jms-logo.png" />
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-control">
            <label>Username</label>
            <input type="text" name="username" id="username" value="<?= $username;?>" />
          </div>
          <div class="form-control">
            <label>Password</label>
            <input type="password" name="password" id="password" />
          </div>
          <input type="submit" class="loginbutton" value="Login">
          <span><?= $errormessage; ?></span>
        </form>
      </div>


    </body>
</html>

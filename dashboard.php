
<?php

// Initialize the session
session_start();

// if not logged in, send to login page
if (!isset($_SESSION["loggedin"])){
header("location: login.php");
exit;
}

// Include config file
require_once "config.php";

//pull user's data from table
try{
  $results = $pdo->prepare("SELECT * FROM users WHERE username=?");
  $results->bindParam(1, $_SESSION["username"], PDO::PARAM_STR);
  $results->execute();
  $userData = $results->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  echo "Error: ". $e->getMessage();
  die();
}

?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Dashboard - JMS Portal</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://use.typekit.net/qmr2evg.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
      <nav id="dashboard-nav">
        <div class="pre-links">
          <img src="img/portal-logo.png" />
          <a href="#" class="trigram">&#9776;</a>
        </div>

        <div class="nav-links">
          <a href="dashboard.php">Dashboard</a>
          <a href="logout.php" class="logout">Log Out</a>
        </div>
      </nav>
      <main id="dashboard-container">
        <div class="dashboard-header">
          <?php
            $userFirstName = explode(" ",$userData['name']);
          ?>
          <h1>Hi, <?= $userFirstName[0]; ?>!</h1>
          <h2>Term 2 - Week 1</h2>
        </div>
        <div class="dashboard-announcement">
          <div class="announcement-meta">
            <h2>Term 2 starts!</h2>
            <span>26/04/2020</span>
          </div>
          <p>
            This week we are back in the swing of things for Term 2! For those who have opted for online lessons, we will be continuing as usual over Zoom. Opt-in is available at any time, just give us a call or email us!
          </p>
        </div>
        <div class="dashboard-content">
          <div class="card instruments">
            <h2>Your Instruments</h2>
            <div class="instruments-list">
              <?php
                $timeslot_arr = explode(",",$userData['instrument']);
                foreach ($timeslot_arr as $data){
                  echo "<img src='img/tab-$data.png' alt='$data' title='" . ucfirst($data) . "'/>";
                }
              ?>
            </div>
          </div>
          <div class="card timeslot">
            <h2>Your Timeslots</h2>
            <?php
              $timeslot_arr = explode(",",$userData['timeslot']);
              foreach ($timeslot_arr as $data){
                echo "<p>$data</p>";
              }
            ?>
          </div>
          <div class="card zoom">
            <h2>Zoom Lesson Details</h2>
            <div class="form-control">
              <label>Direct Link</label>
              <input disabled value="<?= $userData['zoom_link']; ?>" />
            </div>
            <div class="form-control">
              <label>Meeting ID</label>
              <input disabled value="<?= $userData['zoom_id']; ?>" />
            </div>
          </div>
        </div>

      </main>
      <footer>
        &copy;  <?= date('Y');?>  Johnson Music School &amp; <a target="_blank" href="http://www.tealjay.com">tealjay</a>
      </footer>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="js/script.js"></script>
    </body>
</html>

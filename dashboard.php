
<?php

// Initialize the session
session_start();

// if not logged in, send to login page
if (!isset($_SESSION["loggedin"])){
header("location: login.php");
exit;
}

// Include config file
include "config.php";
include "functions.php";

//pull user's data from table
try{
  $results = $pdo->prepare("SELECT * FROM students WHERE username=?");
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

          <h2><?= getTermWeek($pdo); ?></h2>
        </div>



        <div class="dashboard-announcement">
          <?php
            $announcementsQuery = $pdo->prepare("SELECT * FROM announcements LIMIT 1");
            $announcementsQuery->execute();
            $announcementData = $announcementsQuery->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="announcement-meta">
            <h2><?= $announcementData['title'];?></h2>
            <span><?=date_format(new DateTime($announcementData['date']), 'd-m-Y');?></span>
          </div>
          <p>
            <?= $announcementData['description'];?>
          </p>
        </div>
        <div class="dashboard-content">
          <div class="card lessons">
            <?php
            $plural="";
            $instrument_arr = explode(",",$userData['instrument']);
            $timeslot_arr = explode(",",$userData['timeslot']);
            if (count($instrument_arr)>1){
              $plural="s";
            }
            ?>
            <h2>Your Lesson<?= $plural; ?></h2>
            <?php
              for ($i=0; $i<count($instrument_arr); $i++){?>
                <div class="lesson_tab">
                  <div class="lesson_data">
                    <h3><?=ucfirst($instrument_arr[$i])?></h3>
                    <p><?= $timeslot_arr[$i]; ?></p>
                    <p>Teacher: Mark</p>
                  </div>
                  <img src='img/tab-<?=$instrument_arr[$i]?>.png' alt='<?=$instrument_arr[$i]?>' title='" . <?=ucfirst($instrument_arr[$i])?> . "'/>

                </div>

              <?php }
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


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

$userID = $userData['id'];

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

          <h1>Hi, <?= getFirstName($userData['name']); ?>!</h1>

          <h2><?= getTermWeek($pdo); ?></h2>
        </div>


        <?php
          $announcementsQuery = $pdo->prepare("SELECT * FROM announcements WHERE feature=1 ORDER BY date DESC");
          $announcementsQuery->execute();
          $announcementData = $announcementsQuery->fetchAll(PDO::FETCH_ASSOC);

          foreach ($announcementData as $announcement){
        ?>
        <div class="dashboard-announcement <?= $announcement['type']; ?>">
          <div class="announcement-meta">
            <h2><?= $announcement['title'];?></h2>
            <span><?=date_format(new DateTime($announcement['date']), 'd-m-Y');?></span>
          </div>
          <p>
            <?= $announcement['description'];?>
          </p>
        </div>
        <?php } ?>
        <div class="dashboard-content">
          <div class="card lessons">

            <?php
            //get lessons for this student
            $querystring = "SELECT day, instruments.name AS instrument, teachers.name AS teacher, start_time, end_time, zoom_link, zoom_id FROM lessons
                            JOIN instruments ON lessons.instrument=instruments.id
                            JOIN teachers ON lessons.teacher=teachers.id
                            WHERE student=?";
            $lessonsQuery = $pdo->prepare($querystring);
            $lessonsQuery->bindParam(1, $userID, PDO::PARAM_INT);
            $lessonsQuery->execute();
            $lessonsData = $lessonsQuery->fetchAll(PDO::FETCH_ASSOC);
            $plural="";
            if (count($lessonsData)>1){$plural="s";}
            ?>
            <h2>Your Lesson<?=$plural?></h2>
            <?php foreach ($lessonsData as $lesson){
            ?>

            <div class="lesson_details">

              <?php
              $instrument = explode(" ",$lesson['instrument']);
              $instrument = strtolower(end($instrument));
              ?>

              <div class="lesson_header">
                <img src='img/tab-<?=$instrument?>.png' alt='<?=$instrument?>' title='<?=$instrument?>'/>
                <h3><?= ucfirst($instrument)?></h3>
              </div>


              <div class="lesson_data">
                <p><?= getDayOfWeek($lesson['day']) ?></p>
                <p><?= getCommonTime($lesson['start_time']) ?>-<?= getCommonTime($lesson['end_time']) ?></p>
                <p>Teacher: <?= getFirstName($lesson['teacher']) ?></p>
              </div>

                  <div class="zoom_tab">
                    <h4>Zoom Details</h4>
                    <?php if ($lesson['zoom_link']!=null && $lesson['zoom_link']!=null){?>
                      <p><a target="_blank" href="<?= $lesson['zoom_link'] ?>">Zoom Link</a></p>
                      <p>ID: <?= $lesson['zoom_id']?></p>
                    <?php } else { ?>
                      <p>
                        N/A
                      </p>
                    <?php }?>
                  </div>


            </div>

            <?php }?>

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

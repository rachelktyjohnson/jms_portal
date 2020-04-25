
<?php

// Initialize the session
session_start();

// if not logged in, send to login page
if (!isset($_SESSION["loggedin"])){
header("location: login.php");
exit;
}

// Include config file
include "includes/config.php";
include "includes/functions.php";

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

  <?php include 'includes/header.php' ?>

      <main id="dashboard-container">
        <div class="dashboard-header">
          <h1>School Dates</h1>
        </div>

        <div class="calendar-content">
          <br />
          <table>
            <tr>
              <th>Term</th>
              <th>Weeks</th>
              <th>Start Date</th>
              <th>End Date</th>
            </tr>

            <?php
            //get terms data from database
            $calendarQuery = $pdo->prepare("SELECT * FROM terms");
            $calendarQuery->execute();
            $calendarData = $calendarQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($calendarData as $term){
              $currentTerm = "";
              if ($term['start']<date('Y-m-d') && $term['end']>date('Y-m-d')){
                $currentTerm = "style='color:#018f5f !important;'";
              }
            ?>

            <tr <?=$currentTerm;?>>
              <td><?= $term['term']?></td>
              <td><?= getWeekDiff($term['start'],$term['end']);?> Weeks</td>
              <td><?= getAUSDateFormat($term['start'])?></td>
              <td><?= getAUSDateFormat($term['end'])?></td>
            </tr>

            <?php } ?>

          </table>
        </div>


      </main>
<?php include 'includes/footer.php'; ?>

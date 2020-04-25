<?php


//Function to return the current Term and Week
function getTermWeek($pdo){

  //work out term
  $termQuery = $pdo->prepare("SELECT * FROM terms WHERE start < CURDATE() AND end > CURDATE()");
  $termQuery->execute();
  $termData = $termQuery->fetch(PDO::FETCH_ASSOC);

  //work out date
  $termStart = $termData['start'];
  $startDate = new DateTime($termStart);
  $todayDate = new DateTime(date('Y-m-d'));
  $difference = $startDate -> diff($todayDate);
  $theWeek = floor($difference->format('%R%a')/7) +1;

  return $termData['term'] . " - " ."Week " . $theWeek;

}

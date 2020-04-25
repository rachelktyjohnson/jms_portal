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

function getDayOfWeek($num){
  switch($num){
    case 1:
      return "Monday";
      break;
    case 2:
      return "Tuesday";
      break;
    case 3:
      return "Wednesday";
      break;
    case 4:
      return "Thursday";
      break;
    case 5:
      return "Friday";
      break;
    case 6:
      return "Saturday";
      break;
    default:
      return "Sunday";
      break;
    }
}

function getCommonTime($time){
  $datetime = new DateTime($time);
  return $datetime->format('g:iA');
}

function getFirstName($fullname){
  $arr = explode(" ",$fullname);
  return $arr[0];
}

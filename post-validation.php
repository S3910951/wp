<?php
include('tools.php');
function validateBooking() {
  global $moviesObject; 
  $errors = []; 

  // Validate movie code
  if (empty($_POST['movie']) || !isset($moviesObject[$_POST['movie']])) {
      header('Location: index.php'); 
      exit();
  }

  // Validate day
  if (empty($_POST['day']) || !array_key_exists($_POST['day'], $moviesObject[$_POST['movie']]['screenings'])) {
      header('Location: index.php'); 
      exit();
  }

  // Validate seat selections
  $seatSelected = false;
  foreach ($_POST['seats'] as $type => $quantity) {
      if ($quantity > 0) $seatSelected = true;
  }
  if (!$seatSelected) $errors['seats'] = 'At least one seat must be selected.';

  // Validate customer fields
  if (!filter_var($_POST['customer']['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['customer']['email'] = 'Invalid email address.';
  }

  return $errors;
}

?>
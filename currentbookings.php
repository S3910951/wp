<?php

include_once('tools.php');
session_start();

// Function to search bookings
function searchBookings($email, $mobile) {
    $bookingsFile = 'bookings.txt';
    $matchingBookings = [];
    
    if (file_exists($bookingsFile)) {
        $file = fopen($bookingsFile, 'r');
        
        while (($line = fgetcsv($file)) !== FALSE) {
            
            if ($line[2] == $email && $line[3] == $mobile) {
                $matchingBookings[] = $line;
            }
        }
        
        fclose($file);
    }
    
    return $matchingBookings;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $bookings = searchBookings($email, $mobile);
    
    if (empty($bookings)) {
        $message = "No bookings found for the provided details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Bookings - Lunardo Cinema</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="bookings-container">
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-summary">
                    <p><strong>Name:</strong> <?= htmlspecialchars($booking[1]); ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($booking[0]); ?></p>
                    <?php
                    $movieCode = $booking[4];
                    $movieTitle = $moviesObject[$movieCode]['title'] ?? 'Unknown Movie';
                    ?>
                    <p><strong>Movie Title:</strong> <?= htmlspecialchars($movieTitle); ?></p>
                    <p><strong>Day of Movie:</strong> <?= htmlspecialchars($booking[5]); ?></p>
                    <p><strong>Time of Movie:</strong> <?= htmlspecialchars($booking[6]); ?></p>
                    <form action="receipt.php" method="post">
                        <input type="hidden" name="bookingData" value="<?= base64_encode(serialize($booking)); ?>">
                        <button type="submit">View Receipt</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?= $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

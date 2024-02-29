<?php
include_once('tools.php');
session_start();
if (!isset($_SESSION['posted_data'])) {
    header('Location: index.php');
    exit();
}

$postedData = $_SESSION['posted_data']; 

$selectedMovieCode = $postedData['movie'] ?? ''; 
$selectedMovie = $moviesObject[$selectedMovieCode] ?? null;

if (!$selectedMovie || !isset($postedData['day'])) {
    echo "Booking information incomplete. Please start your booking process again.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Lunardo Cinema</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="receipt-container">
        <header>
            <!-- Company details -->
            <h1>Lunardo Cinema</h1>
            <p>123 Cinema Street, Townsville | lunardo@cinema.com | (07) 1234 5678</p>
        </header>
        <main>
            <h2>Receipt</h2>
            <section>
                <!-- Customer details -->
                <p>Name: <?= $_SESSION['posted_data']['customer']['name']; ?></p>
                <p>Email: <?= $_SESSION['posted_data']['customer']['email']; ?></p>
                <p>Mobile: <?= $_SESSION['posted_data']['customer']['mobile']; ?></p>
                <!-- Booking summary -->
                <table>
                    <!-- Table headers -->
                    <tr>
                        <th>Seat Type</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php
                    $totalPrice = 0;
                    foreach ($_SESSION['posted_data']['seats'] as $seatType => $quantity) {
                        if ($quantity > 0) {
                            $seatPrice = getSeatPrice($seatType, $_SESSION['posted_data']['day'], $_SESSION['posted_data']['time'], $_SESSION['posted_data']['movie']);
                            $subtotal = $seatPrice * $quantity;
                            $totalPrice += $subtotal;
                            $seatTypeName = getSeatTypeName($seatType); 
                            echo "<tr>
                                    <td>$seatTypeName</td>
                                    <td>$quantity</td>
                                    <td>$" . number_format($subtotal, 2) . "</td>
                                </tr>";
                        }
                    }
                    $gst = $totalPrice * (1 / 11); 
                    $totalPriceIncludingGST = $totalPrice;

                    function getSeatTypeName($seatType) {
                        // Mapping of seat type codes to human-readable names
                        $seatTypeNames = [
                            'STA' => 'Standard Adult',
                            'STP' => 'Standard Concession',
                            'STC' => 'Standard Child',
                            'FCA' => 'First Class Adult',
                            'FCP' => 'First Class Concession',
                            'FCC' => 'First Class Child',
                        ];
                    
                        
                        return $seatTypeNames[$seatType] ?? $seatType;
                    }
                    ?>
                    <!-- Display Total and GST -->
                    <tr>
                        <td colspan="2">Total (incl. GST)</td>
                        <td>$<?= number_format($totalPriceIncludingGST, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">GST</td>
                        <td>$<?= number_format($gst, 2); ?></td>
                    </tr>
                </table>
            </section>
            <section>
                <!-- Generate tickets -->
                <?php foreach ($_SESSION['posted_data']['seats'] as $seatType => $quantity): ?>
                    <?php for ($i = 0; $i < $quantity; $i++): ?>
                        <div class="ticket">
                            <!-- Ticket content-->
                            <h3><?= htmlspecialchars($selectedMovie['title']); ?> - Ticket <?= $i + 1; ?> of <?= $quantity; ?></h3>
                            <p>Seat Type: <?= htmlspecialchars(getSeatTypeName($seatType)); ?></p>
                            <p>Screening Day: <?= htmlspecialchars($postedData['day']); ?></p>
                            <p>Screening Time: <?= htmlspecialchars($postedData['time']); ?></p>
                            <div class="ticket-footer">
                                Enjoy the show!
                            </div>
                            <div class="qr-code">
                                <img src="QR_Code.png" alt="QR Code" style="width: 100px; height: 100px;">
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endforeach; ?>
            </section>
        </main>
    </div>
    
</body>
</html>

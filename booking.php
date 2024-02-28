<?php 
include_once('tools.php');
$title = "Booking";
$lastModTime = filemtime("style.css"); 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$selectedMovieCode = $_GET['movie'] ?? '';
$selectedMovie = $moviesObject[$selectedMovieCode] ?? null;

$errors = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('post-validation.php');
    $errors = validateBooking();
    
    if (empty($errors)) {
        $postedData = $_POST;

        if (isset($selectedMovie['screenings'][$postedData['day']])) {
            $postedData['time'] = $selectedMovie['screenings'][$postedData['day']]['time'];
        } else {
            $postedData['time'] = 'Unknown'; 
        }
    
    $totalPrice = 0;
    $seatQuantities = []; 
    $seatSubtotals = []; 

    $seatPrices = [
        'STA' => ['full' => 21.50, 'disc' => 16.00],
        'STP' => ['full' => 19.00, 'disc' => 14.50],
        'STC' => ['full' => 17.50, 'disc' => 13.00],
        'FCA' => ['full' => 31.00, 'disc' => 25.00],
        'FCP' => ['full' => 28.00, 'disc' => 23.50],
        'FCC' => ['full' => 25.00, 'disc' => 22.00],
    ];

    foreach ($postedData['seats'] as $seatType => $quantity) {
        $isDiscounted = isAfternoon($postedData['time']); 
        $priceKey = $isDiscounted ? 'disc' : 'full';
        $seatPrice = $seatPrices[$seatType][$priceKey];
        
        $subtotal = $quantity * $seatPrice;
        $totalPrice += $subtotal;
        

        $seatQuantities[$seatType] = $quantity;
        $seatSubtotals[$seatType] = $subtotal;
    }

    $gst = $totalPrice * 0.1; // GST calculation


    $bookingData = [
        date('Y-m-d H:i:s'), 
        $postedData['customer']['name'],
        $postedData['customer']['email'],
        $postedData['customer']['mobile'],
        $selectedMovieCode,
        $postedData['day'],
        $selectedMovie['screenings'][$postedData['day']]['time'],
    ];

    foreach (['STA', 'STP', 'STC', 'FCA', 'FCP', 'FCC'] as $seatType) {
        $bookingData[] = $seatQuantities[$seatType] ?? 0;
        $bookingData[] = $seatSubtotals[$seatType] ?? 0;
    }

    $bookingData[] = $totalPrice;
    $bookingData[] = $gst;

    // Append booking data to file
    $fileHandle = fopen('bookings.txt', 'a');
    if ($fileHandle !== false) {
        fputcsv($fileHandle, $bookingData);
        fclose($fileHandle);

        $_SESSION['posted_data'] = $postedData; 
        header('Location: receipt.php');
        exit();
    } else {
        echo "Error opening the bookings file.";
    }

    }

    
    
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunardo Cinema</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link id='wireframecss' type="text/css" rel="stylesheet" href="../wireframe.css" disabled>
    <link id='stylecss' type="text/css" rel="stylesheet" href="style.css?t=<?= $lastModTime ?>">
    <script src='../wireframe.js'></script>
</head>
<body>
    <header id="head">
        <div class="logo-title-container">
            <img src="logo-board-wide-fade.jpg" alt="Lunardo Cinema Logo">
        </div>
    </header>
    <nav class="navbar nav-curve">
        <ul class="navbar">
            <li class="navbar" style="float:left"><a href="index.php#head"><h1 class="nav-title">Lunardo Cinema</h1></a></li>
            <li class="navbar navbar-buttons"><a href="index.php#now-showing">Now Showing</a></li>
            <li class="navbar navbar-buttons"><a href="index.php#seats-prices">Seats & Prices</a></li>
            <li class="navbar navbar-buttons"><a href="index.php#about-us">About Us</a></li>
        </ul>
    </nav>
    <main>
        <section id="booking-form">
            <h2 class="Heading-title">Book Your Movie Tickets</h2>
            <div class="video-container">
                <?php if ($selectedMovie && isset($selectedMovie['trailer'])): ?>
                <iframe width="560" height="315" src="<?= htmlspecialchars($selectedMovie['trailer']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php else: ?>
                <p>Trailer not available.</p>
                <?php endif; ?>
            </div>
            
            <form action="" method="post" class="movie-form">
                <div class="synopsis">
                    <h3><?= htmlspecialchars($selectedMovie['title'] ?? 'Select a Movie') ?></h3>
                    <p><?= htmlspecialchars($selectedMovie['summary'] ?? '') ?></p>
                </div>
                <input type="hidden" name="movie" value="<?= htmlspecialchars($selectedMovieCode) ?>">


                <div class="session-buttons">
                    <?php if ($selectedMovie): ?>
                        <?php foreach ($selectedMovie['screenings'] as $day => $screening): ?>
                            <input type="radio" id="session<?= $day ?>" name="day" value="<?= $day ?>" data-pricing="<?= $screening['price'] ?>" hidden>
                            <label for="session<?= $day ?>" class="session-label"><?= $day ?>: <?= $screening['time'] ?></label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="seat-selection">
                    <!-- Standard Adult Seats -->
                    <p>Standard Adult Seats</p>
                    <select name="seats[STA]" data-FULL="21.5" data-DISC="16">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
        
                    <!-- Standard Concession Seats -->
                    <p>Standard Concession Seats</p>
                    <select name="seats[STP]" data-FULL="19.0" data-DISC="14.5">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
        
                    <!-- Standard Child Seats -->
                    <p>Standard Child Seats</p>
                    <select name="seats[STC]" data-FULL="17.5" data-DISC="13.0">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
        
                    <!-- First Class Adult Seats -->
                    <p>First Class Adult Seats</p>
                    <select name="seats[FCA]" data-FULL="31.0" data-DISC="25.0">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
        
                    <!-- First Class Concession Seats -->
                    <p>First Class Concession Seats</p>
                    <select name="seats[FCP]" data-FULL="28.0" data-DISC="23.5">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
        
                    <!-- First Class Child Seats -->
                    <p>First Class Child Seats</p>
                    <select name="seats[FCC]" data-FULL="25.0" data-DISC="22.0">
                        <option value="">Please select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>

                <div class="total-price-container">
                    Total Price: <span id="totalPrice"></span>
                </div>
                
                <!-- Customer Details Fields -->
                <div class="customer-details">
                    <div>
                        <label for="customerName">Full Name:</label>
                        <input type="text" id="customerName" name="customer[name]" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ-.'\s]+" title="Name must contain only letters, spaces, hyphens, apostrophes, or dots.">
                    </div>
                    <div>
                        <label for="customerEmail">Email Address:</label>
                        <input type="email" id="customerEmail" name="customer[email]" required title="Please enter a valid email address.">
                    </div>
                    <div>
                        <label for="customerMobile">Mobile Number:</label>
                        <input type="tel" id="customerMobile" name="customer[mobile]" required pattern="04[\d\s]{8,9}" title="Mobile number must start with '04' followed by 8 digits, with optional spaces.">
                    </div>
                    <label id="rememberMeLabel" for="rememberMe">Remember Me</label>
                    <input type="checkbox" id="rememberMe" name="rememberMe"/>
                </div>



                <!-- Submit button -->
                <input type="submit" value="Book Movie" class="link-button">
            </form>
        </section>      
    </main>
    <footer>
        <div class="footer-form">
            <form action="currentbookings.php" method="post">
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="mobile" placeholder="Your Mobile Number" required>
                <button type="submit">Retrieve Booking</button>
            </form>
        </div>
        <p>Contact us at lunardo@cinema.com | (07) 1234 5678</p>
        <p>123 Cinema Street, Townsville</p>
        <div>&copy;<script>
            document.write(new Date().getFullYear());
          </script> Samuel Smith - S3910951 Last modified <?= date ("Y F d  H:i", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>.</div>
        <a href="https://github.com/S3910951/a2">GitHub Repository</a>
        <div><button id='toggleWireframeCSS' onclick='toggleWireframe()'>Toggle Wireframe CSS</button></div>
    </footer>
    <aside id="debug">
        <hr>
        <h3>Debug Area</h3>
        <pre>
    GET Contains:
    <?php print_r($_GET); ?>
    POST Contains:
    <?php print_r($_POST); ?>
    SESSION Contains:
    <?php if (isset($_SESSION)) print_r($_SESSION); ?>
        </pre>
    </aside>
    <script src="script.js"></script>
</body>
</html>

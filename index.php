<?php 
  include_once('tools.php');
  include_once('post-validation.php');
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
            <li class="navbar" style="float:left"><a href="#head"><h1 class="nav-title">Lunardo Cinema</h1></a></li>
            <li class="navbar navbar-buttons"><a href="#now-showing">Now Showing</a></li>
            <li class="navbar navbar-buttons"><a href="#seats-prices">Seats & Prices</a></li>
            <li class="navbar navbar-buttons"><a href="#about-us">About Us</a></li>
        </ul>
    </nav>
    <main>
        <section id="about-us">
            <h2 class="Heading-title">About Us</h2>
            <p>Welcome to Lunadro Cinema, where the charm of classic cinema meets modern cinematic technology. We're thrilled to announce the grand reopening of our beloved cinema, a cornerstone in the local community to provide for film enthusiasts. After extensive renovations, we're ready to redefine your movie-going experience.</p>
            <p>We understand that movie lovers in our community often travel to larger towns for a modern cinema experience. Lunadro Cinema's upgrade means you no longer need to make that journey. We're bringing the best of contemporary cinema right to your doorstep, combining convenience with quality.</p>
            <p>Dive into the story with our new seating options. Choose from our plush, standard seats or indulge in the ultimate comfort with our reclining first-class seats. Every spot in our cinema is designed to offer an unmatched viewing experience.</p>
            <p>Embrace the future of cinema with our cutting-edge 3D Dolby Vision projection and Dolby Atmos sound systems. Experience movies like never before with immersive visuals and an audio system that envelops you in the action. The clarity, depth, and realism brought by these technologies will transport you right into the heart of the movie.</p>
            <p>Ready for an unparalleled cinematic experience? Visit Lunadro Cinema and witness the transformation. We're excited to share our passion for movies with you and look forward to welcoming both our long-time supporters and new faces.</p>
        </section>
        <section id="seats-prices">
            <h2 class="Heading-title">Seats and Prices</h2>
            <div class="seating">
                <h3>Standard Seating</h3>
                <div class="side-by-side"> 
                    <img src="Profern-Standard-Twin.png" alt="Standard Seating">
                    <table class="prices-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Regular Pricing</th>
                                <th>Discounted Pricing *</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard Adult</td>
                                <td>$21.50</td>
                                <td>$16.00</td>
                            </tr>
                            <tr>
                                <td>Standard Concession</td>
                                <td>$19.00</td>
                                <td>$14.50</td>
                            </tr>
                            <tr>
                                <td>Standard Child</td>
                                <td>$17.50</td>
                                <td>$13.00</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                <h3>First Class Seating</h3>
                <div class="side-by-side">
                    <img src="Profern-Verona-Twin.png" alt="First Class Seating">
                    <table class="prices-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Regular Pricing</th>
                                <th>Discounted Pricing *</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>First Class Adult</td>
                                <td>$31.00</td>
                                <td>$25.00</td>
                            </tr>
                            <tr>
                                <td>First Class Concession</td>
                                <td>$28.00</td>
                                <td>$23.50</td>
                            </tr>
                            <tr>
                                <td>First Class Child</td>
                                <td>$25.00</td>
                                <td>$22.00</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                <div>
                    <em class="disclaimer">*Discounts apply weekdays after 12pm</em>
                </div>
        </div>

            
        </section>
        
        <section id="now-showing">
            <h2 class="Heading-title">Now Showing</h2>
            <?php foreach ($moviesObject as $code => $movie): ?>
                <?php generateMoviePanel($code); ?>
            <?php endforeach; ?>
        </section>        
        
    </main>
    <footer>
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
</body>
</html>

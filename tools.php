<?php
  session_start();

  $moviesObject = [
    'ACT' => [
      'title' => 'Godzilla Minus One',
      'rating' => 'PG-13',
      'genre' => 'Action, Adventure, Drama',
      'poster'=> 'godzilla-minusone-poster.jpg',
      'trailer'=> 'https://www.youtube.com/embed/VvSrHIX5a-0?si=FpBvbT7XZXcuwAOJ',
      'summary' => 'Post war Japan is at its lowest point when a new crisis emerges in the form of a giant monster, baptized in the horrific power of the atomic bomb.',
      'screenings' => [
        'MON' => [
          'time' => '6pm',
          'price' => 'DISC'
        ],
        'TUE' => [
          'time' => '6pm',
          'price' => 'FULL'
        ],
        'SAT' => [
          'time' => '9pm',
          'price' => 'FULL'
        ],
        'SUN' => [
          'time' => '9pm',
          'price' => 'FULL'
        ]
      ]
    ],
    'DRM' => [
      'title' => 'Napoleon',
      'rating' => 'R',
      'genre' => 'Action, Adventure',
      'poster'=> 'napoleon-poster.jpg',
      'trailer'=> 'https://www.youtube.com/embed/OAZWXUkrjPc',
      'summary' => 'An epic that details the checkered rise and fall of French Emperor Napoleon Bonaparte and his relentless journey to power through the prism of his addictive, volatile relationship with his wife, Josephine.',
      'screenings' => [
        'MON' => [
          'time' => '9pm',
          'price' => 'DISC'
        ],
        'TUE' => [
          'time' => '9pm',
          'price' => 'FULL'
        ],
        'WED' => [
          'time' => '9pm',
          'price' => 'FULL'
        ],
        'THU' => [
          'time' => '9pm',
          'price' => 'FULL'
        ],
        'FRI' => [
          'time' => '9pm',
          'price' => 'FULL'
        ],
        'SAT' => [
          'time' => '6pm',
          'price' => 'FULL'
        ],
        'SUN' => [
          'time' => '6pm',
          'price' => 'FULL'
        ]
      ]
    ],
    'RMT' => ['title' => 'Cat Person',
    'rating' => 'R',
    'genre' => 'Drama, Horror, Thriller',
    'poster'=> 'catperson-poster.jpg',
    'trailer'=> 'https://www.youtube.com/embed/ktOBY8aHX2k?si=JoShcJa3CIj6LrTB',
    'summary' => 'When Margot, a college sophomore goes on a date with the older Robert, she finds that IRL Robert doesnt live up to the Robert she has been flirting with over texts. A razor-sharp exploration of the horrors of dating.',
    'screenings' => [
      'WED' => [
        'time' => '12pm',
        'price' => 'FULL'
      ],
      'THU' => [
        'time' => '12pm',
        'price' => 'FULL'
      ],
      'FRI' => [
        'time' => '12pm',
        'price' => 'FULL'
      ],
      'SAT' => [
        'time' => '3pm',
        'price' => 'FULL'
      ],
      'SUN' => [
        'time' => '3pm',
        'price' => 'FULL'
      ]
    ]],
    'FAM' => ['title' => 'Wonka',
    'rating' => 'PG',
    'genre' => 'Musical, Fantasy',
    'poster'=> 'wonka-poster.jpg',
    'trailer'=> 'https://www.youtube.com/embed/otNh9bTjXWg?si=wJFN9UF5_w8FEaG3',
    'summary' => 'With dreams of opening a shop in a city renowned for its chocolate, a young and poor Willy Wonka discovers that the industry is run by a cartel of greedy chocolatiers.',
    'screenings' => [
      'MON' => [
        'time' => '12pm',
        'price' => 'DISC'
      ],
      'WED' => [
        'time' => '6pm',
        'price' => 'FULL'
      ],
      'THU' => [
        'time' => '6pm',
        'price' => 'FULL'
      ],
      'FRI' => [
        'time' => '6pm',
        'price' => 'FULL'
      ],
      'SAT' => [
        'time' => '12pm',
        'price' => 'FULL'
      ],
      'SUN' => [
        'time' => '12pm',
        'price' => 'FULL'
      ]
    ]]
    
  ];

if (!function_exists('generateMoviePanel')) {
  function generateMoviePanel($movieCode) {
    global $moviesObject; 
        if (!isset($moviesObject[$movieCode])) {
            echo "Movie code does not exist.";
            return; 
        }
    $movie = $moviesObject[$movieCode];
    echo "<div class='movie-panel' tabindex='0'>";
    echo "<div class='panel-front'>";
    echo "<img src='" . $movie['poster'] . "' alt='" . htmlspecialchars($movie['title']) . "'>";
    echo "<div class='movie-info'>";
    echo "<h2>" . htmlspecialchars($movie['title']) . "</h2>";
    echo "<p>" . htmlspecialchars($movie['rating']) . "</p>";
    echo "</div>";
    echo "</div>";
    echo "<div class='panel-back'>";
    echo "<p>" . htmlspecialchars($movie['summary']) . "</p>";
    echo "<ul class='screening-times'>";
    foreach ($movie['screenings'] as $day => $screening) {
        $time = $screening['time'];
        echo "<li>" . htmlspecialchars($day) . ": " . htmlspecialchars($time) . "</li>";
    }
    echo "</ul>";
    echo "<div class='booking-button-container'>";
    echo "<a class='link-button book-now-btn' href='booking.php?movie=" . urlencode($movieCode) . "'>BookNow</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

  }
}

if (!function_exists('getSeatPrice')) {
  function getSeatPrice($seatType, $day, $time, $movieCode) {

    $basePrices = [
        'STA' => 21.50, // Standard Adult
        'STP' => 19.00, // Standard Concession
        'STC' => 17.50, // Standard Child
        'FCA' => 31.00, // First Class Adult
        'FCP' => 28.00, // First Class Concession
        'FCC' => 25.00, // First Class Child
    ];
  
    $discountedPrices = [
        'STA' => 16.00,
        'STP' => 14.50,
        'STC' => 13.00,
        'FCA' => 25.00,
        'FCP' => 23.50,
        'FCC' => 22.00,
    ];
  
    $weekdays = ['MON', 'TUE', 'WED', 'THU', 'FRI'];
    $isWeekday = in_array($day, $weekdays);
  
    $isAfternoon = isAfternoon($time);
  
    if ($isWeekday && $isAfternoon) {
        $price = $discountedPrices[$seatType] ?? 0;
    } else {
        $price = $basePrices[$seatType] ?? 0;
    }
  
    return $price;
  }

  // Helper function to determine if the time is after 12pm
  function isAfternoon($time) {
    $timeIn24Hour = date("H", strtotime($time));
    return $timeIn24Hour >= 12;
  }
}



?>

<?php 
    include('server.php');
    include('login.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlashLite</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles_header.css">
</head>
<body>
    <?php 
        // echo "using user id: ".$_SESSION['user_id'];
        echo $_SESSION['email'];
    ?> 
    <div class="container">
        <nav class="navbar">
            <div class="navbar_toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul class="navbar-menu">
                <li class="navbar_item">
                    <button class="edit_btn navbar_btn">Edit</button>
                </li>
                <li class="navbar_item">
                    <a id = "quiz" href="quiz/quiz.php?mode=quiz">
                        <button class="quiz navbar_btn">Quiz</button>
                    </a>
                </li>
                <li class="navbar_item">
                    <button class="show-hide-all navbar_btn">Show/Hide All</button>
                </li>
                <li class="navbar_item">
                    <button class="navbar_btn" id="add-flashcard">Add Flashcard</button>
                </li>
            </ul>
        </nav>

        <!-- Display Card of Question and Answers Here -->
        <div id="card-con">
            <div class="card-list-container"></div>
        </div>
    </div>

    <!-- Input form for users to fill question and answer-->
    <div class="question-container hide" id="add-question-card">
        <h2>Add Flashcard</h2>
        <div class="wrapper">
            <!-- Error message -->
            <div class="error-con">
                <span class="hide" id="error">
                    Input fields cannot be empty!
                </span>
            </div>
            <!-- Close Button -->
            <i class="fa-solid fa-xmark" id="close-btn"></i>
        </div>

        <label for="question">Question:</label>
        <textarea class="input" id="question"
        placeholder="Type the question here..." rows="2"></textarea>
        <label for="answer">Answer:</label>
        <textarea class="input" id="answer" rows="4"
        placeholder="Type the answer here..."></textarea>
        <button id="save-btn">Save</button>
    </div>

    <!-- Empty Container -->
    <div class="empty-display">
        <i class="fa fa-circle-plus"></i>
        <h2>Add a Flashcard</h2>
        <p>No flashcards added yet silly goose :)</p>
    </div>

    <!-- Delete Confimation Message -->
    <div class="delete-container hide" id="delete-question-card">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <h2>Are you sure?</h2>
        <p>You won't be able to revert this.</p>
        <div class="delete-btn-con">
            <button class="delete-item" id="yes">Yes, delete it!</button>
            <button class="delete-item" id="no">Cancel</button>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer_container">
        <div class="social_media">
            <div class="social_media-wrap">
                <p class="website_rights"></p>
                <div class="social_icons">
                    <a href="https://www.tiktok.com/@nathan_florendo?_t=8myaliZR5h9&_r=1" class="social_icon-link" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://www.instagram.com/nathan_florendo?igsh=MTRpdnY4YjVlaDNzeA%3D%3D&utm_source=qr" class="social_icon-link" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://github.com/natflorendo" class="social_icon-link" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/nathan-florendo-435b54213?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" class="social_icon-link" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://youtube.com/@bleh8106?si=RslB-YDJsi2f9D06" class="social_icon-link" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js" defer></script>
</body>
</html>
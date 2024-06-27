<?php 
    include('server.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlashLite Quiz</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="quiz.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php
    $mode = "quiz";
    if(isset($_GET['mode'])) {
        $mode = $_GET['mode'];
    }

    //something is posted
    if(count($_POST) > 0) {
        switch($mode) {
            case 'quiz':
                break; 
            case 'quiz_end':
                break;   
        }
    }
?>
<?php
    switch($mode) {
    case 'quiz':
    ?>
     <div class="container">
        <div class="hud">
            <a id ="home" href="../app.php">
                <button class="home-btn">Back</button>
            </a>
            <div class="hud-item">
                <div class="progress-bar">
                    <div class="progress-bar-full"></div>
                </div>
                <div class="hud-prefix">
                    <p class="progress-text ">Progress</p>
                    <p class="progress-percent">0%</p>
                </div>
            </div>
            <div class="hud-item">
                <p class="hud-prefix">Correct</p>
                <p class="hud-main-text score">0/0</p>
            </div>
        </div>
        <h1 id="question">What is the answer to this question?</h1>
            
        <input type="text" id="answer" name="answer" autocomplete="off"
        placeholder="Type here"/>
        <span>Answer</span>
        <button type="button" class="sendAnswer" name="sendAnswer"><i class="fas fa-paper-plane"></i></button>
        <form method="post" action="quiz.php?mode=quiz">    
            <button type="submit" class="restartQuiz" name="restartQuiz">Restart Quiz</button>
        </form>
        <form method="post" action="quiz.php?mode=quiz_end" class="hide">    
            <button type="submit" class="finishedQuiz" name="finishedQuiz"></button>
        </form>
        <img>
        <div class="feedback-container hide">
            <div class="inner-container">
                <label class="correct-label">CORRECT</label>    
                <div class="correct-container"></div>
            </div>
            <div class="inner-container">
                <label class="incorrect-label">INCORRECT</label>
                <div class="incorrect-container"></div>
            </div>
        </div>
    </div>
<?php
        break;
    case 'quiz_end':
    ?>
    <div class="container">
        <div class="hud">
            <a id ="home" href="../app.php">
                <button class="home-btn">Back</button>
            </a>
            <div class="hud-item">
                <div class="progress-bar">
                    <div class="progress-bar-full"></div>
                </div>
                <div class="hud-prefix">
                    <p class="progress-text ">Progress</p>
                    <p class="progress-percent">0%</p>
                </div>
            </div>
            <div class="hud-item">
                <p class="hud-prefix">Correct</p>
                <p class="hud-main-text score">0/0</p>
            </div>
        </div>
        <h1 id="final">Final Score</h1>
        <h2 id="percentCorrect"></h2>
        <img src="hiddenMickey.png" alt="" class="hide">
        <div class="feedback-container hide">
            <div class="inner-container">
                <label class="correct-label">CORRECT</label>    
                <div class="correct-container"></div>
            </div>
            <div class="inner-container">
                <label class="incorrect-label">INCORRECT</label>
                <div class="incorrect-container"></div>
            </div>
        </div>
        <button type="button" class="sendAnswer hide" name="sendAnswer"><i class="fas fa-paper-plane"></i></button>
        <form method="post" action="quiz.php?mode=quiz">    
            <button type="submit" class="restartQuiz restartQuiz2" name="restartQuiz2">Restart Quiz</button>
        </form>
    </div>
<?php
    }
    ?>


    <!-- Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="quiz.js" defer></script>
</body>
</html>
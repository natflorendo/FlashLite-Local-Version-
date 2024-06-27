<?php

session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'flashcardDB');

if(isset($_POST['newCard'])) {
    $newCard = $_POST['newCard'];
    $question = $newCard['question'];
    $answer = $newCard['answer'];
    $user_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO flashcards (user_id, question, answer)
                VALUES('$user_id', '$question', '$answer')";
    mysqli_query($db, $query);
    lowestID();
    echo "newCard: ".$user_id.", ".$question.", ".$answer;
}

if(isset($_POST['modifyCard'])) {
    $modifyCard = $_POST['modifyCard'];
    $question = $modifyCard['question'];
    $answer = $modifyCard['answer'];
    $user_id = $_SESSION['user_id'];
    
    //Limit 1 in case of duplicate cards with same question and answer
    $query = "DELETE FROM flashcards WHERE user_id='$user_id'
                AND question='$question' AND answer='$answer' LIMIT 1";
    mysqli_query($db, $query);
    lowestID();
    echo "edit/delete success";
}

if(isset($_GET['requested'])) {
    $user_id = $_SESSION['user_id'];

    lowestID();

    $query = "SELECT * FROM flashcards WHERE user_id='$user_id'";
    $results = mysqli_query($db, $query);

    $flashcards = array();
    while($row = mysqli_fetch_assoc($results)) {
        $flashcards[] = $row;
    }

    echo json_encode($flashcards);
}

if(isset($_POST['changeIndexes'])) {
    $changeIndexes = $_POST['changeIndexes'];
    $oldIndex = $changeIndexes['oldIndex'];
    $newIndex = $changeIndexes['newIndex'];
    $user_id = $_SESSION['user_id'];

    //another user could delete a card while switching indexes
    lowestID();

    $query = "SELECT * FROM flashcards WHERE user_id='$user_id'";
    $results = mysqli_query($db, $query);

    $flashcards = array();
    while($row = mysqli_fetch_assoc($results)) {
        $flashcards[] = $row;
    }

    // print_r($flashcards);
    //add 1 if old index is smaller than new index, 
    //but subtract 1 if old index is bigger than new index
    $step = ($oldIndex < $newIndex) ? 1 : -1;
    for($i = $oldIndex; $i != $newIndex; $i += $step) {
        //store the original fcard_id of i and newIndex
        $oldCardId = $flashcards[$i]['fcard_id'];
        $newCardId = $flashcards[$newIndex]['fcard_id'];

        //change newIndex fcard_id to be -1 temporarily
        $query = "UPDATE flashcards SET fcard_id=-1
                WHERE fcard_id='$newCardId'
                AND user_id='$user_id'";
        mysqli_query($db, $query);

        //change i fcard_id to be newIndex f_card id;
        $query = "UPDATE flashcards SET fcard_id='$newCardId'
                WHERE fcard_id='$oldCardId'
                AND user_id='$user_id'";
        mysqli_query($db, $query);

        //change the newIndex fcard_id to be i f_card id:
        $query = "UPDATE flashcards SET fcard_id='$oldCardId'
                WHERE fcard_id=-1
                AND user_id='$user_id'";
        mysqli_query($db, $query);

        //swap ids in flashcards array as well
        $temp = $flashcards[$i]['fcard_id'];
        $flashcards[$i]['fcard_id'] = $flashcards[$newIndex]['fcard_id'];
        $flashcards[$newIndex]['fcard_id'] = $temp;
    }
    // print_r($flashcards);
}

function lowestID() {
    global $db;

    //changes id's of existing flashcards to be lowest possible id #.
    //@count variable prevents errors when trying to access an array 
    //element that doesn't exist.
    $query = "SET @count = 0";
    mysqli_query($db, $query);
    //Remove auto-increment temporarily
    $query = "ALTER TABLE flashcards MODIFY fcard_id INT NOT NULL";
    mysqli_query($db, $query);
    //re-sequence the ID's starting from 1.
    //@count:=@count + 1 means that count increments 
    //by 1 for each row processed.
    $query = "UPDATE flashcards SET fcard_id=@count:=@count + 1";
    mysqli_query($db, $query);
    //Add auto-increment attrubute back
    $query = "ALTER TABLE flashcards MODIFY fcard_id INT NOT NULL AUTO_INCREMENT";
    mysqli_query($db, $query);
}

// QUIZ SECTION /////////////////////////
if(isset($_POST['quizAnswer'])) {
    $quizAnswer = $_POST['quizAnswer'];
    $question = $quizAnswer['question']; $answer = $quizAnswer['answer'];
    $correct = $quizAnswer['correct']; $qanswered = $quizAnswer['qanswered'];
    $user_id = $_SESSION['user_id'];

    $correct = $correct ? 1 : 0;
    $qanswered = $qanswered ? 1 : 0;

     //Limit 1 in case of duplicate cards with same question and answer
    $query = "UPDATE flashcards SET correct='$correct', qanswered='$qanswered'
    WHERE user_id='$user_id' AND question='$question' AND answer='$answer' LIMIT 1";
    mysqli_query($db, $query);
}

if(isset($_POST['restartQuiz'])) {
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE flashcards SET correct='0', qanswered='0'
              WHERE user_id='$user_id'";
    mysqli_query($db, $query);
}

?>
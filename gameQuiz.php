<?php 

session_start();
ob_start();

include("connection.php");

$sessionID = $_SESSION['session_user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="css/quizGame.css">
    <link rel="stylesheet" href="css/mobileUI.css">

</head>
<body>
<?php
    if (isset($_GET['e'])) {
        $msg = htmlspecialchars($_GET['e']);
        echo "
        <div class='eMsg' id='alertpopup'>
            <p class='msg'>$msg</p>
        </div>
        ";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { alertpopup(); });</script>";
    }
    ?>
    <script>
        function alertpopup() {
            setTimeout(function(){
                const popup = document.getElementById('alertpopup');
                popup.classList.add('showEMsg');
                setTimeout(function(){
                    popup.classList.remove('showEMsg');
                },1500 );
            },50);     
        }
    </script>
    <a href="quiz.php">
        <img src="img/back.svg" alt="" class="backbutton">
    </a>
    <div class="playerScore">Score:<span id="playerscore"></span></div>
    <div class="quizContent">
    <?php
        
    $selectQry = "SELECT * FROM tbl_quizzes ORDER BY rand() LIMIT 1";    
    $result = $connectDB->query($selectQry);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    }
    
    ?>
        <div class="qQuestion"><?php echo $row['question']; ?></div>
        
        <div class="qDrop" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
        <div class="qAnswerbox">
            <div class="answer"  id='<?php echo $row['answer1']; ?>' draggable="true" ondragstart="drag(event)"
            ontouchstart="touchStart(event)" ontouchmove="touchMove(event)" ontouchend="touchEnd(event)">
                <?php echo $row['answer1']; ?>
            </div>
            <div class="answer"  id='<?php echo $row['answer2']; ?>' draggable="true" ondragstart="drag(event)"
            ontouchstart="touchStart(event)" ontouchmove="touchMove(event)" ontouchend="touchEnd(event)">
                <?php echo $row['answer2']; ?>
            </div>
            <div class="answer"  id='<?php echo $row['answer3']; ?>' draggable="true" ondragstart="drag(event)"
            ontouchstart="touchStart(event)" ontouchmove="touchMove(event)" ontouchend="touchEnd(event)">
                <?php echo $row['answer3']; ?>
            </div>
            <div class="answer" id='answer4' draggable="true" ondragstart="drag(event)"
            ontouchstart="touchStart(event)" ontouchmove="touchMove(event)" ontouchend="touchEnd(event)">
                <?php echo $row['answer4']; ?>
            </div>
        </div>
    </div>
    <form action="checkQuiz.php" method="POST" id="quizForm">
        <input type="hidden" name="QID" value="<?php echo $row['QID']; ?>" id="QIDcontainer">
        <input type="hidden" name="userAnswer" value="" id="AnswerContainer">
        <input type="hidden" name="score" value="" id="scoreinput">
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
        const playerScore = <?php echo isset($_SESSION['current_score']) ? $_SESSION['current_score'] : 0; ?>;
        
        const scoreContainer = document.getElementById('playerscore');
        const scoreinput = document.getElementById('scoreinput');
        
        scoreContainer.innerHTML = playerScore;
        scoreinput.value = playerScore;
        
        sessionStorage.setItem('score', playerScore);
    });

    let selectedElement = null;
    let originalPosition = null;

    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        const draggedAnswerId = event.target.id;
        event.dataTransfer.setData("text", draggedAnswerId);
    }

    function drop(event) {
        event.preventDefault();
        const answerId = event.dataTransfer.getData("text");
        const draggedElement = document.getElementById(answerId);
        const targetBox = event.target;

        if (targetBox.classList.contains('qDrop')) {
            const answerContainer = document.getElementById('AnswerContainer');
            answerContainer.value = answerId;
            targetBox.appendChild(draggedElement);
            
            draggedElement.style.position = '';
            draggedElement.style.left = '';
            draggedElement.style.top = '';
            
            document.getElementById('quizForm').submit();
        }
    }

    function touchStart(event) {
        selectedElement = event.target;
        
        if (selectedElement.classList.contains('answer')) {
            event.preventDefault();
            
            originalPosition = {
                left: selectedElement.style.left,
                top: selectedElement.style.top,
                position: selectedElement.style.position
            };
            
            selectedElement.style.position = 'fixed';
            const touch = event.touches[0];
            selectedElement.style.left = `${touch.pageX - selectedElement.offsetWidth / 2}px`;
            selectedElement.style.top = `${touch.pageY - selectedElement.offsetHeight / 2}px`;
            
            selectedElement.classList.add('dragging');
        }
    }

    function touchMove(event) {
        if (selectedElement && selectedElement.classList.contains('answer')) {
            event.preventDefault();
            const touch = event.touches[0];
            
            selectedElement.style.left = `${touch.pageX - selectedElement.offsetWidth / 2}px`;
            selectedElement.style.top = `${touch.pageY - selectedElement.offsetHeight / 2}px`;
        }
    }

    function touchEnd(event) {
        if (selectedElement) {
            const touch = event.changedTouches[0];
            const dropTarget = document.elementFromPoint(touch.clientX, touch.clientY);

            if (dropTarget && dropTarget.classList.contains('qDrop')) {
                const answerContainer = document.getElementById('AnswerContainer');
                answerContainer.value = selectedElement.id;
                dropTarget.appendChild(selectedElement);
                
                selectedElement.style.position = '';
                selectedElement.style.left = '';
                selectedElement.style.top = '';
                
                document.getElementById('quizForm').submit();
            } else {
                selectedElement.style.position = originalPosition.position;
                selectedElement.style.left = originalPosition.left;
                selectedElement.style.top = originalPosition.top;
            }
            
            selectedElement.classList.remove('dragging');
            selectedElement = null;
            originalPosition = null;
        }
    }
    </script>

</body>
</html>
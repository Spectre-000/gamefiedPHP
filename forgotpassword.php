<?php 
include("connection.php");
session_start();
ob_start();



if(isset($_SESSION['session_user'])){
    $sessionUsername = $_SESSION['session_user'];
    $sessionID = $_SESSION['session_user_id'];
    if($sessionUsername == "admin"){
        header("location: adminhome.php?e=" . urlencode("No need to log in"));
        exit();
    }else {
        header("location: homepage.php?e=" . urlencode("No need to log in"));
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <!-- <link rel="stylesheet" href="css/ui.css"> -->
    <link rel="stylesheet" href="css/mobileUI.css">
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
            --error-color: #ff4444;
            --success-color: #00C851;
            --card-bg: white;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #fff 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: var(--text-color);
            animation: fadeInDown 0.8s ease;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: var(--primary-color);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header i {
            font-size: 1.2em;
            color: var(--text-color);
            opacity: 0.8;
        }

        .wrapper {
            width: 100%;
            max-width: 400px;
            perspective: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .card-switch {
            position: relative;
            width: 100%;
            height: 450px;
        }

        .switch {
            position: relative;
            display: block;
            width: 100%;
            height: 100%;
        }

        .toggle {
            display: none;
        }

        .flip-card__inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
        }


        .flip-card__front,
        .flip-card__back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            background: var(--card-bg);
            padding: 40px;
            border-radius: 20px;
        }

        .flip-card__back {
            transform: rotateY(180deg);
        }

        .title {
            font-size: 2em;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 30px;
        }

        .flip-card__form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .flip-card__input {
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1em;
            transition: all 0.3s;
            outline: none;
        }

        .flip-card__input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(237, 99, 70, 0.2);
        }

        .flip-card__btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .flip-card__btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(237, 99, 70, 0.3);
        }

        .flip-card__btn:active {
            transform: translateY(0);
        }

        .eMsg {
            position: fixed;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--error-color);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: top 0.3s ease;
            z-index: 1000;
        }

        .eMsg.showEMsg {
            top: 20px;
        }

        .msg {
            font-size: 1em;
            font-weight: 500;
        }


        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8em;
            }

            .header i {
                font-size: 1em;
            }

            .wrapper {
                padding: 10px;
            }

            .flip-card__front,
            .flip-card__back {
                padding: 20px;
            }

            .title {
                font-size: 1.6em;
                margin-bottom: 20px;
            }
        }
    </style>
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
    <div class="header">
        <h1>GAMEFIED CODING LEARNING</h1>
        <i>In PHP web application</i>
    </div>
    <div class="wrapper">
        <div class="card-switch">
            <label class="switch">
               <input type="checkbox" class="toggle">
               <span class="slider"></span>
               <span class="card-side"></span>
               <div class="flip-card__inner">
                  <div class="flip-card__front">
                     <div class="title">Forgot Password</div>
                     <form class="flip-card__form" action="validateEmail.php" method="post">
                        <input class="flip-card__input" name="email" id="email" placeholder="Enter email address" type="email" required>
                        <input class="flip-card__input" id="confirmEmail" placeholder="Re-Enter email address" type="email" required>
                        <button class="flip-card__btn" id="submitBtn">Proceed</button>
                        <button type="button" onclick="window.location.href='index.php'" class="flip-card__btn" id="submitBtn">Go back</button>
                     </form>
                  </div>
               </div>
            </label>
        </div>   
   </div>
   <script>
        function alertpopup() {
            setTimeout(function(){
                const popup = document.getElementById('alertpopup');
                popup.classList.add('showEMsg');
                setTimeout(function(){
                    popup.classList.remove('showEMsg');
                }, 2000);
            }, 100);     
        }
        document.addEventListener('DOMContentLoaded', function() {
            const email = document.getElementById('email');
            const confirmEmail = document.getElementById('confirmEmail');
            const submitBtn = document.getElementById('submitBtn');

            function validateEmails() {
                if (email.value === confirmEmail.value) {
                    submitBtn.disabled = false; 
                    submitBtn.innerHTML = "Proceed";
                    submitBtn.style.background = "var(--primary-color)";
                    submitBtn.style.color = "#fff";
                } else {
                    submitBtn.disabled = true; 
                    submitBtn.style.background = "#ccc";
                    submitBtn.style.color = "#DC3545";
                    submitBtn.innerHTML = "Email Must Match";
                }
            }
            confirmEmail.addEventListener('input', validateEmails);
        });

    </script>
</body>
</html>
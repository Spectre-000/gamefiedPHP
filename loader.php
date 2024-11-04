<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading</title>
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            background: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .upper, .lower {
            position: fixed;
            left: 0;
            width: 100%;
            height: 40vh;
            background: var(--primary-color);
            transition: height 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }

        .upper {
            top: 0;
            transform-origin: top;
            z-index: 100;
        }

        .lower {
            bottom: 0;
            transform-origin: bottom;
        }

        .middle {
            position: fixed;
            left: 0;
            top: 40vh;
            width: 100%;
            height: 20vh;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }

        .logo-container {
            position: relative;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .midLogo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .loading-ring {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 3px solid rgba(237, 99, 70, 0.1);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 20px;
            color: var(--primary-color);
            font-size: 1.2em;
            font-weight: 500;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards 1s;
        }

        .loading-dots {
            display: inline-block;
            width: 20px;
            text-align: left;
        }

        .loading-dots::after {
            content: '.';
            animation: dots 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(237, 99, 70, 0.4);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(237, 99, 70, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(237, 99, 70, 0);
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60% { content: '...'; }
            80%, 100% { content: ''; }
        }

        /* Progress bar */
        .progress-bar {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 4px;
            background: rgba(237, 99, 70, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: var(--primary-color);
            transition: width 7s linear;
        }

        @media (max-width: 768px) {
            .midLogo {
                width: 100px;
                height: 100px;
            }

            .loading-ring {
                width: 130px;
                height: 130px;
            }

            .loading-text {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="upper" id="up"></div>
    <div class="middle" id="mid">
        <div class="logo-container">
            <div class="loading-ring"></div>
            <img src="img/logo.jpg" alt="" class="midLogo">
        </div>
        <div class="loading-text">
            Loading<span class="loading-dots"></span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progress"></div>
        </div>
    </div>
    <div class="lower" id="low"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const top = document.getElementById('up');
            const mid = document.getElementById('mid');
            const bot = document.getElementById('low');
            const progress = document.getElementById('progress');

            setTimeout(function(){
                // Start the animation
                top.style.height = "20vh";
                bot.style.height = "20vh";
                mid.style.height = "60vh";
                
                // Start progress bar
                progress.style.width = "100%";

                setTimeout(function(){
                    // Prepare for exit animation
                    top.style.transition = "transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)";
                    bot.style.transition = "transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)";
                    mid.style.opacity = "0";
                    
                    // Exit animation
                    setTimeout(function() {
                        top.style.transform = "translateY(-100%)";
                        bot.style.transform = "translateY(100%)";
                        
                        // Redirect after exit animation
                        setTimeout(function() {
                            location.href = "homepage.php";
                        }, 800);
                    }, 200);
                }, 6000);
            }, 1000);
        });
    </script>
</body>
</html>
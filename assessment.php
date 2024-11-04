<?php 
session_start();
ob_start();

include("connection.php");

if (!isset($_SESSION['session_user_id'])) {
    header("Location: login.php");
    exit();
}

$sessionID = $_SESSION['session_user_id'];

$selectQry = "SELECT ur.*, u.username 
              FROM tbl_userrank ur 
              JOIN tbl_users u ON ur.UID = u.UID 
              WHERE ur.UID = ?";
$stmt = $connectDB->prepare($selectQry);
$stmt->bind_param('i', $sessionID);
$stmt->execute();
$result = $stmt->get_result();

// Default values
$rank = "Newbie";
$level = 0;
$username = "";
$rankColor = "#64B5F6"; 
$rankIcon = "🌱";    

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rankNum = $row['rank'] ?? 1; 
    $level = $row['level'] ?? 0;  
    $username = $row['username'] ?? "User";
    
    // Assign rank details based on rank number
    switch($rankNum) {
        case 1:
            $rank = "Newbie";
            $rankColor = "#64B5F6"; // Light Blue
            $rankIcon = "🌱";
            break;
        case 2:
            $rank = "Intermediate";
            $rankColor = "#81C784"; // Light Green
            $rankIcon = "⭐";
            break;
        case 3:
            $rank = "Advanced";
            $rankColor = "#BA68C8"; // Purple
            $rankIcon = "🎓";
            break;
        default:
            break;
    }
}

// Ensure level is a non-negative integer
$level = max(0, intval($level));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment - <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="css/mobileUI.css">
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
            --border-radius: 15px;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .backbutton {
            width: 30px;
            height: 30px;
            margin-right: 20px;
            transition: transform 0.3s ease;
        }

        .backbutton:hover {
            transform: translateX(-5px);
        }

        .assessmentStats {
            background: linear-gradient(135deg, var(--primary-color), #ff8566);
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .aTitle {
            color: white;
            font-size: 1.5em;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .stats {
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: grid;
            grid-gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--secondary-color);
            padding: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-title {
            color: var(--text-color);
            font-size: 0.9em;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 1.8em;
            font-weight: bold;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .rank-icon {
            font-size: 1.5em;
        }

        .progress-bar {
            background: #e0e0e0;
            height: 10px;
            border-radius: 5px;
            margin-top: 15px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary-color);
            width: <?php echo ($level % 10) * 10; ?>%;
            transition: width 0.3s ease;
        }

        .startBtn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.2em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .startBtn:hover {
            background: #ff8566;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        a {
            text-decoration: none;
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .aTitle {
                font-size: 1.2em;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-value {
                font-size: 1.5em;
            }
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Changed to space-between for better spacing */
            margin-bottom: 30px;
            padding: 10px 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .backbutton {
            width: 30px;
            height: 30px;
            transition: transform 0.3s ease;
        }

        .backbutton:hover {
            transform: translateX(-5px);
        }

        .quiz-button {
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .quiz-button:hover {
            background: #ff8566;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .quiz-button .arrow {
            font-size: 1.2em;
            transition: transform 0.3s ease;
        }

        .quiz-button:hover .arrow {
            transform: translateX(5px);
        }

        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-left {
                width: 100%;
                justify-content: flex-start;
            }

            .quiz-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <a href="homepage.php">
                    <img src="img/back.svg" alt="Back" class="backbutton">
                </a>
                <h1>Assessment Center</h1>
            </div>
            <a href="quiz.php" class="quiz-button">
                Take Quiz <span class="arrow">→</span>
            </a>
        </div>

        <div class="assessmentStats">
            <span class="aTitle">SKILL ASSESSMENT</span>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-title">Current Rank</div>
                <div class="stat-value" style="color: <?php echo $rankColor; ?>">
                    <span class="rank-icon"><?php echo $rankIcon; ?></span>
                    <?php echo htmlspecialchars($rank); ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-title">Progress Level</div>
                <div class="stat-value"><?php echo $level; ?></div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div style="font-size: 0.8em; margin-top: 5px; color: #666;">
                    <?php echo ($level % 10) * 10; ?>% to next rank
                </div>
            </div>
        </div>

        <a href="gameAssessment.php">
            <button class="startBtn">Start Assessment</button>
        </a>
    </div>
</body>
</html>
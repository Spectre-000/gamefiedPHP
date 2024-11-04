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
    <title>Quiz Leaderboard</title>
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .backbutton {
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .backbutton:hover {
            transform: scale(1.1);
        }

        .leaderboard {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .leaderboard h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: var(--primary-color);
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: var(--secondary-color);
        }

        .current-user {
            background-color: rgba(237, 99, 70, 0.1) !important;
            font-weight: bold;
        }

        .quizStart {
            display: block;
            margin: 20px auto;
            padding: 12px 30px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
        }

        .quizStart:hover {
            transform: scale(1.05);
            background-color: #d94f32;
        }

        @media (max-width: 600px) {
            .leaderboard {
                padding: 10px;
            }

            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <a href="adminhome.php">
        <img src="img/back.svg" alt="" class="backbutton">
    </a>
    
    <?php
    // Get Top 100 Scores
    $topScoresQuery = "SELECT l.*, u.username, r.rank 
                        FROM tbl_leaderboards l
                        JOIN tbl_users u ON l.UID = u.UID
                        JOIN tbl_userrank r ON l.UID = r.UID
                        ORDER BY l.score DESC
                        LIMIT 100;
                        ";
    $topResult = $connectDB->query($topScoresQuery);
    
    ?>

    <div class="leaderboard">
        <h2>Top 100 Players</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if($topResult->num_rows > 0) {
                    $rank = 1;
                    while($row = $topResult->fetch_assoc()) {
                        $isCurrentUser = ($row['UID'] == $sessionID) ? 'current-user' : '';
                        if($row['rank'] == 1){
                            $title = "Newbie";
                        }elseif($row['rank'] == 2){
                            $title = "Advanced";
                        }elseif($row['rank'] == 3){
                            $title = "Professional";
                        }elseif($row['rank'] == 4){
                            $title = "Master";
                        }else {
                            $title = "No title";
                        }
                ?>
                    <tr class="<?php echo $isCurrentUser; ?>">
                        <td>#<?php echo $rank; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($title); ?></td>
                        <td><?php echo number_format($row['score']); ?></td>
                    </tr>
                <?php
                        $rank++;
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="3">No scores yet!</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
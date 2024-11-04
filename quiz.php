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
    <link rel="stylesheet" href="css/mobileUI.css">
    <link rel="stylesheet" href="css/quiz.css">
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
    <div class="header">
        <div class="header-left">
            <a href="homepage.php">
                <img src="img/back.svg" alt="Back" class="backbutton">
            </a>
            <h2>Quiz</h2>
        </div>
        <div class="hamburgerMenu" id="sidebarBtn">
            <a href="assessment.php" style="text-decoration: none; color: #fff;">
                Take Assessment <span class="arrow">→</span>
            </a>
        </div>
    </div>
    <?php
// Get Top 10 Scores
$topScoresQuery = "SELECT l.*, u.username 
                  FROM tbl_leaderboards l
                  JOIN tbl_users u ON l.UID = u.UID
                  ORDER BY l.score DESC
                  LIMIT 10";
$topResult = $connectDB->query($topScoresQuery);

// Get Current User's Rank
$userRankQuery = "SELECT rank 
                 FROM (
                     SELECT UID, 
                            score,
                            DENSE_RANK() OVER (ORDER BY score DESC) as rank
                     FROM tbl_leaderboards
                 ) ranked 
                 WHERE UID = ?";
$stmt = $connectDB->prepare($userRankQuery);
$stmt->bind_param('i', $sessionID);
$stmt->execute();
$rankResult = $stmt->get_result();
$userRank = $rankResult->fetch_assoc();
?>


<!-- Display Current User's Rank -->
<div class="userRank">
    Your Rank: <?php echo isset($userRank['rank']) ? "#" . $userRank['rank'] : "Not Ranked"; ?>
</div>

<!-- Display Top 10 Leaderboard -->
<div class="leaderboard">
    <h2>Top 10 Players</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($topResult->num_rows > 0) {
                $rank = 1;
                while($row = $topResult->fetch_assoc()) {
                    $isCurrentUser = ($row['UID'] == $sessionID) ? 'current-user' : '';
            ?>
                <tr class="<?php echo $isCurrentUser; ?>">
                    <td>#<?php echo $rank; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
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
    <a href="gameQuiz.php">
        <button class="quizStart">Start</button>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sidebar = document.getElementById('sidebar');
            const sidebarBtn = document.getElementById('sidebarBtn');
            const returnBtn = document.getElementById('returnBtn');
            
            sidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('showsidebar');
                sidebarBtn.classList.add('active');
            });
            
            returnBtn.addEventListener('click', () => {
                sidebar.classList.remove('showsidebar');
                sidebarBtn.classList.remove('active');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !sidebarBtn.contains(e.target) && sidebar.classList.contains('showsidebar')) {
                    sidebar.classList.remove('showsidebar');
                    sidebarBtn.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
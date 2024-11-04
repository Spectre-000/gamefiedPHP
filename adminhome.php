<!-- <?php
// Assuming you have a database connection
include("connection.php");

// Function to get statistics
function getStatistics($connectDB) {
    $stats = array();
    
    $query = "SELECT COUNT(*) as total FROM tbl_users";
    $result = mysqli_query($connectDB, $query);
    $stats['users'] = mysqli_fetch_assoc($result)['total'];
    

    $query = "SELECT COUNT(*) as total FROM tbl_quizzes";
    $result = mysqli_query($connectDB, $query);
    $stats['quizzes'] = mysqli_fetch_assoc($result)['total'];
    
    $query = "SELECT COUNT(*) as total FROM tbl_assessments";
    $result = mysqli_query($connectDB, $query);
    $stats['assessments'] = mysqli_fetch_assoc($result)['total'];
    
    
    $query = "SELECT l.*, u.username 
                      FROM tbl_leaderboards l
                      JOIN tbl_users u ON l.UID = u.UID
                      ORDER BY l.score DESC
                      LIMIT 5";
    $result = mysqli_query($connectDB, $query);
    $stats['top_performers'] = array();
    while($row = mysqli_fetch_assoc($result)) {
        $stats['top_performers'][] = $row;
    }
    
    return $stats;
}

$statistics = getStatistics($connectDB);
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamefied Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="sidebar">
        <div class="gameTitle">
            <p>GAMEFIED</p>
        </div>
        <div class="sideNavs">
            <ul>
            <a href="adminhome.php"><li><i class="fas fa-home"></i> Dashboard</li></a>
                <a href="adminFeedback.php"><li><i class="fas fa-comment-dots"></i> Feedbacks</li></a>
                <a href="adminAssessment.php"><li><i class="fas fa-tasks"></i> Assessments</li></a>
                <a href="adminQuiz.php"><li><i class="fas fa-question-circle"></i> Quizzes</li></a>
                <a href="adminUser.php"><li><i class="fas fa-users"></i> Users</li></a>
                <a href="adminLeaderboard.php"><li><i class="fas fa-trophy"></i> Leaderboards</li></a>
                <a href="homepage.php"><li><i class="fas fa-check-circle"></i> User Page</li></a>
                <a href="homepage.php"><li><i class="fas fa-sign-out"></i> Sign Out</li></a>
            </ul>
        </div>
    </div>

    <div class="mainContent">
        <div class="topbar">
            <h2>Admin Dashboard</h2>
        </div>

        <div class="statsGrid">
            <div class="statCard">
                <i class="fas fa-users"></i>
                <div class="statNumber"><?php echo $statistics['users']; ?></div>
                <div class="statLabel">Total Users</div>
            </div>
            
            <div class="statCard">
                <i class="fas fa-question-circle"></i>
                <div class="statNumber"><?php echo $statistics['quizzes']; ?></div>
                <div class="statLabel">Total Quizzes</div>
            </div>
            
            <div class="statCard">
                <i class="fas fa-tasks"></i>
                <div class="statNumber"><?php echo $statistics['assessments']; ?></div>
                <div class="statLabel">Total Assessments</div>
            </div>
            
            <div class="statCard">
                <i class="fas fa-book"></i>
                <div class="statNumber">10</div>
                <div class="statLabel">Total Modules</div>
            </div>
        </div>

        <div class="leaderboard">
            <h2>Top Performers</h2>
            <?php 
            $rank = 1;
            foreach($statistics['top_performers'] as $performer): 
            ?>
            <div class="leaderboardItem">
                <div class="rank"><?php echo $rank++; ?></div>
                <div class="userName"><?php echo htmlspecialchars($performer['username']); ?></div>
                <div class="userScore"><?php echo $performer['score']; ?> points</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
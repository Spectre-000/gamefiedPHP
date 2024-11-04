<?php
include("connection.php");

ob_start(); 
session_start(); 
date_default_timezone_set("Asia/Manila");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamefied Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/feedback.css">
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
    <div class="mainPage">
        <div class="topbar">
            <h2>Feedbacks</h2>
        </div>
        <div class="module-list">
            <table class="assessmentList">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Feedback Type</th>
                        <th>Rating</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $selectQry = "SELECT * 
                        from tbl_feedback
                        INNER JOIN tbl_users ON tbl_feedback.senderID = tbl_users.UID";
                        $result = $connectDB->query($selectQry);
                        
                        if($result->num_rows > 0) {
                            while($data = mysqli_fetch_array($result)) {
                                $lastLoginStr = $data['date_submitted'];

                                if($data['rating'] == 1){
                                    $rating = "Poor";
                                }elseif($data['rating'] == 2){
                                    $rating = "Fair";
                                }elseif($data['rating'] == 3){
                                    $rating = "Good";
                                }elseif($data['rating'] == 4){
                                    $rating = "Very Good";
                                }elseif($data['rating'] == 5){
                                    $rating = "Excellent";
                                }
                                
                                if (empty($lastLoginStr)) {
                                    $timeAgo = "Never Submitted";
                                } else {
                                    try {
                                        $lastLogin = new DateTime($lastLoginStr);
                                        $currentDate = new DateTime();
                                        $interval = $lastLogin->diff($currentDate);
                                        
                                        if ($interval->y > 0) {
                                            $timeAgo = $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
                                        } elseif ($interval->m > 0) {
                                            $timeAgo = $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
                                        } elseif ($interval->d > 0) {
                                            $timeAgo = $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
                                        } elseif ($interval->h > 0) {
                                            $timeAgo = $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
                                        } elseif ($interval->i > 0) {
                                            $timeAgo = $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
                                        } else {
                                            $timeAgo = "Just now";
                                        }
                                    } catch (Exception $e) {
                                        $timeAgo = "Invalid date";
                                    }
                                }
                                if($data['feedback_type'] = "General"){
                                    $feedbackType = "General Feedback";
                                }elseif($data['feedback_type'] = "bug"){
                                    $feedbackType = "Bug Report";
                                }elseif($data['feedback_type'] = "content"){
                                    $feedbackType = "Content Improvement";
                                }else{
                                    $feedbackType = "Feature Request";
                                }
                        
                                echo "<tr>
                                        <td>" . htmlspecialchars($data['username']) . "</td>
                                        <td>" . $feedbackType . "</td>
                                        <td>" . $rating . "</td>
                                        <td>" . $timeAgo . "</td>
                                        <td>
                                            <a href='viewfeedback.php?FID=" . htmlspecialchars($data['feedbackID']) . "' 
                                               class='action-btn'>View</a>
                                            <a href='deleteFeedback.php?FID=" . htmlspecialchars($data['feedbackID']) . "' 
                                               class='action-btn' 
                                               onclick=\"return confirm('Are you sure you want to delete this Feedback?');\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No results found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
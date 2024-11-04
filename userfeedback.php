<?php 
include("connection.php");
session_start();
ob_start();




$sessionUsername = $_SESSION['session_user'];
$sessionID = $_SESSION['session_user_id'];

// Get user profile picture
$pictureQuery = "SELECT picture FROM tbl_profilepictures WHERE UID = ?";
$stmt = $connectDB->prepare($pictureQuery);
$stmt->bind_param('i', $sessionID);
$stmt->execute();
$pictureResult = $stmt->get_result();
$profilePicture = $pictureResult->num_rows > 0 ? 'pfp/' . $pictureResult->fetch_assoc()['picture'] : 'pfp/defaultUser.jpg';

// Get user rank
$rankQuery = "SELECT rank FROM tbl_userrank WHERE UID = ?";
$stmt = $connectDB->prepare($rankQuery);
$stmt->bind_param('i', $sessionID);
$stmt->execute();
$rankResult = $stmt->get_result();
$rank = "No Rank";

if($rankResult->num_rows > 0) {
    $rankNum = $rankResult->fetch_assoc()['rank'];
    $rank = match($rankNum) {
        1 => "Newbie",
        2 => "Intermediate",
        3 => "Advanced",
    };
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - <?php echo htmlspecialchars($sessionUsername); ?></title>
    <link rel="stylesheet" href="css/learn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header Section (Same as existing) -->
        <div class="hamburgerMenu menu" id="sidebarBtn">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

    <div class="feedbackContainer">
        <div class="feedbackHeader">
            <h1><i class="fas fa-comment-dots"></i> Share Your Feedback</h1>
            <p>Your feedback helps us improve the learning experience for everyone. Let us know what you think about the modules, interface, or anything else!</p>
        </div>

        <div class="feedbackForm">
            <form action="process_feedback.php" method="POST">
                <div class="formGroup">
                    <label for="feedbackType">Type of Feedback</label>
                    <select id="feedbackType" name="feedbackType" required>
                        <option value="">Select feedback type</option>
                        <option value="general">General Feedback</option>
                        <option value="bug">Bug Report</option>
                        <option value="content">Content Improvement</option>
                        <option value="feature">Feature Request</option>
                    </select>
                </div>

                <div class="formGroup">
                    <label>How would you rate your experience?</label>
                    <div class="ratingGroup">
                        <div class="ratingOption">
                            <input type="radio" id="rating1" name="rating" value="1">
                            <label for="rating1">Poor</label>
                        </div>
                        <div class="ratingOption">
                            <input type="radio" id="rating2" name="rating" value="2">
                            <label for="rating2">Fair</label>
                        </div>
                        <div class="ratingOption">
                            <input type="radio" id="rating3" name="rating" value="3">
                            <label for="rating3">Good</label>
                        </div>
                        <div class="ratingOption">
                            <input type="radio" id="rating4" name="rating" value="4">
                            <label for="rating4">Very Good</label>
                        </div>
                        <div class="ratingOption">
                            <input type="radio" id="rating5" name="rating" value="5">
                            <label for="rating5">Excellent</label>
                        </div>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="feedbackText">Your Feedback</label>
                    <textarea id="feedbackText" name="feedbackText" rows="6" required 
                              placeholder="Please share your thoughts..."></textarea>
                </div>

                <button type="submit" class="submitBtn">Submit Feedback</button>
            </form>
        </div>


    <div class="sideBar" id="sidebar">
        <div class="closesidebarBtn" id="returnBtn">
            <i class="fas fa-arrow-left" style="margin: 1rem; cursor: pointer;"></i>
        </div>
        <div class="sidebarTitle">
            <span class="sideTitle">MENU</span>
        </div>
        <div class="navs">
            <ul>
                <a href="homepage.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-home"></i> Home</li>
                </a>
                <a href="assessment.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-tasks"></i> Assessment</li>
                </a>
                <a href="learn.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-book"></i> Learn</li>
                </a>
                <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-puzzle-piece"></i> Quiz</li>
                </a>
                <a href="leaderboard.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-trophy"></i> Leaderboards</li>
                </a>
                <a href="leaderboard.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-comment-dots"></i> Send feedback</li>
                </a>
                <a href="logout.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-sign-out-alt"></i> Log Out</li>
                </a>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sidebar = document.getElementById('sidebar');
            const sidebarBtn = document.getElementById('sidebarBtn');
            const returnBtn = document.getElementById('returnBtn');

            sidebarBtn.addEventListener('click', () => sidebar.classList.add('showsidebar'));
            returnBtn.addEventListener('click', () => sidebar.classList.remove('showsidebar'));
        });
    </script>
</body>
</html>
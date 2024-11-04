<?php 
include("connection.php");
session_start();
ob_start();

if(!isset($_SESSION['session_user'])){
    header("location: Index.php?e=" . urlencode("Please log in first"));
    exit();
}

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
        1 => "Beginner",
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
    <title>Welcome, <?php echo htmlspecialchars($sessionUsername); ?></title>
    <link rel="stylesheet" href="css/mobileUI.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ed6346;
            --secondary-color: #ffe4ce;
            --text-color: #333;
            --bg-color: #f5f5f5;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 15px;
            --transition: all 0.3s ease;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-color);
            font-family: 'Arial', sans-serif;
            color: var(--text-color);
        }

        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .userProfile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .userPicture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition);
            border: 3px solid var(--primary-color);
        }

        .userPicture:hover {
            transform: scale(1.1);
        }

        .userPicture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .userName {
            display: flex;
            flex-direction: column;
        }

        .profileName {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--text-color);
        }

        .profileRank {
            font-size: 0.9rem;
            color: var(--primary-color);
            margin-left: 0.5rem;
        }

        .mainContent {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .box1 {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .box1:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }

        .box1::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
        }

        .boxTitle {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .boxText {
            color: #666;
            line-height: 1.6;
        }

        .box1 button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: bold;
            margin-top: auto;
        }

        .box1 button:hover {
            background: #ff8566;
            transform: translateY(-2px);
        }

        .hamburgerMenu {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bar {
            width: 25px;
            height: 3px;
            background: var(--primary-color);
            transition: var(--transition);
        }

        .hamburgerMenu:hover .bar {
            background: #ff8566;
        }

        .sideBar {
            position: fixed;
            right: -300px;
            top: 0;
            width: 300px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            transition: var(--transition);
            z-index: 1000;
        }

        .showsidebar {
            right: 0;
        }

        .sidebarTitle {
            padding: 2rem;
            border-bottom: 1px solid #eee;
        }

        .sideTitle {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .navs ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navs li {
            padding: 1rem 2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .navs li:hover {
            background: var(--secondary-color);
            color: var(--primary-color);
        }

        .blurBG {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 900;
        }

        .pictureUpload {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .showpictureUpload {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .closeUploadBtn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-color);
        }

        .picturePreview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 1rem auto;
            background-size: cover;
            background-position: center;
            border: 3px solid var(--primary-color);
        }

        @media (max-width: 768px) {
            .mainContent {
                grid-template-columns: 1fr;
                padding: 1rem;
                gap: 1rem;
                margin: 1rem auto;
            }

            .box1 {
                padding: 1.5rem;
                margin: 0;
                width: auto;
                min-width: 0;
            }

            .boxTitle {
                font-size: 1.3rem;
            }

            .boxText {
                font-size: 0.95rem;
            }

            .box1 button {
                padding: 0.8rem 1.5rem;
                width: 100%;
            }
        }

        /* Add this for even smaller screens */
        @media (max-width: 320px) {
            .mainContent {
                padding: 0.5rem;
            }

            .box1 {
                padding: 1rem;
            }

            .boxTitle {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="userProfile">
            <div class="userPicture" id="changePictureBtn" title="Click to change">
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
            </div>
            <div class="userName">
                <span class="profileName">
                    <?php echo htmlspecialchars($sessionUsername); ?>
                    <span class="profileRank"><?php echo htmlspecialchars($rank); ?></span>
                </span>
            </div>
        </div>
        <div class="hamburgerMenu" id="sidebarBtn">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>

    <div class="mainContent">
        <div class="box1">
            <i class="fas fa-tasks fa-2x" style="color: var(--primary-color)"></i>
            <span class="boxTitle">Assessments</span>
            <span class="boxText">Complete PHP coding challenges to improve your skills and raise your rank. Test your knowledge with hands-on exercises.</span>
            <a href="assessment.php">
                <button>Take Assessment</button>
            </a>
        </div>
        <div class="box1">
            <i class="fas fa-book fa-2x" style="color: var(--primary-color)"></i>
            <span class="boxTitle">Learn</span>
            <span class="boxText">Access comprehensive PHP learning materials designed to help you master web development concepts.</span>
            <a href="learn.php">
                <button>Start Learning</button>
            </a>
        </div>
        <div class="box1">
            <i class="fas fa-puzzle-piece fa-2x" style="color: var(--primary-color)"></i>
            <span class="boxTitle">Quiz</span>
            <span class="boxText">Test your PHP knowledge with our interactive drag-and-drop quiz game. Compete with others and climb the leaderboard!</span>
            <a href="quiz.php">
                <button>Start Quiz</button>
            </a>
        </div>
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
                <?php
                if($sessionUsername == "admin") {
                    echo "
                    <a href='adminhome.php' style='text-decoration: none; color: inherit;'>
                        <li><i class='fas fa-shield-alt'></i>Admin Panel</li>
                    </a>
                    ";
                }
                ?>
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
                <a href="userfeedback.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-comment-dots"></i> Send feedback</li>
                </a>
                <a href="logout.php" style="text-decoration: none; color: inherit;">
                    <li><i class="fas fa-sign-out-alt"></i> Log Out</li>
                </a>
            </ul>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="blurBG"></div>
    <div class="pictureUpload" id="pictureUpload">
        <form action="uploadPFP.php" class="formUpload" enctype="multipart/form-data" method="post">
            <div class="closeUploadBtn" id="closeUploadBtn">&times;</div>
            <div class="picturePreview"></div>
            <input type="file" name="profilePicture" id="profilePictureInput" accept="image/*" style="margin: 1rem 0;">
            <button class="uploadBtn">Upload Picture</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const pictureUpload = document.getElementById('pictureUpload');
            const changePictureBtn = document.getElementById('changePictureBtn');
            const closeUploadBtn = document.getElementById('closeUploadBtn');
            const blurBG = document.querySelector('.blurBG');
            const sidebar = document.getElementById('sidebar');
            const sidebarBtn = document.getElementById('sidebarBtn');
            const returnBtn = document.getElementById('returnBtn');
            const profilePictureInput = document.getElementById('profilePictureInput');
            const picturePreview = document.querySelector('.picturePreview');

            const toggleBlurBG = (show) => blurBG.style.display = show ? "block" : "none";

            changePictureBtn.addEventListener('click', () => {
                toggleBlurBG(true);
                pictureUpload.classList.add('showpictureUpload');
            });

            closeUploadBtn.addEventListener('click', () => {
                toggleBlurBG(false);
                pictureUpload.classList.remove('showpictureUpload');
            });

            profilePictureInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        picturePreview.style.backgroundImage = `url(${e.target.result})`;
                    };
                    reader.readAsDataURL(file);
                }
            });

            sidebarBtn.addEventListener('click', () => sidebar.classList.add('showsidebar'));
            returnBtn.addEventListener('click', () => sidebar.classList.remove('showsidebar'));
        });
    </script>
</body>
</html>
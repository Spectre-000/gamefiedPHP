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
    <title>Welcome, <?php echo htmlspecialchars($sessionUsername); ?></title>
    <link rel="stylesheet" href="css/learn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        <?php
        include('tracking-functions.php');
        $moduleData = getModuleStatus($connectDB, $sessionID);

        $sections = [
            'beginner' => [
                'icon' => 'fas fa-seedling',
                'title' => 'Beginner',
                'modules' => [
                    1 => 'Introduction to PHP',
                    2 => 'PHP Variables and Data Types',
                    3 => 'PHP Control Structures',
                    4 => 'PHP Functions'
                ]
            ],
            'intermediate' => [
                'icon' => 'fas fa-star',
                'title' => 'Intermediate',
                'modules' => [
                    5 => 'PHP Arrays',
                    6 => 'PHP Input and Output',
                    7 => 'PHP File Handling',
                    8 => 'PHP Database Integration (MySQL)'
                ]
            ],
            'advanced' => [
                'icon' => 'fas fa-graduation-cap',
                'title' => 'Advanced',
                'modules' => [
                    9 => 'PHP Object-Oriented Programming',
                    10 => 'PHP Web Development'
                ]
            ]
        ];

        foreach($sections as $level => $section): 
            $progress = calculateProgress($moduleData, $level);
        ?>
            <div class="section">
                <div class="sectionHeader">
                    <div class="headerTitle">
                        <i class="<?php echo $section['icon']; ?>"></i>
                        <?php echo $section['title']; ?>
                    </div>
                    <div class="progressStats">
                        <div class="progressBar">
                            <div class="progressFill" style="width: <?php echo $progress['percentage']; ?>%"></div>
                        </div>
                        <span><?php echo $progress['completed']; ?>/<?php echo $progress['total']; ?> completed</span>
                    </div>
                </div>
                
                <div class="moduleList">
                    <?php foreach($section['modules'] as $moduleNum => $moduleName): ?>
                        <a href="modules/<?php echo $moduleNum; ?>.php" class="moduleItem">
                            <div class="moduleName">
                                <i class="fas fa-angle-right"></i>
                                <?php echo $moduleName; ?>
                            </div>
                            <div class="moduleStatus">
                                <?php if($moduleData["module$moduleNum"] == 1): ?>
                                    <span class="statusIcon completed"><i class="fas fa-check"></i></span>
                                    <span style="color: #4CAF50;">Completed</span>
                                <?php else: ?>
                                    <span class="statusIcon pending"><i class="fas fa-clock"></i></span>
                                    <span style="color: #FFC107;">In Progress</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Sidebar -->
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
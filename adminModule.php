<?php
include("connection.php");

session_start();

// Function to get modules by category
function getModulesByCategory($connectDB, $category) {
    $selectQry = "SELECT * FROM tbl_modules WHERE moduleCategory = $category";
    $result = $connectDB->query($selectQry);
    $modules = array();

    if ($result->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $modules[] = $data;
        }
    }

    return $modules;
}

// Get modules by category
$introModules = getModulesByCategory($connectDB, 1);
$beginnerModules = getModulesByCategory($connectDB, 2);
$advancedModules = getModulesByCategory($connectDB, 3);
$extraModules = getModulesByCategory($connectDB, 4);
?>

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
                <a href="adminHome.php"><li><i class="fas fa-home"></i> Dashboard</li></a>
                <a href="adminModule.php"><li><i class="fas fa-book"></i> Modules</li></a>
                <a href="adminAssessment.php"><li><i class="fas fa-tasks"></i> Assessments</li></a>
                <a href="adminQuiz.php"><li><i class="fas fa-question-circle"></i> Quizzes</li></a>
                <a href="adminUser.php"><li><i class="fas fa-users"></i> Users</li></a>
                <a href="adminLeaderboard.php"><li><i class="fas fa-trophy"></i> Leaderboards</li></a>
            </ul>
        </div>
        <div class="userInfo">
            <img src="img/user.svg" alt="" width="30">
            <span>ADMIN</span>
            <a href="logout.php" title="Logout">
                <i class="fas fa-sign-out-alt" style="color: white;"></i>
            </a>
        </div>
    </div>

    <div class="mainPage">
        <div class="topbar">
            <h2>Manage Modules</h2>
        </div>

        <span class="module-category">Newbie</span>
        <ul class="moduleList">
            <?php foreach ($introModules as $module) { ?>
            <li>
                <a href="modules/<?php echo $module['moduleLocation']; ?>"><?php echo $module['moduleName']; ?></a>
                <span class="deleteModule" title="Delete this module">
                    <a href="deleteModule.php?mid=<?php echo $module['MID']; ?>" onclick="return confirm('Are you sure you want to delete this module?');" style="color: var(--primary-color);">&times;</a>
                </span>
            </li>
            <?php } ?>
        </ul>

        <span class="module-category">Advanced</span>
        <ul class="moduleList">
            <?php foreach ($beginnerModules as $module) { ?>
            <li>
                <a href="modules/<?php echo $module['moduleLocation']; ?>"><?php echo $module['moduleName']; ?></a>
                <span class="deleteModule" title="Delete this module">
                    <a href="deleteModule.php?mid=<?php echo $module['MID']; ?>" onclick="return confirm('Are you sure you want to delete this module?');" style="color: var(--primary-color);">&times;</a>
                </span>
            </li>
            <?php } ?>
        </ul>

        <span class="module-category">Professional</span>
        <ul class="moduleList">
            <?php foreach ($advancedModules as $module) { ?>
            <li>
                <a href="modules/<?php echo $module['moduleLocation']; ?>"><?php echo $module['moduleName']; ?></a>
                <span class="deleteModule" title="Delete this module">
                    <a href="deleteModule.php?mid=<?php echo $module['MID']; ?>" onclick="return confirm('Are you sure you want to delete this module?');" style="color: var(--primary-color);">&times;</a>
                </span>
            </li>
            <?php } ?>
        </ul>

        <span class="module-category">Master</span>
        <ul class="moduleList">
            <?php foreach ($extraModules as $module) { ?>
            <li>
                <a href="modules/<?php echo $module['moduleLocation']; ?>"><?php echo $module['moduleName']; ?></a>
                <span class="deleteModule" title="Delete this module">
                    <a href="deleteModule.php?mid=<?php echo $module['MID']; ?>" onclick="return confirm('Are you sure you want to delete this module?');" style="color: var(--primary-color);">&times;</a>
                </span>
            </li>
            <?php } ?>
        </ul>
    </div>

    <div class="addModule" title="Add module" id="showModulePopUp">
        <i class="fas fa-plus"></i>
    </div>

    <div class="modulePopup" id="modulePopup">
        <span class="popUpTitle">Add Module</span>
        <form action="addModule.php" method="post" enctype="multipart/form-data">
            <select name="moduleCategory" class="select-module-category">
                <option value="0">-- Select Category --</option>
                <option value="1">Newbiew</option>
                <option value="2">Advanced</option>
                <option value="3">Professional</option>
                <option value="4">Master</option>
            </select>
            <input type="text" name="moduleName" class="select-module-name" placeholder="Enter Module Name">
            <input type="file" name="moduleFile" class="module-file">
            <input type="submit" value="Save" class="saveModule">
        </form>
        <div class="closeBtn" id="closeBtn">
            &times;
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showModuleBtn = document.getElementById('showModulePopUp');
            const modulePopUp = document.getElementById('modulePopup');
            const closeBtn = document.getElementById('closeBtn');

            showModuleBtn.addEventListener('click', function() {
                modulePopUp.classList.add('showModulePopUp');
            });

            closeBtn.addEventListener('click', function() {
                modulePopUp.classList.remove('showModulePopUp');
            });
        });
    </script>
</body>
</html>
<?php

date_default_timezone_set("Asia/Manila");
include("connection.php");
session_start();
ob_start();
$sessionUsername = $_SESSION['session_user'];
$sessionID = $_SESSION['session_user_id'];
$date = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $picture = $_FILES['profilePicture'];
    $targerDir = "pfp/";

    $fileExtension = strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION));
    
    $uniqueID = uniqid();
    $sanitizedModuleName = preg_replace("/[^a-zA-Z0-9]/", "_", $sessionUsername); 
    $newFileName = $sanitizedModuleName . "_" . $uniqueID . "." . $fileExtension;
    $targetFile = $targerDir . $newFileName;


    if($picture['size'] > 5000000){
        $msg = "File is too large";
        header("location: homepage.php?e=$msg");
        exit();
    }

    $allowedFormats = ['jpg', 'png', 'jpeg'];
    if(!in_array($fileExtension, $allowedFormats)){
        $msg = "Sorry, Allowed formats jpg, jpeg, and png";
        header("location: homepage.php?e=$msg");
        exit();
    }

    $selectQry = "SELECT * FROM tbl_profilepictures WHERE `UID` = '$sessionID'";
    $result = $connectDB->query($selectQry);

    if($result->num_rows > 0){
        $data = $result->fetch_assoc();
        $currentFilename = $data['picture'];

        $currentTargetFile = $targerDir . $currentFilename;

        if(file_exists($currentTargetFile)){
            if(unlink($currentTargetFile)){
                if(move_uploaded_file($picture['tmp_name'], $targetFile)){
                    $updateQry = "UPDATE tbl_profilepictures SET `picture` = '$newFileName', `date_uploaded` = '$date'";
                    if($result = $connectDB->query($updateQry)){
                        $msg = "Picture successfully updated";
                        header("location: homepage.php?e=$msg");
                    }
                }
            }else{
                echo "Failed to delete old picture";
            }
        }else {
            if(move_uploaded_file($picture['tmp_name'], $targetFile)){
                $updateQry = "UPDATE tbl_profilepictures SET `picture` = '$newFileName', `date_uploaded` = '$date'";
                if($result = $connectDB->query($updateQry)){
                    $msg = "Picture successfully updated";
                    header("location: homepage.php?e=$msg");
                }
            }
        }
    }else {
        if(move_uploaded_file($picture['tmp_name'], $targetFile)){
            $stmt = $connectDB->prepare("INSERT INTO tbl_profilepictures(`UID`,`picture`,`date_uploaded`) VALUES(?,?,?)");
            $stmt->bind_param("iss", $sessionID, $newFileName, $date);

            if($stmt->execute()){
                $msg = "Successfully added picture";
                header("location: homepage.php?e=$msg");
            }else{
                $msg = "Failed to add picture";
                header("location: homepage.php?e=$msg");
            }
        }
    }
}

?>
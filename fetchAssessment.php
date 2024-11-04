<?php

session_start();
ob_start();

$sessionID = $_SESSION['session_user_id'];

if (!isset($_SESSION['session_user_id'])) {
    echo json_encode(array("error" => "Session expired or invalid."));
    exit;
}
$selectQry = "SELECT rank FROM tbl_userrank WHERE UID = '$sessionID'";
$result = $connectDB->query($selectQry);

if ($result === false) {
    // SQL error handling
    echo json_encode(array("error" => "SQL error: " . $connectDB->error));
    exit;
}

$rank = ($result->num_rows > 0) ? $result->fetch_assoc()['rank'] : 1;

$response = array(
    "AID" => null,
    "assessment_category" => null,
    "question" => null,
    "problem" => null,
    "answers" => array(),
    "error" => "No new assessment found"
);

$selectQry = "SELECT * FROM tbl_assessments WHERE `assessment_category = '$rank'`";
$result = $connectDB->query($selectQry);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $response['question'] = $row['given'];
}


?>
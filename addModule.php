<?php 

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $moduleCategory = $_POST['moduleCategory'];
    $moduleName = $_POST['moduleName'];
    $moduleFile = $_FILES['moduleFile'];

    if ($moduleCategory == "0") {
        echo "Please select a module category.";
    } elseif (empty($moduleName)) {
        echo "Please enter a module name.";
    } elseif (empty($moduleFile['name'])) {
        echo "Please upload a file.";
    } else {
        // Define the target directory for file uploads
        $targetDir = "modules/";

        // Get the file extension
        $fileExtension = strtolower(pathinfo($moduleFile['name'], PATHINFO_EXTENSION));


        $uniqueID = uniqid(); 
        $sanitizedModuleName = preg_replace("/[^a-zA-Z0-9]/", "_", $moduleName); 
        $newFileName = $sanitizedModuleName . "_" . $uniqueID . "." . $fileExtension;
        $targetFile = $targetDir . $newFileName;

        // Check file size (limit to 5MB)
        if ($moduleFile['size'] > 5000000) {
            echo "Sorry, your file is too large.";
            exit();
        }

        // Allowed file formats
        $allowedTypes = ['pdf'];
        if (!in_array($fileExtension, $allowedTypes)) {
            echo "Sorry, only PDF is allowed.";
            exit();
        }

        // Upload the file
        if (move_uploaded_file($moduleFile['tmp_name'], $targetFile)) {
            // Prepare SQL to insert module information into tbl_modules table
            $stmt = $connectDB->prepare("INSERT INTO tbl_modules (moduleCategory, moduleName, moduleLocation, date_added) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $moduleCategory, $moduleName, $newFileName);

            if ($stmt->execute()) {
                $msg = "Module successfully added!";
                header("location: adminModule.php?e=$msg");
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$connectDB->close();

?>

<?php 
$connectDB = new mysqli('localhost', 'root', '', 'gcl_db');
//$connectDB = new mysqli('sql113.ezyro.com', 'ezyro_37637437', '381772', 'ezyro_37637437_gcl_db');
if ($connectDB->connect_error) {
    echo "Error: " . $connectDB->connect_error;
	exit();
}else {

}
  
?>
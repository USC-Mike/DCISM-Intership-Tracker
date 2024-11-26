<?php
// test.php
$filePath = '../backend/config/db.php';
if (file_exists($filePath)) {
    echo "File exists!";
    require($filePath);
} else {
    echo "File does not exist!";
}
?>

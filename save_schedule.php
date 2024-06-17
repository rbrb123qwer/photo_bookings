<?php
/* 
require_once('config.php');
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    $conn = null;
    exit;
}
extract($_POST);
$allday = isset($allday);

if(empty($id)){
    $sql = "INSERT INTO `schedule_list` (`title`,`description`,`start_datetime`,`end_datetime`) VALUES (:title, :description, :start_datetime, :end_datetime)";
}else{
    $sql = "UPDATE `schedule_list` SET `title` = :title, `description` = :description, `start_datetime` = :start_datetime, `end_datetime` = :end_datetime WHERE `id` = :id";
}

$stmt = $conn->prepare($sql);
if(empty($id)){
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_datetime', $start_datetime);
    $stmt->bindParam(':end_datetime', $end_datetime);
}else{
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_datetime', $start_datetime);
    $stmt->bindParam(':end_datetime', $end_datetime);
    $stmt->bindParam(':id', $id);
}

$save = $stmt->execute();
if($save){
    echo "<script> alert('Schedule Successfully Saved.'); location.replace('./') </script>";
}else{
    echo "<pre>";
    echo "An Error occurred.<br>";
    $errorInfo = $stmt->errorInfo();
    echo "Error: " . $errorInfo[2] . "<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
}
$conn = null;

*/
?>
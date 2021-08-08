<?php
  session_start();
  require_once('conn.php');
  if (empty($_GET['id'])) {
    die('error');
  }
  $id = $_GET['id'];
  $username_session = $_SESSION['username'];
  $status_session = $_SESSION['member_status'];
  $user_sql = "UPDATE `tingkao_comments` SET `is_deleted`= 1 WHERE `id` = ? AND `username` = ?";
  $manager_sql = "UPDATE `tingkao_comments` SET `is_deleted`= 1 WHERE `id` = ? ";
  if($status_session === "manager"){
    $stmt = $conn->prepare($manager_sql);
    $stmt->bind_param("s", $id);
  } else {
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("ss", $id, $username_session);
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  if($conn->error || $stmt->error){
    echo $conn->error . $stmt->error;
  } else {
    header("Location: home.php");
  }
?>

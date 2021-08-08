<?php
  session_start();
  require_once('conn.php');
  if (empty($_POST['member_status']) || empty($_POST['id'])) {
    die('data missing');
  }
  $new_status = $_POST['member_status'];
  $member_id = $_POST['id'];
  // die($member_id );
  $username_session = $_SESSION['username'];
  $status_session = $_SESSION['member_status'];
  if($status_session !== "manager"){
    die("You do not have permission");
  }
  $sql = "UPDATE `tingkao_members` SET `member_status`= ? WHERE `id` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $new_status, $member_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if($conn->error || $stmt->error){
    echo $conn->error . $stmt->error;
  } else {
    header("Location: manage.php");
  }
?>

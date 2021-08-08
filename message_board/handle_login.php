<?php
  session_start();
  require_once('conn.php');
  // require_once('utility.php');
  $username = $_POST['username'];
  $password = $_POST['password'];
  if(empty($username) || empty($username)) {
    die('Wrong password!');
  }
  $sql = "SELECT `password`, `member_status` FROM `tingkao_members` WHERE `username` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if($conn->error || $stmt->error){
    echo "Error：" . $conn->error . $stmt->error;
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  // print_r('How many rows returned:' . $stmt->affected_rows);
  // print_r('How many rows returned:' . $result->num_rows);
  // print_r('if :' . ($result->num_rows));

  if($result->num_rows === 1) {
    $pwd_hashed = $row ['password'];
    $member_status = $row ['member_status'];
    $isVarified = password_verify($password, $pwd_hashed);
    if($isVarified){
      // echo "Log in sucessfully";
      $_SESSION['username'] = $username;
      $_SESSION['member_status'] = $member_status;
      header("Location: home.php");
    }else {
      // echo "Wrong password!";
      header("Location: home.php?login=err");
    }
  } else {
    // echo "Wrong user account";
    header("Location: home.php?login=err");
  }
?>
<?php
  session_start();
  require_once('conn.php');
  require_once('utility.php');
  if(empty($_POST['username']) || empty($_POST['nickname']) || empty($_POST['password'])) {
    die('data missing');
  }
  $username = $_POST['username'];
  $nickname = $_POST['nickname'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $sql = "INSERT INTO `tingkao_members`(`username`, `nickname`, `password`) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $nickname, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if(!empty($stmt->error)) {
    if($stmt->errno === 1062){
      // die('user account is taken');
      header("Location: home.php?register=re");
    }
    // other error, $conn->error：preare() failed
    die('Error：' . $conn->error . $stmt->error);
  } else {
    $_SESSION['username'] = $username;
    $_SESSION['member_status'] = "user";
    header("Location: home.php");
  }
  
?>
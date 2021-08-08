<?php
  require_once('conn.php');
  require_once('utility.php');
  session_start();
  $username = $_SESSION['username'];

  $password_hashed = checkMembers($username)['password'];

  if(!empty($_POST['nickname']) && !empty($_POST['password'])) {
    $nickname_new = $_POST['nickname'];
    $password = $_POST['password'];
    $isVarified = password_verify($password, $password_hashed);

    if(!$isVarified){
      header("Location: setting.php?state=err");
      die("Wrong Password!");
    } 
    $sql = "UPDATE `tingkao_members` SET `nickname`= ? WHERE `username`= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nickname_new, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($conn->error || $stmt->error){
      die("Error：" . $conn->error . $stmt->error);
    } else {
      // echo "data has changed";
      header("Location: setting.php?state=1");
    }
  }

  if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $old_password = $_POST['old_password'];
    $isVarified = password_verify($old_password, $password_hashed);

    if(!$isVarified){
      header("Location: setting.php?state=err");
      die("Wrong Password!");
    } 
    $sql = "UPDATE `tingkao_members` SET `password`= ? WHERE `username`= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($conn->error || $stmt->error){
      die("Error：" . $conn->error . $stmt->error);
    } else {
      // echo "data has changed";
      header("Location: setting.php?state=1");
    }
  }
?>
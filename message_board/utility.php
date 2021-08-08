<?php
  //16ch token
  require_once('conn.php');
  function createToken($num) {
    $token = '';
    for ($i = 0; $i < $num; $i += 1) {
      $token = $token . chr(rand(65,90));
    }
    return $token;
  }

  function checkMembers($username){
    global $conn;
    $sql = "SELECT * FROM `tingkao_members` WHERE `username`= ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }
?>
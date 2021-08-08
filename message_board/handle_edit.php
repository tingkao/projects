<?php
  require_once('conn.php');
  if (empty($_POST['content']) || empty($_POST['id'])) {
    die('data missing！');
  }
  // editting others' message by changing id, so use session['username'] matching data if it's the same person
  $content = $_POST['content'];
  $id = $_POST['id'];
  $edited = 1;
  $sql = "UPDATE `tingkao_comments` SET `content`= ?, `edited`= ? WHERE `id` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sis", $content, $edited, $id);
  $stmt->execute();
  if($conn->error || $stmt->error){
    echo "Error：" . $conn->error . $stmt->error;
  } else {
    header("Location: home.php");
  }
?>
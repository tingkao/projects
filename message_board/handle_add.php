<?php
  require_once('conn.php');
  session_start();
  if (empty($_POST['nickname']) || empty($_POST['content'])) {
    // die('data missing！');
    header("Location: home.php?error=1");
    exit();
  }
  $username = $_POST['username'];
  $member_status = $_SESSION['member_status'];
  if($member_status === "banned"){
    echo "Your account has been banned, and you are temporarily unavailable to send messages";
  ?>
    <br>
    <a href="home.php">Back to Message Board</a>
  <?php
    exit();
  }
  // $niackname = $_POST['nickname'];
  $content = $_POST['content'];
  $sql = "INSERT INTO `tingkao_comments`(`username`, `content`) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $content);
  $result = $stmt->execute();
  if($stmt->error){
    echo "Error：" . $stmt->error;
  } else {
    header("Location: home.php");
  }
?>
<?php
  if(!empty($_GET['error']) && $_GET['error']==='1'){
    echo "<script>alert('資料不齊全')</script>";
    // 這個空格檢查方法，重新整理後還是會在(因為網址還是帶了 ?error=1 的資料)，所以比較好的方法是用 Js 做表單驗證
  }
  require_once('conn.php');
  $sql = "SELECT * FROM `comments` ORDER BY `created_at` DESC";
  $result = $conn->query($sql);
  if($conn->error){
    die('錯誤' . $conn->error);
  } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>留言板</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>注意！本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼。</header>
  <div class="wrap">
    <h1>Comments</h1>
    <form class="comment__add" method="POST" action="handle_add.php" >
      <label for="">NICKNAME： <input class="nickname" type="text" name="nickname"></label>
      <textarea name="content" id="" cols="30" rows="10"></textarea>
      <input class="submit-btn" type="submit" value="提交">
    </form>
    <div class="comment__hr"></div>
    <div class="comment__cards-group">
<?php
  while ($row = $result->fetch_assoc()) {
    $nickname = $row['nickname'];
    $content = $row['content'];
    $created_at = $row['created_at'];
    $id = $row['id'];
    echo '<div class="card">';
    echo    '<div class="card-avatar"></div>';
    echo    '<div class="card-info">';
    echo        '<div class="card-user">' . $nickname . '</div>';
    echo        '<div class="card-dot"></div>';
    echo        '<div class="card-time">' . $created_at . '</div>';
    echo    '</div>';
    echo    '<div class="content">'. $content .'</div>';
    echo    '<div class="card-btn">';
    echo        '<a href="handle_delete.php?id=' . $id . '" class="delete-btn">刪除 </a>';
    echo        '<a href="edit.php?id=' . $id . '" class="edit-btn">編輯</a>';
    echo    '</div>';
    echo '</div>';
  }
?>
    <div class="comment__hr"></div>
  </div>
</body>
</html>
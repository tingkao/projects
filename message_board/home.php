<?php
  session_start();
  $whoLogin = null;
  if(!empty($_GET['error']) && $_GET['error']==='1'){
    echo "<script>alert('data missing')</script>";
    // the data validation, it's better to be done with "form" validation in JavaScript before the submission
  }
  require_once('conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Message Board</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dark_bgc hidden"></div>
  <header>Warning！The site is for practice purpose. Please do not use any real account or password when registering.</header>
  <div class="wrap">
    <h1>Comments</h1>

<?php if (!empty($_SESSION['username'])) { 
        // SELECT the nickname for the token in DB
        $data_sql = "SELECT nickname FROM `tingkao_members` WHERE username = ?";
        $stmt = $conn->prepare($data_sql);
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($conn->error || $stmt->error) {
          die($conn->error . $stmt->error);
        }
        if(!$result->num_rows){
          /*cookie 比對不到相對應的資料，cookie 被 clien 端隨意填入假資料*/
          session_destroy();
          header("Location: home.phps");
        }
        $login_nickname = htmlspecialchars($result->fetch_assoc()['nickname'], ENT_QUOTES);
        $whoLogin = htmlspecialchars($_SESSION['username'], ENT_QUOTES);
?>
    <div class="member-btn">
<?php
      if($_SESSION['member_status'] === "manager") {
        echo '<a href="manage.php?page=1" class="manage-btn">Content Manage System</a>';
      }
?>
      <a href="setting.php" class="setting-btn">Settings</a>
      <a href="handle_logout.php" class="logout-btn">Log Out</a>
    </div>
    <form class="comment__add" method="POST" action="handle_add.php" >
      <label for="">Say something？ <?php echo $login_nickname ?> 
        <input class="nickname" type="hidden" name="nickname" value="<?php echo $login_nickname ?>">
        <input type="hidden" name="username" value="<?php echo $_SESSION['username'] ?>">
      </label>

<?php
      if ($_SESSION['member_status'] !== "banned") {
        echo '<textarea name="content" id="" cols="30" rows="10"></textarea>';
        echo '<input class="submit-btn" type="submit" value="Submit">';
      } else {
        echo '<textarea name="content" id="" cols="30" rows="10" style="color: #bbb;">Your account has been banned, and you are temporarily unavailable to send messages</textarea>';
      }
?>
  
    </form>
<?php } else {?> 
    <div class="member-btn">
      <a href="#" class="login-btn">Sign In</a>
      <a href="#" class="register-btn">Create Account</a>
    </div>
<?php } ?>

    <div class="comment__hr"></div>
    <div class="comment__cards-group">

<?php
  $sum_sql = "SELECT COUNT(*) AS sum FROM `tingkao_comments` WHERE `is_deleted` = 0";
  $sum_stmt = $conn->prepare($sum_sql);
  $sum_stmt->execute();
  $sum = $sum_stmt->get_result();
  $total_comments = $sum->fetch_assoc()['sum'];
  
  $num_per_page = ($total_comments < 70) ? 6 : 10;
  $pages = ceil($total_comments / $num_per_page);

  if(empty($_GET['page'])){
    $page_state = 1;
  } else if (intval($_GET['page']) >= $pages) {
    $page_state = $pages;
  } else if (intval($_GET['page']) <= 0) {
    $page_state = 1;
  } else {
    $page_state = intval($_GET['page']);
  }

  $index_comment = ($page_state - 1) * $num_per_page;
?>

<?php
  $sql = "SELECT C.id AS content_id, " . 
                "C.content AS content, " . 
                "C.created_at AS created_at, " . 
                "C.edited AS edited, " . 
                "M.username AS username, " . 
                "M.nickname AS nickname " . 
        "FROM `tingkao_comments` AS C LEFT JOIN `tingkao_members` AS M " . 
        "ON C.username = M.username " . 
        "WHERE C.is_deleted = 0 " .
        "ORDER BY C.created_at DESC " .
        "LIMIT ? OFFSET ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $num_per_page, $index_comment);
  $stmt->execute();
  $result = $stmt->get_result();
  if($conn->error || $stmt->error){
    die('Error' . $conn->error . $stmt->error);
  } 
  while ($row = $result->fetch_assoc()) {
    $username = htmlspecialchars($row['username'], ENT_QUOTES);
    $nickname = htmlspecialchars($row['nickname'], ENT_QUOTES);
    $content = htmlspecialchars($row['content'], ENT_QUOTES);
    $created_at = htmlspecialchars($row['created_at'], ENT_QUOTES);
    $id = htmlspecialchars($row['content_id'], ENT_QUOTES);
    $edited = (htmlspecialchars($row['edited'], ENT_QUOTES) === "1")?"edited":"";
    echo '<div class="card">';
    echo    '<div class="card-avatar"></div>';
    echo    '<div class="card-info">';
    echo        '<div class="card-user">' . $nickname . ' (@'. $username .')</div>';
    echo        '<div class="card-dot"></div>';
    echo        '<div class="card-time">' . $created_at . '</div>';
    echo        '<div class="card-time">' . $edited . '</div>';
    echo    '</div>';
    echo    '<div class="content">'. $content .'</div>';
    echo    '<div class="card-btn">';

    $isMangaer = false;
    if (!empty($_SESSION['member_status']) && $_SESSION['member_status'] === "manager") {
      $isMangaer = true;
    }
    if($whoLogin === $username) {
      echo  '<a href="edit.php?id=' . $id . '" class="edit-btn">Edit </a>';
    }
    if ($whoLogin === $username || $isMangaer) { 
      echo  '<a href="handle_delete.php?id=' . $id . '" class="delete-btn"> Delete</a>';
    }



    echo    '</div>';
    echo '</div>';
  }

  echo '<div class="comment__hr"></div>';

  $previousPage = $page_state;
  $previousPage = ($previousPage > 1)?($page_state - 1):($previousPage);
  $nextPage = $page_state;
  $nextPage = ($nextPage < $pages)?($page_state + 1):($nextPage);

  echo '<div class="pages_wrap">';
  echo '<a href="home.php?page=' . $previousPage . '"><<</a>';

  for ($i = 1; $i <= $pages; $i += 1) {
    echo '<a href="home.php?page='. $i .'" data-page="'. $i .'">' . $i . '</a>';
  }

  echo '<a href="home.php?page=' . $nextPage . '">>></a>';
  echo '</div>';
?>
  </div>

  <section class="comment__register hidden">
    <h2>Create Account</h2>
    <form method="POST" action="handle_register.php" class="comment__register-form">
      <label for="username">Account：
        <input type="text" name="username">
      </label>
      <label for="nickname">Nickname：
        <input type="text" name="nickname">
      </label>
      <label for="password">Password：
        <input type="password" name="password">
      </label>
      <input class="handle-register-btn" type="submit" value="Create">
    </form>
    <div class="to-login">Sign In</div>
    <div class="cancel">X</div>
  </section>

  <section class="comment__login hidden">
    <h2>Sign In</h2>
    <form method="POST" action="handle_login.php" class="comment__login-form">
      <label for="username">Account：
        <input type="text" name="username">
      </label>
      <label for="password">Password：
        <input type="password" name="password">
      </label>
      <input class="handle-login-btn" type="submit" value="Sign In">
    </form>
    <div class="to-register">Create Account</div>
    <div class="cancel">X</div>
  </section>
</body>
<script src="register.js"></script>

<?php

?>
<script>
  // pages css
  let pageState = <?php echo $page_state?>;
  document.querySelector(`a[data-page="${pageState}"]`).classList.add('active');
</script>
<?php
  //account is taken
  if(!empty($_GET['register']) && $_GET['register']==='re'){
    echo "<script>
            alert('user account is taken!');
            document.querySelector('.register-btn').click();
          </script>";
  }
  //Wrong password
  if(!empty($_GET['login']) && $_GET['login']==='err'){
    echo "<script>
            alert('Wrong password!');
            document.querySelector('.to-login').click();
          </script>";
  }
?>
</html>
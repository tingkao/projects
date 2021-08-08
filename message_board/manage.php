<?php
  session_start();
  $whoLogin = null;
  if(!empty($_GET['error']) && $_GET['error']==='1'){
    echo "<script>alert('data missing')</script>";
  }
  require_once('conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Content Manage System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dark_bgc hidden"></div>
  <header>Warning！The site is for practice purpose. Please do not use any real account or password when registering.</header>
  <div class="wrap">
    <h1>Comments</h1>

<?php if (!empty($_SESSION['username'])) { 
        $data_sql = "SELECT nickname FROM `tingkao_members` WHERE username = ?";
        $stmt = $conn->prepare($data_sql);
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($conn->error || $stmt->error) {
          die($conn->error . $stmt->error);
        }
        if(!$result->num_rows){
          session_destroy();
          header("Location: home.phps");
        }
        $login_nickname = htmlspecialchars($result->fetch_assoc()['nickname'], ENT_QUOTES);
        $whoLogin = htmlspecialchars($_SESSION['username'], ENT_QUOTES);
?>
        <div class="member-btn">
<?php
        if($_SESSION['member_status'] === "manager") {
          echo '<a href="home.php" class="manage-btn">Back to Message Board</a>';
        }
?>
          <a href="setting.php" class="setting-btn">Settings</a>
          <a href="handle_logout.php" class="logout-btn">Sign Out</a>
        </div>

<?php }?> 

    <div class="comment__hr"></div>
    <div class="comment__cards-group">

<?php
  $sum_sql = "SELECT COUNT(*) AS sum FROM `tingkao_members`";
  $sum_stmt = $conn->prepare($sum_sql);
  $sum_stmt->execute();
  $sum = $sum_stmt->get_result();
  $total_comments = $sum->fetch_assoc()['sum'];
  
  // $num_per_page = ($total_comments < 70) ? 6 : 10;
  $num_per_page = 3;
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
  $sql = "SELECT * FROM `tingkao_members` ORDER BY created_at DESC LIMIT ? OFFSET ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $num_per_page, $index_comment);
  $stmt->execute();
  $result = $stmt->get_result();
  if($conn->error || $stmt->error){
    die('Error' . $conn->error . $stmt->error);
  } 
  while ($row = $result->fetch_assoc()) {
    $id = htmlspecialchars($row['id'], ENT_QUOTES);
    $username = htmlspecialchars($row['username'], ENT_QUOTES);
    $nickname = htmlspecialchars($row['nickname'], ENT_QUOTES);
    $member_status = htmlspecialchars($row['member_status'], ENT_QUOTES);
    $created_at = htmlspecialchars($row['created_at'], ENT_QUOTES);
    
    echo '<div class="card">';
    echo    '<div class="card-avatar card-manage"></div>';
    echo    '<div class="card-info card-manage">';
    echo        '<div class="card-user">username: ' . $username . '</div>';
    echo        '<div class="card-nickname">nickname: ' . $nickname . '</div>';
    echo        '<div class="card-member_status" data-status="'. $member_status .'">member_status: <span>' . $member_status . '</span></div>';
    echo        '<div class="card-time">created_at:' . $created_at . '</div>';
    echo    '</div>';
    echo    '<div class="card-btn">';

    $isMangaer = false;
    if (!empty($_SESSION['member_status']) && $_SESSION['member_status'] === "manager") {
      $isMangaer = true;
    }
    if ($isMangaer) { 
      echo  '<a href="#" class="manage_status-btn" ' . 
            'data-id="'.$id.'" data-id="'.$id.'" data-username="'.$username.'" data-status="'.$member_status.'">Change Member State' . 
            '</a>';
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
  echo '<a href="manage.php?page=' . $previousPage . '"><<</a>';

  for ($i = 1; $i <= $pages; $i += 1) {
    echo '<a href="manage.php?page='. $i .'" data-page="'. $i .'">' . $i . '</a>';
  }

  echo '<a href="manage.php?page=' . $nextPage . '">>></a>';
  echo '</div>';
?>
  </div>
  

  <section class="member_status__edit hidden">
    <h2>Change ID: <span></span> membership</h2>
    <form method="POST" action="handle_manage.php" class="member_status__edit-form">
      <input type="hidden" name="id" value="">  
      <label for="username">Account：</label>
      <label for="member_status">MemberShip：
        <input type="text" name="member_status" value="">
        <div class="intro">manager / user / banned</div>
      </label>
      <input class="member_status__edit-btn" type="submit" value="儲存">
    </form>
    <div class="cancel">X</div>
  </section>
</body>

  <script>
    let pageState = <?php echo $page_state?>;
    document.querySelector(`a[data-page="${pageState}"]`).classList.add('active');

    document.querySelector('.member_status__edit .cancel').addEventListener('click', function(){
      document.querySelector('.member_status__edit').classList.add('hidden');
    });
      document.querySelectorAll('.manage_status-btn').forEach(function(el){
        el.addEventListener('click', function(e){
          e.preventDefault();
          let dataId = e.target.getAttribute("data-id");
          let dataUsername = e.target.getAttribute("data-username");
          let dataStatus = e.target.getAttribute("data-status");
          document.querySelector('.member_status__edit').classList.remove('hidden');
          document.querySelector('.member_status__edit h2 span').innerText = dataId;
          document.querySelector('.member_status__edit input[name="id"]').value = dataId;
          document.querySelector('.member_status__edit label[for="username"]').innerText = "Account： " + dataUsername;
          document.querySelector('.member_status__edit input[name="member_status"]').value = dataStatus;
        })
      })

  </script>

</body>
</html>
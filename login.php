<?php
  require_once "pdo.php";
  session_start();

   unset($_SESSION['username']);
   unset($_SESSION['user_id']);
  if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
  }

  // Salt code for MD5 hashing
  $salt = 'xyz';
  if(isset($_POST['username']) && isset($_POST['pass'])){
    $check = hash('md5',$salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, username FROM users
      WHERE username = :un AND password = :pw');
    $stmt->execute(array(':un'=>$_POST['username'], ':pw'=> $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false){
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['success'] = "Logged in";
      header("Location: index.php");

      return;
    } else {
      $_SESSION['error'] = 'Invalid Login';
      header("Location: login.php");
      return;
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>LOG IN</title>
  </head>
  <body>
    <?php
    if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);

    //USER1, PASS123
    //USER2, PASS321
    }
    ?>
    <form method="post">
      <p>Username:
      <input type="text" name="username" id="username"></p>
      <p>Password:
      <input type="password" name="pass" id="pw"> </p>

      <input type="submit" value="Log In">
      <input type="submit" name="cancel" value="CANCEL">
    </form>
  </body>
</html>

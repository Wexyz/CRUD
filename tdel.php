<?php
  require_once 'pdo.php';
  session_start();
  if ( ! isset($_SESSION['username']) || ! isset($_SESSION['user_id']) ) {die('ACCESS DENIED: PLEASE LOG IN');}

  if(isset($_POST['cancel'])) header('Location: index.php');
  if(isset($_POST['delete'])){
    $stmt = $pdo->prepare('DELETE FROM title WHERE title_id = :tid');
    $stmt->execute(array(':tid'=>$_REQUEST['title_id']));
    $_SESSION['success'] = 'Book Deleted';
    header('Location: index.php');
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Deleting Book Entry</title>
  </head>
  <body>
    <h2>Confirm Delete?</h2>
    <form method="post">
      <button type="post" name="delete">Delete</button>
      <button type="post" name="cancel">Cancel</button>
    </form>
  </body>
</html>

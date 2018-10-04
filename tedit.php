<?php
  require_once 'pdo.php';
  require_once 'util.php';
  session_start();
  if ( ! isset($_SESSION['username']) || ! isset($_SESSION['user_id']) ) {die('ACCESS DENIED: PLEASE LOG IN');}

  if(isset($_POST['cancel'])) header('Location: index.php');

  $stmt = $pdo->prepare('SELECT title, author_id FROM title WHERE title_id = :tid AND author_id = :aid');
  $stmt->execute(array(':tid'=>$_REQUEST['title_id'], ':aid'=>$_REQUEST['author_id']));
  $rows = $stmt->fetch(PDO::FETCH_ASSOC);
  if(isset($_POST['delete'])) header('Location: tdel.php?title_id='.$_REQUEST['title_id']);

  if(isset($_POST['update'])){
    $stmt2 = $pdo->prepare('SELECT * FROM title WHERE title = :ti AND author_id = :aid');
    $stmt2->execute(array(':ti'=>$_POST['title'],':aid'=>$rows['author_id']));
    $rows2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    if($rows2 !== false) {
      $_SESSION['error'] = 'Authors Book Title Already Exists';
      header('Location: tedit.php?title_id='.$_REQUEST['title_id'].'&author_id='.$_REQUEST['author_id']);
      return;
    }

    if(strlen($_POST['title'])<1){
      $_SESSION['error'] = 'Entry is Required';
      header('Location: tedit.php?title_id='.$_REQUEST['title_id'].'&author_id='.$_REQUEST['author_id']);
      return;
    } else {
      $stmt2 = $pdo->prepare('UPDATE title SET title = :ti WHERE title_id = :tid');
      $stmt2->execute(array(':ti'=> $_POST['title'],
                            ':tid'=>$_REQUEST['title_id'],
                            ));
      $_SESSION['success'] = 'Book Title Updated';
      header('Location: index.php');
      return;
    }

  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Updating Book Entry</title>
    <style>
      .del{
        border: none;
        margin-top: 5px;
        padding: 5px 6px;
        color: white;
        background-color: #ff3333;
      }
      .topspace{
        margin-top: 2px;
      }
    </style>
  </head>
  <body>
    <?php notification(); ?>
    <h2>Update Book Title</h2>
    <h4>Current Input Title: <?= htmlentities($rows['title']) ?></h4>
    <form method="post">
      <input type="text" name="title" value="<?= htmlentities($rows['title']) ?>">
      </br>
      <button type="post" name="update" class="topspace">Update</button>
      <button type="post" name="cancel" class="topspace">Cancel</button>
    </br>
    <button type="post" name="delete" class="del">Delete Book</button>
    </form>
  </body>
</html>

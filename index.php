<!--
CREATE DATABASE portfoliocrud;
GRANT ALL ON portfoliocrud.* TO 'user'@'localhost' IDENTIFIED BY 'pass';
GRANT ALL ON portfoliocrud.* TO 'user'@'127.0.0.1' IDENTIFIED BY 'pass';

INSERT INTO users (username, password) VALUES ('user1','17c09c779920bc388857179679157866');
INSERT INTO users (username, password) VALUES ('user1','9e7418d249aca6be24c7751e163b4394');
-->
<?php
  require_once 'pdo.php';
  require_once 'util.php';
  session_start();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Joshua Soriaga - CRUD Portfolio</title>
    <script src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
  </head>
  <body>
    <!-- Notify if user is logged in or not -->
    <?php
    LoginCheck();
    if(isset($_POST['login'])){header("Location: login.php");}
    if(isset($_POST['logout'])){header("Location: logout.php");}
    if(isset($_POST['add'])){header('Location: add.php');}
    ?>

    <?php notification(); ?>
    <div id="list">
      <h2>CRUD Application Portfolio</h2>
      <h3>Author/Books Database</h3>
      <?php
      $stmt = $pdo->query('SELECT author, author_id FROM author');
      $stmt2 = $pdo->prepare('SELECT title, title_id, genre_id FROM title WHERE author_id = :aid');
      $stmt3 = $pdo->prepare('SELECT genre FROM genre WHERE genre_id = :gid');
    echo '<table border="1">';
        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<tr><th colspan="2">';
          echo '<p style="font-size: 20px;"><a href="authedit.php?author_id='.$rows['author_id'].'">'.htmlentities($rows['author']).'</a></p>';
    echo '</th></tr><tr>';
            $stmt2->execute(array(':aid'=>$rows['author_id']));
            while($rows2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
    echo '<td>';
              echo '<p style="text-indent: 15px;"><a href="tedit.php?title_id='.$rows2['title_id'].'&author_id='.$rows['author_id'].' ">'.htmlentities($rows2['title']).'</a></p>';
    echo '</td>';
              $stmt3->execute(array(':gid'=>$rows2['genre_id']));
              while($rows3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
                echo '<td>'.htmlentities($rows3['genre']).'</td>';
              }

    echo '</tr>';
            }

          }
    echo '</table>';
      ?>
    </div>

    <form method="post">
      <button type="post" name="add">Add New Entry</button>
      </br>
      <button type="post" name="login">Login</button>
      <button type="post" name="logout">Logout</button>
    </form>
  </body>
</html>

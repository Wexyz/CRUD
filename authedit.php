<?php
  require_once 'pdo.php';
  require_once 'util.php';
  session_start();
  if ( ! isset($_SESSION['username']) || ! isset($_SESSION['user_id']) ) {die('ACCESS DENIED: PLEASE LOG IN');}

  $stmt = $pdo->prepare('SELECT author FROM author WHERE author_id = :aid');
  $stmt->execute(array(':aid'=>$_REQUEST['author_id']));
  $rows=$stmt->fetch(PDO::FETCH_ASSOC);
  $in = htmlentities($rows['author']);

  if(isset($_POST['cancel'])) header('Location: index.php');
  if(isset($_POST['delete'])) header('Location: audelete.php?author_id='.$_REQUEST['author_id']);
  if(isset($_POST['update'])){
    $stmt2 = $pdo->prepare('SELECT author FROM author WHERE author = :a');
    $stmt2->execute(array(':a'=>$_POST['author']));
    $rows2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    if (isset($rows2['author'])) {
      $_SESSION['error'] = 'Author name already exists';
      header('Location: authedit.php?author_id='.$_REQUEST['author_id']);
      return;
    } else {
      $stmt = $pdo->prepare('UPDATE author SET author = :au
                            WHERE author_id = :aid');
      $stmt->execute(array(':aid'=>$_REQUEST['author_id'],
                           ':au'=>$_POST['author']));

      $_SESSION['success'] = 'Author Changed';
      header('Location: index.php');
      return;
    }
  }

  if(isset($_POST['insert'])){
    $author_id = [$_REQUEST['author_id']];
    for($i=1;$i<=2;$i++){
      if(isset($_POST['titl'.$i])){
        if(strlen($_POST['titl'.$i]) > 0){

            $_POST['title'] = $_POST['titl'.$i];
            $_POST['genre'] = $_POST['genres'.$i];

            $genre_id = addGenre($pdo);
            addTitle($pdo, $author_id, $genre_id);

            header('Location: index.php');
            return;

        } else {
          echo 'Blank entry detected';
        }
      } else {continue;}
    }
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Editing entries</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

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

    <h2>Editing Author Entry</h2>
    <p style="font-size: 20px;">Current Author Name: <?php echo $in ?></p>
    <form method="post">
      <input type="text" name="author" value="<?= $in ?>">
      </br>
      <button type="post" name="update" class="topspace">Update</button>
      <button type="post" name="cancel" class="topspace">Cancel</button>
      </br>
      <button type="post" name="delete" class="del">Delete Author</button>
    </form>
  </br>
      <h3>Existing Books</h3>
        <div>
          <?php
            $count = 1;
            $stmt = $pdo->prepare('SELECT title, title_id FROM title WHERE author_id = :aid');
            $stmt->execute(array(':aid'=>$_REQUEST['author_id']));
            while ($rows=$stmt->fetch(PDO::FETCH_ASSOC)) {
              echo '<p style="font-size: 15px;">'.$rows['title'];
              echo '|| <a href="tdel.php?title_id='.$rows['title_id'].'">Delete</a>';
              echo '</p>';
            }
          ?>
        </div>

        <form method="post">
          <input type="submit" id="addtitle" value="Add Book">
          <div id="adds"></div>
          <button type="post" name="insert" class="topspace">Insert</button>
        </form>

    <script>
      icount = 0;
      $(document).ready(function(){

        $('#addtitle').click(function(event){
          event.preventDefault();
          if(icount >= 2){
            alert('Maximum Book Entries: 2');
            return;
          }
          icount++;
          $('#adds').append(' \
            <p id=titl'+icount+'>Insert Title '+icount+': \
            <input type="text" name="titl'+icount+'"  />  \
            <span id="genr'+icount+' "> Genre: </span> \
            <input type="text" name="genres'+icount+'"  />\
            <input type="submit" value="-" onclick="$(\'#titl'+icount+'\').remove(); icount--; return false;">\
            </p>\
          ')
        });

      });
    </script>
  </body>
</html>

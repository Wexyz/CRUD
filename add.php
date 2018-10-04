<?php
  require_once 'pdo.php';
  require_once 'util.php';
  session_start();
if ( ! isset($_SESSION['username']) || ! isset($_SESSION['user_id']) ) {die('ACCESS DENIED: PLEASE LOG IN');}

if(isset($_POST['cancel'])){header('Location: index.php');return;}
if(isset($_POST['submit'])){

  if(strlen($_POST['author0']) < 1){
    $_SESSION['error'] = "Author is required"; //At least author entry is required
    header('Location: add.php');
    return;
  }
  else if(strlen($_POST['genre']) > 1 && strlen($_POST['title']) < 1) {
    $_SESSION['error'] = "Title is required before entering Genre"; // Genre w/o title is not allowed
    header('Location: add.php');
    return;
  }
  else{
      //INPUT AUTHOR DATA
      $author_id = [];
      $author_id = addAuthor($pdo);
      //SET GENRE TO NULL WHEN BLANK ENTRY. ELSE, ENTER.
      $genre_id = addGenre($pdo);
      //SET TITLE
      if(strlen($_POST['title'])>0){Addtitle($pdo, $author_id, $genre_id);}
      $_SESSION['success'] = 'Entry Added';
      header('Location: index.php');
      return;
  }

}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Adding entries</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
  </head>
  <body>
    <h2>Adding New Book Entry</h2>
    <?php
    LoginCheck();
    notification();?>

      <form method="post">
        <p>Author:
        <input type="text" name="author0" id="author"></p>
        <p style="font-size: 12px;">Add Co-Author
        <input type="submit" id="add_auth" value="+"></p>
        <div id="ca"></div>
        <p>Book title:
        <input type="text" name="title"></p>
        <p>Genre:
        <input type="text" name="genre" id="genre"></p>

        <input type="submit" name="submit" value="Submit">
        <input type="submit" name="cancel" value="Cancel">
      </form>
      <script>
        CoCount = 0;
        $(document).ready(function(){
          $('#author').autocomplete({
            source: 'authorlist.php'
          });
          $('#genre').autocomplete({
            source: 'genrelist.php'
          });


          $('#add_auth').click(function(event){
            event.preventDefault();
            if ( CoCount >= 2 ) {
                  alert("Co-Authors maximum of 2");
                  return;
              }
              CoCount++;
            $('#ca').append(
              '<p id="coid'+CoCount+'" style="font-size: 12px;">Co-Author: \
              <input type="text" name="author'+CoCount+'"/> \
              <input type="submit" value="-" onclick="$(\'#coid'+CoCount+'\').remove(); CoCount--; return false;">\
              </p>'
            );
          });
        });
      </script>
  </body>
</html>

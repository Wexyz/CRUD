<?php

  // Notify if user is logged in or not
  function LoginCheck(){
    if(isset($_SESSION['username']) && isset($_SESSION['user_id'])){
      echo('<p style="color: green">Logged in as '.htmlentities($_SESSION['username']).'</p>');
    }
    else{echo('<p style="color: red">Not logged in</p>');}
  }
  // Notifications
  function notification(){
    if(isset($_SESSION['error'])){
      echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
      unset($_SESSION['error']);
    }
    else if(isset($_SESSION['success'])){
      echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
      unset($_SESSION['success']);
    }
  }

  function addAuthor($pdo){
    $arr = [];
    for($i=0;$i<=2;$i++){
      if(!isset($_POST['author'.$i])) continue;

      $author_id = false;
      $stmt = $pdo->prepare('SELECT author_id FROM author WHERE author = :au');
      $stmt->execute(array(':au'=>$_POST['author'.$i]));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row !== false) {$author_id = $row['author_id']; $arr[]= $row['author_id'];}

      if($author_id === false){
        $stmt=$pdo->prepare('INSERT INTO author (author) VALUES (:au)');
        $stmt->execute(array(':au'=>$_POST['author'.$i]));
        $arr[] = $pdo->lastInsertId();
      }
    }
    return $arr;
  }

  function addGenre($pdo){
    if(strlen($_POST['genre'])<1){return 1;}
    else{
      $genre_id=false;
      $stmt = $pdo->prepare('SELECT genre_id FROM genre WHERE genre = :ge');
      $stmt->execute(array(':ge'=>$_POST['genre']));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
      if($row !== false) return $row['genre_id'];

      if($genre_id === false){
        $stmt=$pdo->prepare('INSERT INTO genre (genre) VALUES (:ge)');
        $stmt->execute(array(':ge'=>$_POST['genre']));
        return $pdo->lastInsertId();
      }
    }
  }
  //Add Title
  function Addtitle($pdo, array $author_id, $genre_id){
    //SUPPRESSES INPUT IF ENTRIES (AUTHOR & TITLE) ALREADY EXISTS0
    for($i=0;$i<=2;$i++){
      if(!isset($author_id[$i])) continue;

        $stmt = $pdo->prepare('SELECT title, author_id, title_id FROM title WHERE title = :ti AND author_id = :aid');
        $stmt->execute(array(':ti'=>$_POST['title'], ':aid'=>$author_id[$i]));

        $stmt2 = $pdo->prepare('SELECT title, title_id, genre_id FROM title WHERE title = :ti');
        $stmt2->execute(array(':ti'=>$_POST['title']));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        if($row !== false){
          echo '<script>console.log("Note: Book & Author Already Exists")</script>';
        } else if ($row2 !== false){
              $stmt = $pdo->prepare('INSERT INTO title (title_id, title, author_id, genre_id)
                                    VALUES (:tid, :ti, :aid, :gid)');
              $stmt->execute(array(
                              ':tid' => $row2['title_id'],
                              ':ti' => $_POST['title'],
                              ':aid' => $author_id[$i],
                              ':gid' => $row2['genre_id']
                            ));

        } else {
              $stmt = $pdo->prepare('INSERT INTO title (title, author_id, genre_id)
                                    VALUES (:ti, :aid, :gid)');
              $stmt->execute(array(
                              ':ti' => $_POST['title'],
                              ':aid' => $author_id[$i],
                              ':gid' => $genre_id
                            ));
            }
    }

  }

?>

<?php
  require_once "pdo.php";
  session_start();

  $stmt = $pdo->prepare('SELECT genre FROM genre
    WHERE genre LIKE :prefix');
  $stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));

  $retval = array();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['genre'];
  }

  echo(json_encode($retval, JSON_PRETTY_PRINT));
?>

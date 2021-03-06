<?php
  require_once "pdo.php";
  session_start();

  $stmt = $pdo->prepare('SELECT author FROM author
    WHERE author LIKE :prefix');
  $stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));

  $retval = array();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['author'];
  }

  echo(json_encode($retval, JSON_PRETTY_PRINT));
?>

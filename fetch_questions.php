<?php

  /*$questions = array(
    array(
      "name"=>"Archie",
      "food"=>"smoked oysters and chocolate muffins",
      "answer"=>"derby" ),
    array(
      "name"=>"Zooey",
      "food"=>"Dairy Queen shit",
      "answer"=>"derby" ),
    );*/
    
  try {
    // connect to the database
    $dbName = "derbyorpregnant";
    $username = "dorp_admin";
    $password = "whfs991";
    $dbh = new PDO( "mysql:host=localhost;dbname=$dbName", $username, $password );
    
    // turning on exceptions for query errors
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $selectQuery = "SELECT `name`, `display_question`, `comment`, `option` FROM `quiz_questions`";
    $questions = $dbh->query($selectQuery);
    
    // closing database
    $dbh = null;

    if ( $questions ) {
      $questionsArray = array();
      
      foreach ( $questions as $row ) {
        $rowArray = array( "name"=>$row['name'],
          "food"=>$row['display_question'],
          "comment"=>$row['comment'],
          "answer"=>$row['option'] );
        array_push($questionsArray, $rowArray);
      }
      
      echo json_encode( $questionsArray );
    }
  } catch( PDOException $e ) {
    echo $e -> getMessage();
  }

?>
<?php

$dbh = null;

if ($_POST) {
  $action = $_POST["action"];
  $items = $_POST["items"];
  
  if ( isset( $action, $items ) ) {
    if ( $action == "approve" ) {
      approve( json_decode( $items ) );
    } elseif ( $action == "remove" ) {
      remove( json_decode($items) );
    }
  }
}


/**
 * @param array $itemsToApprove
 * @return boolean whether the itemsToApprove were added to the quiz_questions
 * table
 * @throws PDOException
 */
function approve( $itemsToApprove ) {
  $wasInsertSuccessful = false;
  
  dbConnect();
  
  try {
    // prepare SQL INSERT statement
    $sql = array();
    foreach( $itemsToApprove as $currentItem ) {
      $sql[] = "(DEFAULT,'" . mysql_real_escape_string($currentItem->name) .
        "','" . mysql_real_escape_string($currentItem->food) .
        "','" . mysql_real_escape_string($currentItem->comment) .
        "','" . mysql_real_escape_string($currentItem->option) . "')";
    }
    $sqlString = implode( ",", $sql );
    $insertQuery = "INSERT INTO quiz_questions VALUES " . $sqlString;
    
    // insert into DB
    global $dbh;
    $wasInsertSuccessful = $dbh->query( $insertQuery );
    if ( $wasInsertSuccessful ) {
      updateStatus($itemsToApprove, 'approved');
      
      echo "Successfully approved ".count($itemsToApprove)." submissions.";
    } else {
      echo "Unable to approve submissions.";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  
  $dbh = null; // close the DB
  
  return $wasInsertSuccessful;
}

function remove( $itemsToRemove ) {
  print_r( $itemsToRemove );
}

/**
 * @param array $itemsToUpdateStatus which items to UPDATE
 * @param string $status should be either 'approved' or 'denied'
 * @return bool whether it was able to run the UPDATE successfully
 */
function updateStatus( $itemsToUpdate, $status ) {
  try {
    global $dbh;
    
    // prepare SQL UPDATE statement to change user_submission status
    foreach( $itemsToUpdate as $currentItem ) {
      $updateQuery = "UPDATE user_submissions SET status='" .
        mysql_real_escape_string($status) . "' WHERE name = '" .
        mysql_real_escape_string($currentItem->name) . "'";
        
      if (!$dbh->query( $updateQuery )) {
        echo "Unable to update status of user submission from " .
          $currentItem->name . ".";
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function dbConnect() {
  try {
    // connect to the database
    $dbName = "derbyorpregnant";
    $username = "dorp_admin";
    $password = "whfs991";
    global $dbh;
    $dbh = new PDO( "mysql:host=localhost;dbname=$dbName", $username, $password );
    
    // turning on exceptions for query errors
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  } catch (PDOException $e ) {
    echo $e->getMessage();
  }
}

?>
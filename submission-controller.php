<?php

$dbh = null;

if ($_POST) {
  $action = $_POST["action"];
  $items = $_POST["items"];
  
  if ( isset( $action, $items ) ) {
    dbConnect();
    
    if ( $action == "approve" ) {
      approve( json_decode( $items ) );
    } elseif ( $action == "remove" ) {
      remove( json_decode( $items ) );
    }
  }
}


/**
 * @param array $itemsToApprove expects an array of objects with properties
 * name, food, comment, and option
 * @return boolean whether the itemsToApprove were added to the quiz_questions
 * table
 * @throws PDOException
 */
function approve( $itemsToApprove ) {
  $wasInsertSuccessful = false;
  
  try {
    // prepare SQL INSERT statement
    $sql = array();
    foreach( $itemsToApprove as $currentItem ) {
      $sql[] = "(DEFAULT,'" . $currentItem->name .
        "','" . $currentItem->food .
        "','" . $currentItem->comment .
        "','" . $currentItem->option . "')";
    }
    $sqlString = implode( ",", $sql );
    $insertQuery = "INSERT INTO quiz_questions VALUES " . $sqlString;
    
    // insert into DB
    global $dbh;
    $wasInsertSuccessful = $dbh->query( $insertQuery );
    if ( $wasInsertSuccessful ) {
      $itemsNotApproved = updateStatus($itemsToApprove, 'approved');
      
      if (count($itemsNotApproved) > 0) {
        echo "Unable to update status of the following items:\n";
        foreach ($itemsNotUpdated as $item) {
          echo implode(", ", $item) . "\n";
        }
      }
      
      echo "Successfully approved " .
        (count($itemsToApprove) - count($itemsNotApproved)) .
        " submissions.";
    } else {
      echo "Unable to approve submissions.";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  
  $dbh = null; // close the DB
  
  return $wasInsertSuccessful;
}


/**
 * Set status in table user_submissions to 'removed' for each item passed in
 * 
 * @param array $itemsToRemove expects an array of objects with properties
 * name, food, comment, and option
 */
function remove( $itemsToRemove ) {
  $itemsNotRemoved = updateStatus($itemsToRemove, 'removed');
  
  if (count($itemsNotRemoved) > 0) {
    echo "Unable to update status of the following items:\n";
    foreach ($itemsNotRemoved as $item) {
      echo "Name: " . $item->name . "\n";
      echo "Question: " . $item->food . "\n\n";
    }
  }
  
  echo "Successfully denied " .
    (count($itemsToRemove) - count($itemsNotRemoved)) .
    " submissionszzzz.";
}

/**
 * @param array $itemsToUpdateStatus which items to UPDATE
 * @param string $status should be either 'approved' or 'denied'
 * @return array which items could not be updated
 */
function updateStatus( $itemsToUpdate, $status ) {
  $failedUpdates = array();  
    
  try {
    global $dbh;
    
    // prepare SQL UPDATE statement to change user_submission status
    foreach( $itemsToUpdate as $currentItem ) {
      $updateQuery = $dbh->prepare("UPDATE user_submissions SET status = ? WHERE name = ?");
      
      if (!$updateQuery->execute( array($status, $currentItem->name ) )) {
        $failedUpdates[] = $currentItem;
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  
  return $failedUpdates;
}

function dbConnect() {
  try {
    // connect to the database
    $dbName = "derbyorpregnant";
    $username = "root";
    $password = "";
    global $dbh;
    $dbh = new PDO( "mysql:host=localhost;dbname=$dbName;charset=utf8", $username, $password );
    
    // turning on exceptions for query errors
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  } catch (PDOException $e ) {
    echo $e->getMessage();
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Derby or Pregnant</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">

      <?php
        try {
          // connect to the database
          $dbName = "derbyorpregnant";
          $username = "root";
          $password = "";
          $dbh = new PDO( "mysql:host=localhost;dbname=$dbName", $username, $password );
          
          // turning on exceptions for query errors
          $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
          
          // display all submissions
          $selectQuery = "SELECT `name`, `food`, `option` FROM `user_submissions`";
          $submissions = $dbh->query($selectQuery);
      
          if ( $submissions ) {
      ?>
      
      <table class="table">
        <thead>
          <tr>
            <th ng-click="edit()">Name</th>
            <th ng-click="edit()">Food</th>
            <th ng-click="edit()">Comment</th>
            <th>Option</th>
            <th><input type="checkbox"> Select All</th>
          </tr>
        </thead>
        <tbody>
          
          <!-- output each user_submission row to table -->
          <?php 
            foreach ( $submissions as $row ) {
          ?>          
          <tr>
            <td><?php echo $row['name'] ?></td>
            <td><?php echo $row['food'] ?></td>
            <td></td>
            <td><?php echo $row['option'] ?></td>
            <td><input type="checkbox"></td>
          </tr>
          <?php
            }
          ?>
          
        </tbody>
      </table>

      <input type="button" value="Approve" />
      <input type="button" value="Delete" />
      
      <?php
          // end if ( $submissions )
          } else {
            echo "<p>Couldn't get user submissions.</p>";
          }
                  
          // closing database
          $dbh = null;
        } catch( PDOException $e ) {
          echo $e -> getMessage();
        }
      
      ?>

    </div><!-- container -->
  </body>
</html>
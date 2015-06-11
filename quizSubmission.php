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

      if ( $_POST ) {
        try {
          // connect to the database
          $dbName = "derbyorpregnant";
          $username = "root";
          $password = "";
          $dbh = new PDO( "mysql:host=localhost;dbname=$dbName", $username, $password );
          
          // turning on exceptions for query errors
          $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

          // now that we're connected to the database, get form fields and insert into database
          $name = $_POST['submissionName'];
          $food = $_POST['submissionFood'];
          $option = $_POST['submissionOption'];

          $stmt = $dbh->prepare("INSERT INTO user_submissions VALUES (DEFAULT, ?, ?, ?, 'new')");

          if ( $stmt->execute( array($name, $food, $option) ) ) {
            echo "<p>Thanks for the submission, " . $name . "!</p>";
            echo '<a href="http://archene.com">Return to Home</a>';

            // display all submissions
            $selectQuery = "SELECT `name`, `food`, `option` FROM `user_submissions`";
            $submissions = $dbh->query($selectQuery);

            if ( $submissions ) {
              echo "<table class=\"table\">
  						<thead>
  							<tr>
  								<th>Name</th>
  								<th>Food</th>
  								<th>Option</th>
  							</tr>
  						</thead>
  						<tbody>";
              foreach ( $submissions as $row ) {
                echo "<tr>
							<td>" . $row['name'] . "</td>
							<td>" . $row['food'] . "</td>
							<td>" . $row['option'] . "</td>
						  </tr>";
              }
              echo "</tbody>
              </table>";
            } else {
              echo "<p>Couldn't get user submissions.</p>";
            }
          } else {
            echo "<p>Sorry. We were unable to add your submission.</p>";
          }

          // closing database
          $dbh = null;
        } catch( PDOException $e ) {
          echo $e -> getMessage();
        }
      }
      ?>
    </div><!-- container -->
  </body>
</html>
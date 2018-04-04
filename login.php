<?php include ("includes/init.php");
$current_page = "login"; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Login</title>
</head>

<body>
  <div class = "wrapper">
    <div class = "static">
      <?php include ("includes/nav.php"); ?>
    </div>
    <div class = "content">
      <?php 
      // display the form only when there is no user logged in. 
      if (!$current_user) {
      echo "<form id=\"userlogin\" action=\"login.php\" method=\"post\">
            <ul>
            <li>
              <label> Username: </label>
              <input type=\"text\" name=\"username\" required />
            </li>
            <li>
              <label> Password: </label>
              <input type=\"password\" name=\"password\" required />
            </li>
            <li> <button name=\"login\" type=\"submit\"> Log In </button> </li>
          </ul>
        </form>";
      }

      // someone already is logged in 
      else {
        echo "<p> Logged in as $current_user";
      }
      ?>
    </div>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

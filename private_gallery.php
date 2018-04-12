<?php include ("includes/init.php");
$current_page = "private_gallery"; ?>
<!DOCTYPE html>
<html lang="en">

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
      $image_exist = 1;
      // display the form only when there is no user logged in.
      if (!$current_user) {
      echo "<form id=\"userlogin\" action=\"private_gallery.php\" method=\"post\">
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
      // someone has logged in. Show his/her own images
      else {
        echo "<div class=\"window_private\">";
        $sql = "SELECT images.*, accounts.realname
        FROM images INNER JOIN accounts
        ON images.user_id = accounts.id
        WHERE accounts.username = :username;";
        $params = array(":username" => $current_user["username"]);
        $records = exec_sql_query($db, $sql, $params) -> fetchAll();
        if (!$records or (count($records)==0)) {
          $image_exist = 0;
        }
        galleryArrangement($records);
        echo "</div>";
      }
      ?>
    </div>
    <?php print_message($image_exist); ?>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

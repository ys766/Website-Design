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
      // and no new user wants to register
      if (!$current_user && !isset($_POST["register"])) {
      echo "<form class=\"userlogin\" action=\"private_gallery.php\" method=\"post\">
            <ul>
            <li>
              <label> Username*: </label>
              <input type=\"text\" name=\"username\" required/>
            </li>
            <li>
              <label> Password*: </label>
              <input type=\"password\" name=\"password\" required/>
            </li>
            <li> <button name=\"login\" type=\"submit\"> Log In </button>
            </li>
          </ul>
        </form>";
        echo "<form id=\"userregister\" action=\"private_gallery.php\" method=\"post\">
        <button name=\"register\" type=\"submit\"> New user? Sign up now </button>
        </form>
        ";
      }
      // some new user wants to register an account
      else if (!$current_user && isset($_POST["register"])) {
        echo "<form class=\"userlogin\" action=\"private_gallery.php\" method=\"post\">
        <ul>

        <li>
        <label> First Name*: </label>
        <input type=\"text\" name=\"firstname\" required />
        </li>

        <li>
        <label> Last Name*: </label>
        <input type=\"text\" name=\"lastname\" required />
        </li>

        <li>
        <label> Email Address*: </label>
        <input type=\"email\" name=\"email\" required />
        </li>

        <li>
        <label> Username*: </label>
        <input type=\"text\" name=\"username\" />
        </li>

        <li>
        <label> New password*: </label>
        <input type=\"password\" name=\"password1st\" required />
        </li>

        <li>
        <label> Confirm password*: </label>
        <input type=\"password\" name=\"password2nd\" required />
        </li>

        <li> <button name=\"newUserInfo\" type=\"submit\"> Submit </button> </li>

        </ul>
        </form>
        ";
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

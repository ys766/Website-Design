<?php
$pages = array("index" => "Home",
"gallery" => "Gallery",
"private_gallery" => "My Gallery",
"upload" => "Upload",
"logout" => "Logout");

// show database errors during development.
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

// execute an SQL query and return the results.
function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db -> exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

function record_message($message) {
  global $messages;
  array_push($messages, $message);
}

// function print_message will print the messages to the user in a collapsible div
function print_message($delete_image_id = NULL) {
  global $messages;
  if(count($messages) > 0) {
    echo "<div id=\"alert\">";

    foreach($messages as $message) {
      $link = "#alert";
      $msg = htmlspecialchars($message) . "<span class=\"clsmsg\">&times;</span>";

      if ($delete_image_id) {
        $link = "gallery.php";
        $msg = $msg . "<br /><br />Click to view the Gallery>>>";
      }

      $string = "<h3><a href=\"" . $link . "\">" . $msg . "</a></h3>";

      echo $string;
    }

    echo "</div>";
  }
}

// open or initialize the database
$db = open_or_init_sqlite_db("data.sqlite", "init/init.sql");
$messages = array();
// the function check_login checks whether if a user has logged in for every page.
// return value: id, the username and the real name of the account holder
function check_login() {

  global $db;

  // session cookie is present
  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $sql = "SELECT id, username, realname FROM accounts WHERE session = :session;";
    $params = array(":session" => $session);
    $results = exec_sql_query($db, $sql, $params) -> fetchAll();

    if ($results) {
      $account = $results[0];
      return array("id" => $account["id"],
                   "username" => $account["username"],
                   "realname" => $account["realname"]);
    }
    // no one has logged in.
    return NULL;
  }
}

// function log_in checks if the username and password already exist in the database.
// it returns TRUE if and only if the username has logged in and a session cookie has been
// created for that username.
function log_in($username, $password) {

  global $db;

  if ($username and $password) {

    $sql = "SELECT * FROM accounts WHERE username = :username;";
    $params = array(":username" => $username);
    $record = exec_sql_query($db, $sql, $params) -> fetchAll();

    if($record) {
      $account = $record[0];

      // check password
      if (password_verify($password, $account["password"])) {

        // generate a session for the user
        $session = uniqid();
        $sql = "UPDATE accounts SET session = :session WHERE id = :user_id;";
        $params = array(":session" => $session,
        ":user_id" => $account["id"]);
        $record_login = exec_sql_query($db, $sql, $params);

        // success log-in
        if ($record_login) {
          setcookie("session", $session, time()+3600);
          record_message("Logged in as " . $account["username"]);
          return array("id" => $account["id"],
                       "username" => $account["username"],
                       "realname" => $account["realname"]);
        }

        else {
          record_message("Login failed");
        }
      }
      else { record_message("Invalid username or password"); }
    }
    else { record_message("Invalid username or password"); }
  }
  else { record_message("No username or password is given"); }

  return NULL;
}


// the function log_out lets a user log out of the system has not logged in for every page.
function log_out() {
  global $db;
  global $current_user;

  // there is a user who has logged in the system. Then log him/her out.
  if ($current_user) {
    $sql = "UPDATE accounts SET session = :session WHERE username = :username;";
    $params = array(":session" => NULL,
    ":username" => $current_user["username"]);
    if (!exec_sql_query($db, $sql, $params)) {
      record_message("Logout failed");
    }
    else {
      record_message("Successfully logged out");
    }
  }
  // remove cookie
  setcookie("session", "", time()-3600);
  $current_user = NULL;
}

// check if we need to log in
if(isset($_POST["login"])) {

  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  $current_user = log_in($username, $password);

}
else {
  $current_user = check_login();
}

CONST NUM_COLUMNS = 4;
CONST PATH_IMG = "/uploads/images/";

/*
showTags displays the tags according to different situations. The input
$tag is an array of tag names. If $single is true, then the tags do not
have a link except for the current user can delete the tags. Otherwise, the links
are used to view all images corresponding to a single tag. When $delete is true,
Add little close icon to the tag so the user can delete the tags.
*/
function showTags($tags, $single=FALSE, $delete=FALSE, $image_id = NULL) {
  if ($tags) {
    global $current_user;
    global $tag_name;
    if ($tags) {
      echo "<div class=\"tags\">";
      if ($delete and $image_id) {
        echo "<form id=\"delete_tag\" action=\"single_img.php?" .
        http_build_query(array("image_id" => $image_id)) . "\" method = \"post\">";
      }
      echo "<ul id=\"tag_list\">";
      foreach($tags as $tag) {
        $string = "<li>";
        // view all images in the gallery, where clicking on the tag
        // can lead to a view of images with that clicked tag
        if (!$single) {
          $string = $string . "<a ";
          if (strlen($tag_name) > 0 and ($tag_name == $tag["tag_name"])) {
            $string = $string . "class=\"selectedTag\" ";
          }
          echo $string. "href=\"gallery.php?".http_build_query(array("tag"=>$tag["tag_name"]))."\">#".
          ucfirst($tag["tag_name"])."</a></li>";
        }
        // view one image but does not show the delete functionality
        else if (!$delete){
          echo $string . "<span class=\"Text\">#". ucfirst($tag["tag_name"]) . "</span></li>";
        }
        // view one image with tag deletion functionality
        else {
          echo $string . "<button class=\"deletebutton\" title=\"Click to delete\"
          type=\"submit\" name=\"tag_delete\" value=\"" .
          htmlspecialchars($tag["tag_name"]) ."\">#" . ucfirst($tag["tag_name"]) . "<span> &times;
          </span><span class=\"tooltip\">Click to delete</span></button></li>";
        }
      }
      echo "</ul>";
      if ($delete) {
        echo "</form>";
      }
      echo "</div>";
    }
  }
  else if ($single){
    record_message("No tags found for this image. Tag it now");
  }
}
/*
The function showImage takes a single SQL query result as the input,
and displays the corresponding queried image on the website. Also,
this function displays all relevant information pertaining to that
image in a divsion overlayed over the image. The information is listed
as the bullet point list.
*/
function showImage($record) {
  $image_id = $record["id"];
  $img_ext = $record["image_ext"];
  $file_name = PATH_IMG . $image_id . "." . $img_ext;
  echo "<div class=\"container\">" .
  "<img src=" . $file_name . " alt=$image_id class=\"galleryImages\">";
  echo "<div class=\"textoverlay\"><div class=\"imageDescription\"><ul>
  <li>Image Name: " . htmlspecialchars($record["image_name"]) . "</li>" .
  "<li>Description: " . htmlspecialchars($record["description"]) . "</li>" .
  "<li>Photographed by: " . htmlspecialchars($record["realname"]) . "</li>";
  if ($record["citation"]) {
    echo "<li>Source: <a href=\"" . htmlspecialchars($record["citation"]) . "\">" .
    htmlspecialchars($record["citation"]) . "</a></li><li></li>";
  }
  echo "<li><a class = \"img_link\" href=\"single_img.php?" .
  http_build_query(array("image_id" => $image_id)). "\">View</a></li></ul></div></div></div>";
}

function galleryArrangement($records) {
  if ($records) {
  shuffle($records); // randomize the photo arrangments.
  $length = count($records);
  $num_per_column = ceil($length /  NUM_COLUMNS);
  for ($i = 0; $i < NUM_COLUMNS; $i++) {
    echo "<div class=\"column\">";
    // all columns but the last one
    if ($i < NUM_COLUMNS - 1) {
      for ($j = 0; $j < $num_per_column; $j++) {
        $index = $i * $num_per_column + $j;
        if ($index < $length){
          showImage($records[$index]);
        }
      }
    }
    // the last column
    else {
      $index = $i * $num_per_column;
      while($index < $length) {
        showImage($records[$index]);
        $index = $index + 1;
      }
    }
    echo "</div>";
  }

}
else {
  record_message("No requested image exists in our database");
}
}
?>

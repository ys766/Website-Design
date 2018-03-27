<?php
$pages = array("index" => "Home",
               "gallery" => "Gallery",
               "login" => "Log In",
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
          record_message("Logged in as " . $account["realname"]);
          return TRUE;
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

  return FALSE;
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

  log_in($username, $password);

}

$current_user = check_login();
?>

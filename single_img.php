<?php
include ("includes/init.php");

$current_page = "gallery";
$tag_name = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Gallery</title>
</head>

<body>
  <div class = "wrapper">
    <div class = "static">
      <?php include ("includes/nav.php"); ?>
    </div>
    <?php
    CONST PATH_IMG = "/uploads/images/";
    // view a single image
    if (isset($_GET["image_id"])){
      $image_id = filter_var($_GET["image_id"], FILTER_SANITIZE_NUMBER_INT);
      // find all information about that image, including filename, and the user's name
      $sql = "SELECT images.*, accounts.realname
              FROM images INNER JOIN accounts
              ON images.user_id = accounts.id
              WHERE images.id = :id;";
      $params = array(":id" => $image_id);
      $records = exec_sql_query($db, $sql, $params) -> fetchAll();
      // the requested single image is available
      if ($records) {
        $sql_tag = "SELECT tags.tag_name
                    FROM tags INNER JOIN image_tag
                    ON tags.id = image_tag.tag_id
                    WHERE image_tag.image_id = :id;";
        $tags = exec_sql_query($db, $sql_tag, $params) -> fetchAll();
        echo "<div class=\"content\">";
        $record = $records[0];
        $file_name = PATH_IMG. $record["id"] . "." . $record["image_ext"];
        echo "<div class='single_img'><img src=" . $file_name . " class='img_single'></div>";
        echo "<div class='details'>";
        echo "<ul><li> Image Name: " . $record["image_name"] . "</li>" .
        "<li> Description: " . $record["description"] . "</li>" .
        "<li> Photographed by: " . $record["realname"] . "</li>" .
        "<li> <a href=" . $record["citation"] . ">Source: " . $record['citation'] . "</a></li>".
        "</ul>";
        $form = "<form id=\"addtag\" action=\"single_img.php\" method=\"POST\">
          <label> Add new tag? </label>
          <input type=\"text\" name=\"add_tag\" />
          <button name=\"addtagsubmit\" type=\"submit\"> Add! </button>
        </form>";
        echo $form;
        echo "</div>";
        showTags($tags, TRUE);
      }
      echo "</div>";
    }

    // add a new tag
    if (isset($_POST["addtagsubmit"])) {
      $newtag = filter_input(INPUT_POST, "add_tag", FILTER_SANITIZE_STRING);
      $newtag = strtolower(trim($newtag));

      // check for existence of the input tag
      $sqlnewtag = "SELECT id FROM tags WHERE tags.tag_name = :input_tag";
      $params = array(":input_tag" => $newtag);
      $record = exec_sql_query($db, $sqlnewtag, $params);
      if ($record) {
        echo "<h3>Tag already exists </h3>";
      }
    }
    ?>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

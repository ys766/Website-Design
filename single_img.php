<?php
include ("includes/init.php");
$current_page = "gallery";
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
    <div class="content">
    <?php

    // if no image_id exists, redirect to the gallery page since single_img is
    // reserved for viewing a single image ONLY
    if (!isset($_GET["image_id"])) {
      header('Location: gallery.php');
    }

    else {
      $image_id = filter_var($_GET["image_id"], FILTER_SANITIZE_NUMBER_INT);
      // invalid image id
      if (strlen($image_id) <= 0) {
        header('Location: gallery.php');
      }
      $delete_image_id = NULL;
    }

    // check if $current_user uploads this image. $records_user is not NULL
    // if and only if current user uploads this single image
    $records_user=NULL;
    if ($current_user) {
      $sql = "SELECT id FROM images WHERE images.user_id = :user_id AND images.id = :image_id;";
      $params = array(":user_id" => $current_user["id"],
      ":image_id" => $image_id);
      $records_user = exec_sql_query($db, $sql, $params) -> fetchAll();
    }

    // delete image button is pressed. Delete the image. After deleting the image,
    // shows a message and nothing else.
    if (isset($_POST["deleteimgbutton"]) and $records_user) {
      $delete_id = filter_var($_POST["deleteimgbutton"], FILTER_SANITIZE_NUMBER_INT);
      // delete the image_tag relation
      $sql_delete_img_tag = "DELETE FROM image_tag
      WHERE image_tag.image_id = :image_id;";
      $params_delete_img = array(":image_id" => $image_id);
      // delete the image from the images table
      $sql_delete_img = "DELETE FROM images
      WHERE images.id = :image_id;";
      if (exec_sql_query($db, $sql_delete_img_tag, $params_delete_img)
      and exec_sql_query($db, $sql_delete_img, $params_delete_img)) {
        record_message("Selected image deleted");
        $delete_image_id = $image_id;
      }
      else {
        record_message("Failed to delete image");
      }

    }

    // image is not deleted. Then show the single image. Add tag or delete tags
    // depending on the user's decisions.
    else if (!isset($_POST["deleteimgbutton"])){
      // view a single image
      // find all information about that image, including filename, and the user's name
      $delete_image_id = NULL;
      $sql = "SELECT images.*, accounts.realname
      FROM images INNER JOIN accounts
      ON images.user_id = accounts.id
      WHERE images.id = :id;";
      $params = array(":id" => $image_id);
      $records = exec_sql_query($db, $sql, $params) -> fetchAll();
      // the requested single image is available
      if ($records) {
        $record = $records[0];
        $file_name = PATH_IMG. $record["id"] . "." . $record["image_ext"];
        echo "<div class='single_img'><img src=\"" . $file_name . "\" class='img_single' alt=\"gallery\">";
        // if user is the uploader, then show the delete image button
        if ($records_user) {
          echo "<form id=\"deleteImg\" action=\"#\" method=\"post\">
          <button class=\"deleteImgButton\" type=\"submit\" name=\"deleteimgbutton\" value=\">".$image_id.
          "\">Delete image</button></form>";
        }
        echo "</div>";

        echo "<div class='details'>";
        echo "<ul><li> Image Name: " . htmlspecialchars($record["image_name"]) . "</li>" .
        "<li> Description: " . htmlspecialchars($record["description"]) . "</li>" .
        "<li> Photographed by: " . htmlspecialchars($record["realname"]) . "</li>";
        if ($record["citation"]) {
          echo "<li> <a href=" . htmlspecialchars($record["citation"]) .
          ">Source: " . htmlspecialchars($record['citation']) . "</a></li>";
        }
        echo "</ul>";
        echo "<form id=\"addtag\" action=\"single_img.php?". http_build_query(array("image_id" => $image_id)) .
        "\" method=\"post\">
        <label> Add a new tag? </label>
        <input type=\"text\" name=\"add_tag\" />
        <button name=\"addtagsubmit\" type=\"submit\"> Add! </button>
        </form>";
        echo "</div>";
      }
      // The requested image_id does not exist
      else {
        record_message("The requested image does not exist in the database");
      }

      // add a new tag
      if (isset($_POST["addtagsubmit"])) {
        $newtag = filter_input(INPUT_POST, "add_tag", FILTER_SANITIZE_STRING);
        $newtag = preg_replace("/[^a-zA-Z0-9]+/", "", $newtag);
        $newtag = strtolower(trim($newtag));

        // check for existence of the input tag
        $sqlnewtag = "SELECT id FROM tags WHERE tags.tag_name = :input_tag;";
        $params = array(":input_tag" => $newtag);
        $newrecords = exec_sql_query($db, $sqlnewtag, $params) ->fetchAll();

        // the added tag already exists in the tags table, but not necessarily
        // in the image_tag table where the image_id is refers to the current image
        if ($newrecords) {
          $tag_exist_id = $newrecords[0]["id"];
          // check if the image already has that tag.
          $sql_check_link = "SELECT *
                             FROM image_tag
                             WHERE image_tag.image_id = :image_id
                             AND image_tag.tag_id = :tag_id;";
          $params_check_link = array(":image_id" => $image_id,
                                     ":tag_id" => $tag_exist_id);
          // duplicate tags for this image
          if (exec_sql_query($db, $sql_check_link, $params_check_link) -> fetchAll()) {
            record_message("Tag already exists for this image");
          }

          // although the tag exists in tags table, this image does not have that tag.
          else {
            $sql_link_tag = "INSERT INTO image_tag(image_id, tag_id)
                             VALUES(:image, :tag);";
            $params_link = array(":image" => $image_id,
                                 ":tag" => $tag_exist_id);
            if (!exec_sql_query($db, $sql_link_tag, $params_link)) {
              record_message("Failed to attach this tag");
            }
          }
        }
        // tag not exist
        else {
          $sql_create_tag = "INSERT INTO tags(tag_name) VALUES(:newtagname);";
          $params_new = array(":newtagname" => $newtag);
          if (exec_sql_query($db, $sql_create_tag, $params_new)) {
            $newtagid = $db -> lastInsertId("id");
            $sql_link_tag = "INSERT INTO image_tag(image_id, tag_id) VALUES(:image, :tag);";
            $params_link = array(":image" => $image_id,
                                 ":tag" => $newtagid);
            if (exec_sql_query($db, $sql_link_tag, $params_link)) {
              record_message("Tag #$newtag successfully created");
            }
            else {
              record_message("Failed to create this new tag");
            }
          }
          else {
            record_message("Failed to create this new tag");
          }
        }
      }

      // delete a tag and the current user is the uploader
      if (isset($_POST["tag_delete"]) and $records_user) {
        // sanitize the tag to be deleted
        $delete_tag = filter_var($_POST["tag_delete"], FILTER_SANITIZE_STRING);
        $delete_tag = preg_replace("/[^a-zA-Z0-9]+/", "", $delete_tag);
        $delete_tag = trim($delete_tag);

        $sql_delete_tag = "DELETE FROM image_tag
                           WHERE image_tag.tag_id IN (SELECT tags.id
                                                      FROM tags INNER JOIN image_tag
                                                      ON tags.id = image_tag.tag_id
                                                      WHERE tags.tag_name = :tag_name)
                           AND image_tag.image_id = :image_id;";
        $params_delete = array(":image_id" => $image_id,
                               ":tag_name" => $delete_tag);
          if (!exec_sql_query($db, $sql_delete_tag, $params_delete)) {
            record_message("Failed to delete tag #$delete_tag");
          }
        }

        // Obtain all tags corresponding to this image
        $sql_tag = "SELECT tags.tag_name
                    FROM tags INNER JOIN image_tag
                    ON tags.id = image_tag.tag_id
                    WHERE image_tag.image_id = :id;";
        $params = array(":id" => $image_id);
        $tags = exec_sql_query($db, $sql_tag, $params) -> fetchAll();

          // either no user logged in or the current user is not the uploader
          // Then only show the tags without the delete functionality
          if (!$current_user or !$records_user) {
            showTags($tags, TRUE, FALSE);
          }
          // current user is the image uploader, then show the tag delete functionality
          else if ($records_user) {
            showTags($tags, TRUE, TRUE, $image_id);
          }
        }
      ?>
    </div>
    <?php
    print_message($delete_image_id);
    ?>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

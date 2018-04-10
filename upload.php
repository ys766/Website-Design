<?php include ("includes/init.php");
$current_page = "upload";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Upload</title>
</head>

<body>
  <div class = "wrapper">
    <div class = "static">
      <?php include ("includes/nav.php"); ?>
    </div>
    <div class = "content">
      <?php
      const MAX_FILE_SIZE = 1000000;
      const IMG_UPLOADS_PATH = "uploads/images/";
      // only logged in user can upload images
      if ($current_user and (isset($_POST["submit_upload"]))) {
        $upload_info = $_FILES["img_file"];
        $upload_desc = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
        $upload_tags = filter_input(INPUT_POST, "tags", FILTER_SANITIZE_STRING);
        $source = filter_input(INPUT_POST, "source", FILTER_VALIDATE_URL);

        // only retain a-z A-Z 0-9 in the tags.
        $upload_tags = preg_replace("/[^a-z0-9]+/", " ", strtolower($upload_tags));
        $upload_tags = trim($upload_tags);
        // tags is an array storing all user input tags
        $tags = preg_split("/[\s]+/", $upload_tags);

        if ($upload_info['error'] == UPLOAD_ERR_OK) {
          $upload_name = basename($upload_info["name"]);
          $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) );

          $sql = "INSERT INTO images (image_name, image_ext, description, user_id, citation)
          VALUES (:filename, :extension, :description, :user_id, :citation);";
          $params = array(
            ':extension' => $upload_ext,
            ':filename' => $upload_name,
            ':description' => $upload_desc,
            ":user_id" => $current_user["id"],
            ":citation" => $source
          );

          if (exec_sql_query($db, $sql, $params)) {

            $file_id = $db->lastInsertId("id");

            // add image tag relation
            if ($tags){

            $visited_tags = array();

            foreach ($tags as $tag) {
              // repeated tags input
              if (in_array($tag, $visited_tags)) {
                continue;
              }
              array_push($visited_tags, $tag);
              $tag_id;
              $sql_check_tag = "SELECT id FROM tags WHERE tags.tag_name = :input_tag;";
              $params_check_tag = array("input_tag" => $tag);
              $results = exec_sql_query($db, $sql_check_tag, $params_check_tag) -> fetchAll();

              // the tag already exists in the tags table
              if ($results) {
                $tag_id = $results[0]["id"];
              }
              // new tag does not exist in the tags table
              else {
                $sql_insert_tag = "INSERT INTO tags(tag_name) VALUES (:input_tag);";
                $params_insert_tag = array("input_tag" => $tag);
                if (!exec_sql_query($db, $sql_insert_tag, $params_insert_tag)) {
                  record_message("Failed to create this new tag #$tag");
                  continue;
                }
                else {
                  $tag_id = $db -> lastInsertId("id");
                }
              }

              // tag either exists or is successfully added to the image
              $sql_add_link = "INSERT INTO image_tag(image_id, tag_id)
              VALUES(:image_id, :tag_id);";
              $params_add_link = array("image_id" => $file_id,
              "tag_id" => $tag_id);
              if (!exec_sql_query($db, $sql_add_link, $params_add_link)) {
                record_message("Failed to link this image with tag #$tag");
              }
            }
          }

            if (move_uploaded_file($upload_info["tmp_name"], IMG_UPLOADS_PATH . "$file_id.$upload_ext")){
              echo "<div class=\"uploadedImg\"><img class=\"newImg\" src=\"" .
              IMG_UPLOADS_PATH . "$file_id" . ".$upload_ext" .
              "\" alt=\"uploaded\"><a href=\"single_img.php?" .
              http_build_query(array("image_id" => $file_id)) . "\">View Image>>></a></div>";
              echo "<div class=\"details\">";
              echo "<ul><li> Image Name: " . htmlspecialchars($upload_name) . "</li>" .
              "<li> Description: " . htmlspecialchars($upload_desc) . "</li>" .
              "<li> Photographed by: " . htmlspecialchars($current_user["realname"]) . "</li>";
              if ($source) {
                echo "<li> <a href=" . htmlspecialchars($source) .
                ">Source: " . htmlspecialchars($source) . "</a></li>";
              }
              echo "</ul>";
              record_message("Your file has been uploaded");

            }
          } else {
            record_message("Failed to upload file");
          }
        } else {
          record_message("Failed to upload file");
        }
      }

      // show the upload form when there is no user logged in or the submit
      // button is not pressed.
      else {
        if (!$current_user and (isset($_POST["submit_upload"]))) {
          record_message("You must log in first to upload your image");
        }
        echo "<form id=\"upload\" action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">" .
        "<ul><li><label>Upload File:</label>
        <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"" . MAX_FILE_SIZE ."\"/>
        <input type=\"file\" name=\"img_file\" accept=\"image/*\" required>
        </li>
        <li>
        <label>Description:</label>
        </li>
        <li>
        <textarea name=\"description\" cols=\"40\" rows=\"5\"></textarea>
        </li>
        <li>
        <label> Tag the image: </label>
        </li>
        <li>
        <textarea name=\"tags\" cols=\"30\" rows=\"5\"> </textarea>
        </li>
        <li><label>Source: </label><input type=\"url\" name=\"source\" /></li>
        <li>
        <button name=\"submit_upload\" type=\"submit\">Upload</button>
        </li>
        </ul>
        </form>";
      }
      ?>
    </div>
    <?php print_message(); ?>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

<?php include ("includes/init.php");
$current_page = "upload";

const MAX_FILE_SIZE = 1000000;
const IMG_UPLOADS_PATH = "uploads/images/";

if (isset($_POST["submit_upload"]) and $current_user) {
  $upload_info = $_FILES["img_file"];
  $upload_desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

  if ($upload_info['error'] == UPLOAD_ERR_OK) {
    $upload_name = basename($upload_info["name"]);
    $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) );

    $sql = "INSERT INTO images (image_name, image_ext, description, user_id) VALUES (:filename, :extension, :description, :user_id)";
    $params = array(
      ':extension' => $upload_ext,
      ':filename' => $upload_name,
      ':description' => $upload_desc,
      ":user_id" => $current_user["id"]
    );

    $result = exec_sql_query($db, $sql, $params);

    if ($result) {
      $file_id = $db->lastInsertId("id");
      if (move_uploaded_file($upload_info["tmp_name"], IMG_UPLOADS_PATH . "$file_id.$upload_ext")){
        array_push($messages, "Your file has been uploaded.");
      }
    } else {
      array_push($messages, "Failed to upload file.");
    }
  } else {
    array_push($messages, "Failed to upload file.");
  }
}

else if (isset($_POST["submit_upload"])) {
  echo "You must log in first prior to uploading a phot";
}
?>
<!DOCTYPE html>
<html>

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
      <form id="upload" action="upload.php" method="post" enctype="multipart/form-data">
        <ul>
          <li>
          <label>Upload File:</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
          <input type="file" name="img_file" required>
        </li>
        <li>
          <label>Description:</label>
        </li>
        <li>
          <textarea name="description" cols="40" rows="5"></textarea>
        </li>
        <li>
          <button name="submit_upload" type="submit">Upload</button>
        </li>
        </ul>
      </form>
    </div>
  </div>
  <?php include ("includes/footer.php") ?>
</body>
</html>

<?php
include ("includes/init.php");

$current_page = "gallery"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
<!--   <script src="script/script.js"> </script>
 -->
  <title>Gallery</title>
</head>

<body>
  <div class = "wrapper">
    <div class = "static">
      <?php include ("includes/nav.php"); ?>
    </div>
    <div class = "content">
      <?php
      CONST NUM_COLUMNS = 4;
      CONST PATH_IMG = "/uploads/images/";

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
        $image_link = array("image_id" => $image_id);
        $file_name = PATH_IMG . $image_id . "." . $img_ext;
        echo "<div class=\"container\"><a href=\"gallery.php?" . http_build_query($image_link) .
              "\"><img src=" . $file_name . " alt=\"GalleryImages\" class=\"galleryImages\"></a>";
        echo "<div class=\"textoverlay\"><div class=\"imageDescription\"><ul><li>Image Name: " . $record["image_name"] . "</li>" .
             "<li>Description: " . $record["description"] . "</li>" .
             "<li>Photographed by: " . $record["realname"] . "</li>" .
             "<li>Source: <a href=\"" . $record["citation"] . "\">" . $record["citation"] .
             "</a></li></ul>";
        echo "</div></div></div>";
      }
      // view all images at once
      if (!isset($_GET["image_id"])) {
        echo "<div id=\"window\">";
        // obtain all images information
        $sql = "SELECT images.*, accounts.realname
                FROM images INNER JOIN accounts
                ON images.user_id = accounts.id;";

        $records = exec_sql_query($db, $sql) -> fetchAll();

        # vertical stores the images considered as vertical.
        # horizontal stores the images considered as horizontal.
        $vertical = array();
        $horizontal = array();

        foreach($records as $record) {
          if ($record["vertical"] == 1) {
            array_push($vertical, $record);
          }
          else {
            array_push($horizontal, $record);
          }
        }

        $num_per_column = floor(count($records) /  NUM_COLUMNS);
        $ver_per_column = floor(count($vertical) / NUM_COLUMNS);
        if ($ver_per_column == 0) { $ver_per_column = 1;}
        $hori_index = 0;
        $ver_index = 0;
        for ($i = 0; $i < NUM_COLUMNS; $i++) {
          echo "<div class=\"column\">";
          $v = 0;
          $before = FALSE;
          // all columns but the last one
          if ($i < NUM_COLUMNS - 1) {
            for ($j = 0; $j < $num_per_column; $j++) {
              if (!$before and $v < $ver_per_column and $ver_index < count($vertical)) {
                showImage($vertical[$ver_index]);
                $ver_index = $ver_index + 1;
                $v = $v + 1;
                $before = TRUE;
              }
              else if ($hori_index < count($horizontal)) {
                showImage($horizontal[$hori_index]);
                $before = FALSE;
                $hori_index = $hori_index + 1;
              }
            }
          }

          // the last column
          else {
            $num_last = count($records) - (NUM_COLUMNS - 1) * $num_per_column;
            for ($j = 0; $j < $num_last; $j++) {
              if (!$before and $v < $ver_per_column and $ver_index < count($vertical)) {
                showImage($vertical[$ver_index]);
                $ver_index = $ver_index + 1;
                $v = $v + 1;
                $before = TRUE;
              }
              else if ($hori_index < count($horizontal)) {
                showImage($horizontal[$hori_index]);
                $before = FALSE;
                $hori_index = $hori_index + 1;
              }
            }
          }
          echo "</div>";
        }
        echo "</div>";
      }
      // view a single image
      else {
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
          $record = $records[0];
          $file_name = PATH_IMG. $record["id"] . "." . $record["image_ext"];
          echo "<div class='single_img'><img src=" . $file_name . " class='img_single'></div>";
          echo "<div class='details'>";
          echo "<ul><li> Image Name: " . $record["image_name"] . "</li>" .
                   "<li> Description: " . $record["description"] . "</li>" .
                   "<li> Photographed by: " . $record["realname"] . "</li>" .
                   "<li> <a href=" . $record["citation"] . ">Source: " . $record['citation'] . "</a></li>".
                   "</ul>";
          echo "</div>";
        }
      }
       ?>
     </div>
    </div>
<?php include ("includes/footer.php") ?>
</body>
<!--
 citation
 1.jpg: http://outgotrip.com/product/colours-of-morocco/
 2.jpg: https://handluggageonly.co.uk/2016/02/01/11-experiences-you-will-want-to-try-in-istanbul/
 3.jpg: https://www.emiratesholidays.com/gb_en/destination/middle-east/dubai
 4.jpg: https://www.wanderingeducators.com/best/traveling/arctic-light-aurora-borealis-vesterålen-northern-norway.html
 5.jpg: https://wikitravel.org/en/Antarctica
 6.jpg: https://www.zicasso.com/african-safari
 7.jpg: https://www.capetownmagazine.com/top-beaches-in-cape-town-and-surrounds
 8.jpg: https://en.wikipedia.org/wiki/Maldives
 9.jpg: https://www.lonelyplanet.com/china/shanghai
 10.jpg: https://www.gadventures.com/trips/highlights-of-morocco/DCMH/
 11.jpg: http://www.chinadaily.com.cn/opinion/2015-10/27/content_22299021.htm
 12.jpg: https://www.pinterest.com/pin/266908715394586145/
 13.jpg: https://www.lonelyplanet.com/japan/tokyo/attractions/tokyo-tower/a/poi-sig/396309/356817
 14.jpg: https://www.100resilientcities.org/cities/london/
 15.jpg: https://www.flickr.com/photos/dominiquejames/4621961395/
 16.jpg: http://beatofhawaii.com/the-cheapest-time-to-fly-to-hawaii-is-coming-soon/
 17.jpg: http://www.nationsonline.org/oneworld/hong_kong.htm
 18.jpg: https://sciencetrends.com/what-tropical-savanna/
 19.jpg: https://www.chinadiscovery.com/shanghai/the-bund.html
 20.jpg: https://en.wikipedia.org/wiki/Empire_State_Building
 21.jpg: https://traveler.marriott.com/tokyo/the-best-time-to-view-japan-cherry-blossoms/
 -->
</html>

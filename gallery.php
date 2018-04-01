<?php include ("includes/init.php");
$current_page = "gallery"; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src="script/script.js"> </script>
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
      CONST PATH_IMG = "uploads/images/";
      // view all images at once
      if (!isset($_GET["image_id"])) {
        echo "<div id='window'>";
        // obtain all images information
        $sql = "SELECT * from images;";
        $records = exec_sql_query($db, $sql) -> fetchAll();

        if ($records) {
          $length = count($records);
          $num_per_column = floor($length / NUM_COLUMNS);

          for ($i = 1; $i <= NUM_COLUMNS; $i++) {
            echo "<div class=column>";
            if ($i < NUM_COLUMNS) {
              for ($j = 1; $j <= $num_per_column; $j++) {
                $index = ($i - 1) * $num_per_column + $j - 1;
                // echo $index;
                $record = $records[$index];
                $file_name = PATH_IMG.$record["id"]. "." . $record["image_ext"];
                $image_link = array("image_id" => $record["id"]);
                // echo $file_name;
                echo "<div><a href=gallery.php?".http_build_query($image_link).">
                <img class=\"galleryImages\" src=" .$file_name . "></a><a href=" . $record["citation"] . ">soure</a></div>";
              }
            }
            else {
              $index = ($i - 1) * $num_per_column;
              // echo $index;
              while ($index < $length) {
                // echo $index;
                $record = $records[$index];
                $file_name = PATH_IMG. $record["id"]. "." . $record["image_ext"];
                $image_link = array("image_id" => $record["id"]);
                // echo $file_name;
                echo "<div><a href=gallery.php?". http_build_query($image_link).">
                <img class=\"galleryImages\" src=" . $file_name . "></a><a href=" . $record["citation"] . ">soure</a></div>";
                $index = $index + 1;
              }
            }
            echo "</div>";
          }
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

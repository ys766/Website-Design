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
<!--   <script src="script/script.js"> </script>
 -->
  <title>Gallery</title>
</head>

<body>
  <div class = "wrapper">
    <div class = "static">
      <?php include ("includes/nav.php"); ?>
    </div>
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
        $file_name = PATH_IMG . $image_id . "." . $img_ext;
        echo "<div class=\"container\">" .
              "<img src=" . $file_name . " alt=\"GalleryImages\" class=\"galleryImages\">";
        echo "<div class=\"textoverlay\"><div class=\"imageDescription\"><ul><li>Image Name: " . $record["image_name"] . "</li>" .
             "<li>Description: " . $record["description"] . "</li>" .
             "<li>Photographed by: " . $record["realname"] . "</li>" .
             "<li>Source: <a href=\"" . $record["citation"] . "\">" . $record["citation"] .
             "</a></li><br />".
             "<li><a class = \"img_link\" href=\"\single_img.php?".http_build_query(array("image_id" => $image_id)). "\">View Image>>></a></li></ul>";
        echo "</div></div></div>";
      }

      function galleryArrangement($records) {
        shuffle($records); // randomize the photo arrangments.
        $length = count($records);
        $num_per_column = floor($length /  NUM_COLUMNS);

        for ($i = 0; $i < NUM_COLUMNS; $i++) {
          echo "<div class=\"column\">";
          // all columns but the last one
          if ($i < NUM_COLUMNS - 1) {
            for ($j = 0; $j < $num_per_column; $j++) {
              showImage($records[$i * $num_per_column + $j]);
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

      $sql_tag = "SELECT tag_name FROM tags;";
      $tags = exec_sql_query($db, $sql_tag) -> fetchAll();

      // view all images at once
      if (!isset($_GET["image_id"]) and !isset($_GET["tag"])) {
        echo "<div class = \"content\">";
        showTags($tags, FALSE);

        echo "<div id=\"window\">";
        // obtain all images information
        $sql = "SELECT images.*, accounts.realname
                FROM images INNER JOIN accounts
                ON images.user_id = accounts.id;";
        $records = exec_sql_query($db, $sql) -> fetchAll();
        galleryArrangement($records);
        echo "</div></div>";
      }
      // view all images with a single tag
      else if (isset($_GET["tag"])) {
          $tag_name = filter_var($_GET["tag"], FILTER_SANITIZE_STRING);
          $sql = "SELECT images.*, accounts.realname
                  FROM images INNER JOIN accounts
                  ON images.user_id = accounts.id
                  WHERE images.id IN (SELECT images.id
                                      FROM images INNER JOIN image_tag
                                      ON images.id = image_tag.image_id
                                      INNER JOIN tags
                                      ON image_tag.tag_id = tags.id
                                      WHERE tags.tag_name = :tag_name)";
          $params = array(":tag_name" => $tag_name);
          $records = exec_sql_query($db, $sql, $params) -> fetchAll();
          echo "<div class = \"content\">";
          showTags($tags, FALSE);
          echo "<div id = \"window\">";
          galleryArrangement($records);
      }
       ?>
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

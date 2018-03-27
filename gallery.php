<?php include ("includes/init.php");
$current_page = "gallery"; ?>
<!DOCTYPE html>
<html>

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
    <div class = "content">
      <div class = "window">
      <?php
      CONST NUM_COLUMNS = 4;
      CONST PATH_IMG = "uploads/images/";
      // obtain all image_id
      $sql = "SELECT id, image_ext, description, citation from images;";
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
              $file_name = PATH_IMG . $record["id"]. "." . $record["image_ext"];
              // echo $file_name;
              if ($record["citation"]){
                echo "<div><img src=" . $file_name . "><a href=" . $record["citation"] . ">soure</a></div>";
              }
              else {
                echo "<div><img src=" . $file_name . "></div>";
              }
            }
          }
          else {
            $index = ($i - 1) * $num_per_column;
            // echo $index;
            while ($index < $length) {
              // echo $index;
              $record = $records[$index];
              $file_name = PATH_IMG . $record["id"]. "." . $record["image_ext"];
              // echo $file_name;
              if ($record["citation"]){
                echo "<div><img src=" . $file_name . "><a href=" . $record["citation"] . ">source</a></div>";
              }
              else {
                echo "<div><img src=" . $file_name . "></div>";
              }
              $index = $index + 1;
            }
          }
          echo "</div>";
        }
      }
       ?>
     </div>
    </div>
  </div>
<?php include ("includes/footer.php") ?>
</body>
<!--
 citation
 The first three images are taken by myself. So the following citations start with image 3.jpg
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

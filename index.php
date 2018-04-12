<?php include ("includes/init.php");
$current_page = "index"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src="script/script.js"> </script>
  <title>Home</title>
</head>

<body onload="Active()">
  <div class = "wrapper">

    <div class = "static">
      <?php include ("includes/nav.php");?>
    </div>

    <div class = "content fade_in" id="bkg">

      <div class = "bkg1">

        <div class="intro">
          <h1>Welcome to TRAVEL BOOK!</h1> <br />
          <p>  Where awesomeness happens all the time <br /><br /> </p>
          <div class="arrow"><span>&#10151; </span></div>
        </div>
        <p class="bkgcitation">
          <a href="http://lauramthomas.com/2017/09/20/believing-is-seeing/"> source:
            http://lauramthomas.com/2017/09/20/believing-is-seeing/ </a>
          </p>
        </div>

        <div class="bkg2">
          <div class="intro">
            <h2> Explore </h2>
            <p>There is so much to be discovered in this world <br />
              Pack your bag, bring your tickets, and get your amazing journey started
            </p>
          </div>
        </div>


        <div class="bkg3">
          <div class="intro">
            <h2> Experience </h2>
            <p>
              See the world through the camera lens and communicate with the world in the language
              of colors and pixels <br />
              Breathe in the air from every corner of the world
              <br /> And become the protagonist of an incredible story
            </p>
          </div>
          <p class="bkgcitation">
            <a href="http://bilder.4ever.eu/verkehr/zuge/alten-schienen-208697"> source:
              http://bilder.4ever.eu/verkehr/zuge/alten-schienen-208697 </a>
            </p>
          </div>


          <div class="bkg4">
            <div class="intro">
              <h2> Sharing </h2>
              <p>
                Share your photos with other avid travalers around the world who are just like you <br />
                <br />At Travel Book, we cherish sharing <br />
                So...check out all photos from other explorers <br />
                And even better, share your own photos and tell us about your stories &#9786;
              </p>
              <a href="gallery.php">View the Gallery &raquo; &raquo;</a>
            </div>
          </div>
        </div>
      </div>
      <?php include ("includes/footer.php") ?>
    </body>
    </html>

    <!--
    Citation:
    background image 1: http://lauramthomas.com/2017/09/20/believing-is-seeing/
    background image 2: http://bilder.4ever.eu/verkehr/zuge/alten-schienen-208697
  -->

<?php include ("includes/init.php");
$current_page = "logout"; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Yuzhe Sheng" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Log Out</title>
</head>

<body>
  <div class = "wrapper">
		<div class = "static">
      <?php include ("includes/nav.php"); ?>
		</div>
    <div class = "content">
      <?php
      log_out();
      if (!$current_user) {
        echo "LOGGED OUT!";
      }
      ?>
    </div>
	</div>
<?php include ("includes/footer.php") ?>
</body>
</html>

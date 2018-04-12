<?php include ("includes/init.php");
$current_page = "logout";
// no user logged in but still tried to log out;
if (!$current_user) {
  record_message("You must log in first before you log out");
}
else {
log_out();
}
?>
<!DOCTYPE html>
<html lang="en">

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
	</div>
<?php print_message(0); ?>
</div>
<?php include ("includes/footer.php") ?>
</body>
</html>

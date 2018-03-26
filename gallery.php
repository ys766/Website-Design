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
<?php 
	include ("includes/init.php");
	include ("includes/nav.php");

	// show all images available
	$sql = "SELECT id FROM images;";
	$records = exec_sql_query($db, $sql) -> fetchAll();

	for ($records as $record) {
		$img = "uploads/images/" . $record . ".jpg";
		echo $img;
	}
?>

</body>
</html>

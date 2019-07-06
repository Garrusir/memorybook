<?php
require "connect.php";
require "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Фотогалерея</title>
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="lib/fancybox-3.0/dist/jquery.fancybox.min.css">
	<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="header row">
	<?php require 'blocks/header.php';?>
</div>


<div class="content row">
	<div class="col-md-3 ">
		<?php require 'blocks/sidebar.php';?>
	</div>
	<div class="col-md-9">
			<div class="text">
				<h1>Фотогалерея</h1>
				
			</div>

			<div class="gallery" >
					<?php

					if(isset($_GET['id'])) {
						
						 $id_alb = clean_data($_GET['id']);

						$result = mysqli_query($connect, "SELECT * FROM images WHERE `id_alb` LIKE '$id_alb'"); 
						$myrow = mysqli_fetch_array($result);
						
						
						do {
							$img_path = $myrow['path'];
							$thumb_path = $myrow['thumb_path'];
							$img_name = $myrow['name'];
							$alt = $myrow['alt'];
							echo "
								<a href='uploads/$img_path' data-fancybox data-caption='$img_name' >
									<img src='uploads/thumbs/$thumb_path' alt='$alt' />
								</a>

							"; 
						}
						while ($myrow = mysqli_fetch_array($result));
						 	} else
						 	{


						$result = mysqli_query($connect, "SELECT * FROM albums"); 
						$myrow = mysqli_fetch_array($result);
						do {
						 	$alb_id 	= $myrow['id'];
						 	$thumb_path	= $myrow['thumb'];
						 	$name 		= $myrow['name'];
						 	$desc 		= $myrow['descrip'];
						 	echo "
						 	<a href='gallery.php?id=$alb_id' >
						 	<div class='alb'>
						 			<div>
						 			<img src='uploads/thumbs/$thumb_path' alt='$name' />
						 			</div>
						 			<div class='alb-name'>
						 			<span> $desc</span>
						 			</div>
						 		
						 	</div>
						 	</a>
						 	"; 
						 }
						 while ($myrow = mysqli_fetch_array($result));


						}

					 ?>

			</div>
				
			
			
	</div>
</div>


</div>
	<?php require 'blocks/footer.php';?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="lib/fancybox-3.0/dist/jquery.fancybox.min.js"></script>
</body>
</html>
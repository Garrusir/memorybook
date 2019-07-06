<?php
require "../connect.php";
require "../functions.php";
$title = "Админ-панель";
require "header.php";
?>

<body>
<?php require 'adminbar.php'; ?>

<div class="content">

<div class="search-form">
	<form action="search.php" method="GET">
	<input id="name" name="words" type="text" required>
	<input  class="search-button" type="submit" name="search" value="Найти">
	</form>
</div>
<?php


$articles_on_page = count_articles_on_admin_page($connect);



	$page = Page();
	$count_page = Count_page( $articles_on_page);

	$str = "SELECT * FROM mb2 ORDER BY surname  ASC, name ASC LIMIT ".($page-1)*$articles_on_page.", ".$articles_on_page;
	$result = mysqli_query($connect, $str) or die (mysqli_error());
	$myrow = mysqli_fetch_array($result);
	?>

	<div class="content-table">
	<?php
	print_result_admin ($myrow, $result);
	?>
	</div>
	<div class="pagination">
	<?php
 	Pagination($count_page, $page, $articles_on_page, $result, $myrow);
?>
	</div>
</div>
</body>
</html>

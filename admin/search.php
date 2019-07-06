<?php
include_once('../connect.php');
include_once('../functions.php');
$title ="Поиск";
include_once('header.php');
?>

<body>
<?php require 'adminbar.php' ?>
<div class="content">


<div class="search-form">
	<form action="search.php" method="GET">
	<input id="name" name="words" type="text">
	<input  class="search-button" type="submit" name="search" value="Найти">
	</form>
</div>
<?php
$errors = array();



if (check_search() == 'search') {
		if (isset($_GET['words'] ) && $_GET['words']!=NULL ) {

			$url = $_SERVER['REQUEST_URI'];
			$str_replace = '/&page=\d+/';
			$url = preg_replace($str_replace, '' ,$url);

			$words = clean_data($_GET['words']);



			$articles_on_page = count_articles_on_page();

			$page = Page();
			

			$str = create_str($words, $articles_on_page, $page);

			$str_count_pages = "SELECT COUNT( 'id' ) FROM mb2 WHERE MATCH (surname,name,patronymic,place, db, dd) AGAINST ('".$words."') ";
			$count_page = Count_page($articles_on_page, $str_count_pages);
			echo $count_page." - кол-во стр<br>";

			$result = mysqli_query($connect, $str)  or die("MySqlError: ".mysql_error());
			$myrow = mysqli_fetch_array($result); 
			if($myrow == false)
				{
					$errors[]  = "Ничего не найдено";
				} else {
					echo "<div class='content-table'>";

					
					print_result_admin($myrow, $result);
					echo "</div><div class='pagination'>";
					
					Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url);
					echo "</div>";
				}
		} else $errors[]  = "Строка пуста";


}

print_error($errors);



?>
</div>
</body>
</html>
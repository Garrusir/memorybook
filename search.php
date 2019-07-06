
 <?php
require 'connect.php';
require 'functions.php';

if( isset($_GET['ajax']) && $_GET['ajax'] == true ) {
	unset($_GET['ajax']);

?>

<h2>Результаты поиска <?php 
  if(isset($_GET['words'])) echo 'по запросу "'.clean_data($_GET['words']).'"';
  ?></h2>

<?php
$errors = array();


$url = $_SERVER['REQUEST_URI'];
$str_replace = '/&page=\d+/';
$url = preg_replace($str_replace, '' ,$url);

$url = str_replace("&ajax=true", "", $url);

$check_search  =check_search();
if ($check_search == 'search') {
		if (isset($_GET['words'] ) && $_GET['words']!=NULL ) {
	
			$words = clean_data($_GET['words']);
			$articles_on_page = count_articles_on_page();

			$page = Page();
		
			

			$str = create_str($words, $articles_on_page, $page);
	

			 $str_count_pages = "SELECT COUNT( 'id' ) FROM mb2 WHERE MATCH (surname,name,patronymic,place, db, dd) AGAINST ('".$words."') ";
			 $count_page = Count_page($articles_on_page, $str_count_pages);

			

			$result = mysqli_query($connect, $str)  or die("MySqlError: ".mysql_error());
			$myrow = mysqli_fetch_array($result); 
			if($myrow == false)
				{
					$errors[]  = "Ничего не найдено";
				} else {
					echo "<div class='content-table'>";

					$n = ($page-1)*$articles_on_page+1;
					print_result($myrow, $result, $n);
					echo "</div>
					<div class='pagination row'>
						<div class='pagin-wrap'>";
										Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url);
					echo "</div>
					</div>";
				}
		} else $errors[]  = "Строка пуста";


}

if ($check_search == 'fullsearch') {
	
	if(isset($_GET['surname']) && isset($_GET['name']) && isset($_GET['patronymic']) && isset($_GET['place']) && isset($_GET['db']) && isset($_GET['dd'])  ){
		if($_GET['surname'] != '' || $_GET['name'] != '' || $_GET['patronymic'] != '' || $_GET['place'] != '' || $_GET['db'] != '' || $_GET['dd'] != ''  ){
			
		
		

			foreach ($_GET as $key => $value) {
				if($value != '') 
				{
				$_GET[$key] = clean_data($value);
				}

			}

			$page = Page();
			
			$articles_on_page = count_articles_on_page();

			$str = create_full_str($articles_on_page, $page);
			

				$pattern = '/SELECT\s\* /';
				$replacement = "SELECT COUNT('id')";
			$str_count_pages = preg_replace($pattern, $replacement, $str);
				$pattern ='/LIMIT\s*\d+\s*,\s*\d+/';
			$str_count_pages = preg_replace($pattern, "", $str_count_pages);
						
			$count_page = Count_page($articles_on_page, $str_count_pages);
		

			$result = mysqli_query($connect, $str)  or die("MySqlError: ".mysqli_error());
			$myrow = mysqli_fetch_array($result); 
				if($myrow == false)
				{
					$errors[]  = "Ничего не найдено";
				} else 
				{
					echo "<div class='content-table'>";

					$n = ($page-1)*$articles_on_page+1;
					print_result($myrow, $result,$n);
					echo "</div>
					<div class='pagination row'>
						<div class='pagin-wrap'>";
										Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url);
					echo "</div>
					</div>";
				}
	} else $errors[]  = "Все поля пусты";
}	else $errors[]  = "Форма не заполенеа";
	
}
if(!empty($errors)) print_error($errors);


}else {

?>
 



<!DOCTYPE html>
<html>
<head>
	<title>Поиск</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="main.js"></script>
	<link rel="stylesheet" type="text/css" href="lib/font-awesome-4.7.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="header row">
	<?php require 'blocks/header.php';?>
</div>

<div class="content row">
	<div class="col-md-3 " style="margin-bottom: 100px; ">
		<?php require 'blocks/sidebar.php';?>
		
	</div>
	<div class="col-md-9">
			<div class="row search-from">
				<form action="search.php" method="GET">
				<input class="col-md-9 search-field" id="name" name="words" type="text" required>
				<input  class="button col-md-3" type="submit" id='send' name="search" value="Найти">
				<label for="send"><i class="fa fa-search button " aria-hidden="true"></i></label>
				</form>
			</div> 
			<div class="ajax-content">
<h2>Результаты поиска <?php 
  if(isset($_GET['words'])) echo 'по запросу "'.clean_data($_GET['words']).'"';
  ?></h2>

<?php
$errors = array();


$url = $_SERVER['REQUEST_URI'];
$str_replace = '/&page=\d+/';
$url = preg_replace($str_replace, '' ,$url);


$check_search  =check_search();
if ($check_search == 'search') {
		if (isset($_GET['words'] ) && $_GET['words']!=NULL ) {
	
			$words = clean_data($_GET['words']);
			$articles_on_page = count_articles_on_page();

			$page = Page();
		
			

			$str = create_str($words, $articles_on_page, $page);
	

			 $str_count_pages = "SELECT COUNT( 'id' ) FROM mb2 WHERE MATCH (surname,name,patronymic,place, db, dd) AGAINST ('".$words."') ";
			 $count_page = Count_page($articles_on_page, $str_count_pages);

			

			$result = mysqli_query($connect, $str)  or die("MySqlError: ".mysql_error());
			$myrow = mysqli_fetch_array($result); 
			if($myrow == false)
				{
					$errors[]  = "Ничего не найдено";
				} else {
					echo "
				
					<div class='content-table'>";

					$n = ($page-1)*$articles_on_page+1;
					print_result($myrow, $result, $n);
					echo "</div>
					<div class='pagination row'>
						<div class='pagin-wrap'>";
										Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url);
					echo "</div>
					</div>
				";
				}
		} else $errors[]  = "Строка пуста";


}

if ($check_search == 'fullsearch') {
	
	if(isset($_GET['surname']) && isset($_GET['name']) && isset($_GET['patronymic']) && isset($_GET['place']) && isset($_GET['db']) && isset($_GET['dd'])  ){
		if($_GET['surname'] != '' || $_GET['name'] != '' || $_GET['patronymic'] != '' || $_GET['place'] != '' || $_GET['db'] != '' || $_GET['dd'] != ''  ){
			
		
		

			foreach ($_GET as $key => $value) {
				if($value != '') 
				{
				$_GET[$key] = clean_data($value);
				}

			}

			$page = Page();
			
			$articles_on_page = count_articles_on_page();

			$str = create_full_str($articles_on_page, $page);
			

				$pattern = '/SELECT\s\* /';
				$replacement = "SELECT COUNT('id')";
			$str_count_pages = preg_replace($pattern, $replacement, $str);
				$pattern ='/LIMIT\s*\d+\s*,\s*\d+/';
			$str_count_pages = preg_replace($pattern, "", $str_count_pages);
						
			$count_page = Count_page($articles_on_page, $str_count_pages);
		

			$result = mysqli_query($connect, $str)  or die("MySqlError: ".mysqli_error());
			$myrow = mysqli_fetch_array($result); 
				if($myrow == false)
				{
					$errors[]  = "Ничего не найдено";
				} else 
				{
					echo "
				
					<div class='content-table'>";

					$n = ($page-1)*$articles_on_page+1;
					print_result($myrow, $result,$n);
					echo "</div>
					<div class='pagination row'>
						<div class='pagin-wrap'>";
										Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url);
					echo "</div>
					</div>
				";
				}
	} else $errors[]  = "Все поля пусты";
}	else $errors[]  = "Форма не заполенеа";
	
}
if(!empty($errors)) print_error($errors);


?>
	</div>
 </div>
</div>
</div>
	<?php require 'blocks/footer.php';?>
</body>
</html> 
<?php 
}
?>
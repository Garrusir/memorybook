<?php
require "connect.php"; 

function print_error($errors){
	echo "<div class='errors'><span >".array_shift($errors)."</span></div>";
}
function print_info($info){
	echo "<span class='info'>".$info."</span>";
}

 function clean_data($data) {
 	global $connect;
 	$data = strip_tags($data);
 	$data = preg_replace('~[^a-z0-9 \x80-\xFF]~i', " ",$data); 
 	$data = trim($data);
	$data = mysqli_real_escape_string( $connect, $data);
	return $data;
 }

function check_email($email) {
	if( preg_match('/([a-z0-9_.-]{1,20})@([a-z0-9.-]{1,20}).([a-z]{2,4})/', strtolower($email))) return true;
	return false;
}

function check_search() {
	if(isset($_GET['search'])) {
		return "search";
	} 

	if(isset($_GET['fullsearch'])) {
	 	return "fullsearch";
	}
	global $errors;
	return $errors[] = "Не нажата кнопка";
}
function count_articles_on_page() {
	global $connect;
	$result = mysqli_query($connect, "SELECT art_on_page FROM settings") or die ('MySQL Error: ');
	$myrow = mysqli_fetch_array($result);
	return $myrow['art_on_page'];
}
function count_articles_on_admin_page() {
	global $connect;
	$result = mysqli_query($connect, "SELECT art_on_admin_page FROM settings") or die ('MySQL Error: ');
	$myrow = mysqli_fetch_array($result);
	return $myrow['art_on_admin_page'];
}
function create_str($words, $articles_on_page, $page) {
	$str  = "SELECT * FROM `mb2` WHERE ";
	$str = $str." MATCH (surname,name,patronymic,place, db, dd) AGAINST ('".$words."') ";
	$str = $str." LIMIT ".($page-1)*$articles_on_page." , ".$articles_on_page;
	return $str;
}

function create_full_str($articles_on_page, $page){
	global $connect;
	$str = "SELECT * FROM mb2 WHERE ";

	if($_GET['surname']) 	$str.="surname LIKE '%".mysqli_real_escape_string($connect,$_GET['surname'])."%' AND ";
	if($_GET['name']) 		$str.="name LIKE '%".mysqli_real_escape_string($connect,$_GET['name'])."%' AND";
	if($_GET['patronymic']) $str.="patronymic LIKE '%".mysqli_real_escape_string($connect,$_GET['patronymic'])."%' AND ";
	if($_GET['place']) 		$str.="place LIKE '%".mysqli_real_escape_string($connect,$_GET['place'])."%' AND ";
	if($_GET['db']) 		$str.="db LIKE '%".mysqli_real_escape_string($connect,$_GET['db'])."%' AND ";
	if($_GET['dd']) 		$str.="dd LIKE '%".mysqli_real_escape_string($connect,$_GET['dd'])."%' AND " ;

	$str = substr($str,0,-4);
	$str = $str." LIMIT ".($page-1)*$articles_on_page." , ".$articles_on_page;
	return $str;

}


function print_result($myrow, $result, $n) {
	echo "<table>
			<tr class='table-title'>
				<th>№</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Место</th><th>Годы</th>
			</tr>";
	
	
	do {
			
		$n % 2 == 0 ? $css_class='table-b' : $css_class='table-w'; 
			echo "<tr class='$css_class'>
				<td>".$n."</td><td>".$myrow['surname']."</td><td>".$myrow['name']."</td><td>".$myrow['patronymic']."</td><td>".$myrow['place']."</td><td>".$myrow['db']." - ".$myrow['dd']."</td>
			</tr>";
		$n++;
	} while ($myrow = mysqli_fetch_array($result));

	echo "</table>
	";
}



function Count_page( $articles_on_page, $str = "SELECT COUNT(*) FROM mb2") {
		global $connect;

		$result = mysqli_query($connect, $str) or die ("error");
		$row = mysqli_fetch_array($result);
		$count_articles = $row[0];
		$count_page = ceil($count_articles/$articles_on_page);
		if ($str != "SELECT COUNT(*) FROM mb2"){	
			if ($count_articles == 1 ) {
				echo "<span class='search-info'>Нашлась ".$count_articles." запись</span>";
			}elseif($count_articles > 1 && $count_articles<5){
					echo "<span class='search-info'>Нашлась ".$count_articles." записи</span>";
			}elseif($count_articles>1){
						echo "<span class='search-info'>Нашлось ".$count_articles." записей</span>";
			}
		}
	return $count_page;
}

function Page() {
	if(isset($_GET['page']) && $_GET['page'] != '' ){
		$page = (int)$_GET['page'];
		if ($page <= 0 ) $page=1;
	} else $page = 1;
	return $page;
}

function print_result_admin ($myrow, $result) {
	$n=0;
	echo "<table>
			<tr class='table-title'>
				<th>№</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Место</th><th>Годы</th><th></th><th></th>
			</tr>";
	do {
		$n++;
		$n % 2 == 0 ? $css_class='table-w' : $css_class='table-b'; 
			echo "<tr class='$css_class'>
				<td>".$myrow['id']."</td>
				<td>".$myrow['surname']."</td>
				<td>".$myrow['name']."</td>
				<td>".$myrow['patronymic']."</td>
				<td>".$myrow['place']."</td>
				<td>".$myrow['db']." - ".$myrow['dd']."</td>
				<td><a href='edit.php?n=".$myrow['id']."'> Изменить </a></td>
				<td><a href='edit.php?action=delete&n=".$myrow['id']."'> Удалить </a></td>
			</tr>";		
	} while ($myrow = mysqli_fetch_array($result));
	echo "</table>";
}



function Pagination($count_page, $page, $articles_on_page, $result, $myrow, $url="?"){

	if($count_page==1) return;

	if ($page > $count_page) $page = $count_page;
	if ($page == 1 ) {
		echo "<span> << </span>" ;
		echo "<span> < </span>";
		echo "<span class='active-page'>".$page."</span>";
		echo "<a href='".$url."&page=".($page+1)."'	 data-page='".($page+1)."' 	title='На страницу номер ".($page+1)."' >  ".($page+1)." </a>";
		if($page+2<=$count_page) echo "<a href='".$url."&page=".($page+2)."' data-page='".($page+2)."' title='На страницу номер ".($page+2)."'>".($page+2)."</a>";
		if($page+3<=$count_page) echo "<a href='".$url."&page=".($page+3)."' data-page='".($page+3)."' title='На страницу номер ".($page+3)."'>".($page+3)."</a>";
		if($page+4<=$count_page) echo "<a href='".$url."&page=".($page+4)."' data-page='".($page+4)."' title='На страницу номер ".($page+4)."'>".($page+4)."</a>";
		// if($page+5<=$count_page) echo "<a href='".$url."&page=".($page+5)."' data-page='".($page+5)."' title='На страницу номер ".($page+5)."'>".($page+5)."</a>";
		if($page+6<=$count_page) echo "<span>...</span>";
		if($page+10<=$count_page) echo "<a href='".$url."&page=".($page+10)."' data-page='".($page+10)."' title='На страницу номер ".($page+10)."'>".($page+10)."</a>";
		echo "<a href='".$url."&page=".($page+1)."' data-page='".($page+1)."' title='Следующая'> > </a>" ;
		echo "<a href='".$url."&page=".$count_page."'  data-page='".$count_page."' title='В конец'>  >> </a>";

	} else if ($page == $count_page ) {
			echo "<a href='".$url."&page=1' data-page='1'  title='В начало'> <<  </a>
				<a href='".$url."&page=".($page-1)."' data-page='".($page-1)."' title='Предыдущая'> <  </a>";
			 	if($page-10>0) echo  "<a href='".$url."&page=".($page-10)."' data-page='".($page-10)."' title='На страницу номер ".($page-10)."'> ".($page-10)." </a> ";
			 	if($page-6>0) echo  "<span>...</span>";
			 	if($page-5>0) echo  "<a href='".$url."&page=".($page-5)."' data-page='".($page-5)."' title='На страницу номер ".($page-5)."'>  ".($page-5)." </a>";
			 	if($page-4>0) echo  "<a href='".$url."&page=".($page-4)."' data-page='".($page-4)."' title='На страницу номер ".($page-4)."'>  ".($page-4)." </a>";
			 	if($page-3>0) echo  "<a href='".$url."&page=".($page-3)."' data-page='".($page-3)."' title='На страницу номер ".($page-3)."'>  ".($page-3)." </a>";
			 	if($page-2>0) echo  "<a href='".$url."&page=".($page-2)."' data-page='".($page-2)."' title='На страницу номер ".($page-2)."'>  ".($page-2)." </a>";
			 	if($page-1>0) echo  "<a href='".$url."&page=".($page-1)."' data-page='".($page-1)."' title='На страницу номер ".($page-1)."'>  ".($page-1)." </a>";
			 echo	"<span class='active-page'>".$page."</span>
			 	<span>  > </span><span>  >> </span>";
		} else {
			echo "<a href='".$url."&page=1' data-page='1' title='В начало'>  <<  </a>";
				if ($page-1>0 )	echo "<a href='".$url."&page=".($page-1)."'   data-page='".($page-1)."'	title='Предыдущая'> <  </a>";
				if ($page-10>0)	echo "<a href='".$url."&page=".($page-10)."'  data-page='".($page-10)."'	title='На страницу номер ".($page-10)."'> ".($page-10)."</a><span>...</span>";
			 	// if ($page-5>0 )	echo "<a href='".$url."&page=".($page-5)."' title='На страницу номер ".($page-5)."'>  ".($page-5)."</a>";
			 	// if ($page-4>0 )	echo "<a href='".$url."&page=".($page-4)."' title='На страницу номер ".($page-4)."'>  ".($page-4)."</a>";
			 	//if ($page-3>0 )	echo "<a href='".$url."&page=".($page-3)."' title='На страницу номер ".($page-3)."'>  ".($page-3)."</a>";
			 	if ($page-2>0 )	echo "<a href='".$url."&page=".($page-2)."'  data-page='".($page-2)."'	title='На страницу номер ".($page-2)."'>  ".($page-2)."</a>";
			 	if ($page-1>0 )	echo "<a href='".$url."&page=".($page-1)."'  data-page='".($page-1)."'	title='На страницу номер ".($page-1)."'>  ".($page-1)."</a>";
				echo "<span class='active-page'>".$page."</span>";
				if ($page+1<=$count_page)	echo "<a href='".$url."&page=".($page+1)."'  data-page='".($page+1)."'	title='На страницу номер ".($page+1)."'>  ".($page+1)." </a>";
				if ($page+2<=$count_page)	echo "<a href='".$url."&page=".($page+2)."'  data-page='".($page+2)."'	title='На страницу номер ".($page+2)."'>  ".($page+2)." </a>";
				//if ($page+3<=$count_page)	echo "<a href='".$url."&page=".($page+3)."' title='На страницу номер ".($page+3)."'>  ".($page+3)." </a>";
				// if ($page+4<=$count_page)	echo "<a href='".$url."&page=".($page+4)."' title='На страницу номер ".($page+4)."'>  ".($page+4)." </a>";
				// if ($page+5<=$count_page)	echo "<a href='".$url."&page=".($page+5)."' title='На страницу номер ".($page+5)."'>  ".($page+5)." </a>";
				if ($page+10<=$count_page)	echo "<span>...</span><a href='".$url."&page=".($page+10)."'  data-page='".($page+10)."'	title='На страницу номер ".($page+10)."'>  ".($page+10)." </a>";
				if ($page+1<=$count_page)	echo "<a href='".$url."&page=".($page+1)."'  data-page='".($page+1)."'	title='Следующая'>  > </a>";
				echo "<a href='".$url."&page=".$count_page."' data-page='".$count_page."' title='В конец'> >> </a>";
		}
	
}

function is_login() {
	if(isset($_SESSION['userid'])) {
	 return true; }
	else return false;
}

function delete_article($id) {
	global $connect;
	$str = "DELETE FROM mb2 WHERE id ='$id' ";
	$result = mysqli_query($connect, $str) or die(mysqli_error("Ошибка удаления: "));
	if ($result) return true; else return false;
}

function delete_image($id, $dir, $thumb_dir) {
	global $connect;

	$str = "SELECT * FROM images WHERE id_img LIKE '$id'";
	$result = mysqli_query($connect, $str) or die(mysqli_error("Ошибка: "));
	$myrow = mysqli_fetch_array($result);

	$del = unlink($dir.$myrow['path']);
	$thumb_del = unlink($thumb_dir.$myrow['thumb_path']); 

	$str = "DELETE FROM images WHERE id_img ='$id' ";
	$result = mysqli_query($connect, $str) or die(mysqli_error("Ошибка удаления: "));
	if ($result && $del) return true; else return false;
}




function check_image($file, $max_size = 1024, $valid_extensions = array()) {
	$errors = array();
	$max_size *= 1048576;
	$imageinfo = getimagesize($file['tmp_name']);
	if(in_array($imageinfo['mime'], $valid_extensions )) {
		if ($file['size'] < $max_size) {
			return true;
		} else $errors[] =  "Размер файла превышает ".$max_size/1048576 . "МБ.";
	} else $errors[] = "Недопустимое расширение файла";
	return $errors;
}


function upload_image($file,$img_name, $thumb_name, $upload_dir = ".") {
			global $connect;
			
	
			$str = "INSERT INTO images(`path`,`thumb_path`,`name`,`alt`) VALUES ('$img_name','$thumb_name', '$img_name', '$img_name') ";
			// echo "string".$str;
			$result = mysqli_query($connect, $str) or die(mysqli_error($connect));

			// echo "file['tmp_name'] =".$file['tmp_name']."<br>";
			//  echo "upload_dir+img_name =".$upload_dir.$img_name."<br>";
			copy ($file['tmp_name'] ,$upload_dir.$img_name );
			

			// if(empty($errors)) return true; else return $errors;
		}

function change_img_name($upload_dir, $img_name, $str="_copy"){
			$path = $upload_dir.$img_name;
			
			if (file_exists($path)) {
				
				$path_parts = pathinfo($path);
				$new_name = basename($path, ".".$path_parts['extension']);
			
				$new_name = $new_name."_".$str.".".$path_parts['extension'];
				if(isset($n)) $n++; else $n=1;
				$new_name = change_img_name($upload_dir, $new_name, $n);
				return $new_name;
	} else {
			
			return $img_name;
			}
}


function resize($file,$thumb_name, $upload_dir) {
		$thumb_width = 300;
		$thumb_height = 250;
		$quality = 75;
		$k_wh = round($thumb_width/$thumb_height,2);
		$k_hw = round($thumb_height/$thumb_width,2);
		if($file['type'] == 'image/jpeg')
			$source = imagecreatefromjpeg($file['tmp_name']);
		elseif($file['type'] == 'image/png')
			$source = imagecreatefrompng($file['tmp_name']);
		elseif($file['type'] == 'image/gif')
			$source = imagecreatefromgif($file['tmp_name']);
		else
		 return false;

		$w_src = imagesx($source);
		$h_src = imagesy($source);

		$dest  = imagecreatetruecolor($thumb_width, $thumb_height);
		
				if ($w_src > $h_src || $w_src == $h_src) {
				$h_img = $h_src; // высота вырезаемой из source обл
				$w_img = floor($h_src * $k_wh); // ширина вырезаемой из source обл
				$src_x = floor(($w_src - $w_img)/2); // координата Х в source
				$src_y = 0; // координата у в source

				imagecopyresampled ( $dest , $source , 0 , 0 ,  $src_x ,  $src_y , $thumb_width, $thumb_height , $w_img , $h_img );
			// imagecopyresampled ( $dest ,$source , 0 , 0 ,  0 ,  0 , 300, 250 , 300 , 250 );
			} elseif ($w_src < $h_src) {
				$w_img = $w_src; // ширина вырезаемой из source обл
				$h_img = floor($w_src * $k_hw); // высота вырезаемой из source обл
				$src_x = 0; // координата Х в source
				$src_y = floor(($h_src - $h_img)/2); // координата у в source

				imagecopyresampled ( $dest , $source , 0 , 0 ,  $src_x ,  $src_y , $thumb_width, $thumb_height , $w_img , $h_img );
			}
		
		$path = $upload_dir.$thumb_name;
		imagejpeg($dest,$path, $quality);
		imagedestroy($dest);
		imagedestroy($source);
		return $thumb_name;
	}



?>
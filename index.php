<?php 
ob_start();
	//ini_set('session.cookie_domain', substr($_SERVER['SERVER_NAME'],strpos($_SERVER['SERVER_NAME'],"."),100));
session_start();
	// if(isset($_SESSION['logged'])){
	// 	echo '<script>alert("logged")</script>';
	// }
function filesize_formatted($path)
{
	$size = filesize($path);
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<link rel="stylesheet" type="text/css" href="https://eswomp.it/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="/new.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/colors.css">
	<link rel="stylesheet" type="text/css" href="/cq.css">
	<link rel="icon" type="image/png" href="https://eswomp.it/images/ayy_red.png">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="/cqlib.js"></script>
	<title>CQE</title>
</head>
<body>

	<div id="totop" style="display:none"><i class="fa fa-angle-up"></i></div>
	<div class="hide-menu" data-opacity="0"><i class="fa fa-angle-down"></i></div>
	<div class="menu" id="menu">
		<div class="folder">
			
			<?php
			if(isset($_SESSION['logged'])){
				echo '<a class="element_link_menu" href="https://eswomp.it">
				<div class="links_pad">
				<i class="fa fa-globe"></i> Eswomp
				</div>
				</a>';
				echo '<a class="element_link_menu" href="https://eswomp.it/exit">
				<div class="links_pad">
				<i class="fa fa-sign-out"></i> Wyloguj
				</div>
				</a>';
				echo '<div style="width:100%;margin-top:60px;"></div>';
			}
			echo '<a class="element_link_menu" href="/">
			<div class="links_pad">
			<i class="fa fa-home"></i> Home
			</div>
			</a>';
			if(isset($_GET['dir'])){
				$con = explode("/",$_GET['dir']);
			}
			$chdir = "./photos/";
			$files = array_diff(scandir($chdir), array('..', '.', '.htaccess'));
			sort($files, SORT_STRING | SORT_FLAG_CASE);
			foreach ($files as $key) {
				if(is_dir($chdir.$key))
				{	
					if(in_array($key, $con)) {
						$highlight = 'style="color:#DF3A2F"';
					} else {
						$highlight = "";
					}
					echo '<a class="element_link_menu" href="/dir='.$key.'/">
					<div class="links_pad" '.$highlight.'>
					<i class="fa fa-folder"></i> '.$key.'
					</div>
					</a>';
				}
			}
			?>
			<div class="branding">© eswomp.it 2018 | <a href="https://eswomp.it">Eswomp.it</a></div>
		</div>
	</div>
	<div class="feed">
		<div class="shadow">
			<?php 
			if(isset($_GET['dir']) && !empty($_GET['dir'])){
				if (strpos($_GET['dir'], '<') !== false || strpos($_GET['dir'], '>') !== false || strpos($_GET['dir'], '..') !== false || strpos($_GET['dir'], '/') === 0) {	
					header('Location: /');
					exit;
				}
				echo '
				<div id="element">
				<span class="title">Photos</span>
				<div style="font-size: 15px;padding-bottom: 10px;"><a href="/" class="_link"><i style="font-size:22px;color:#3F5167;vertical-align:middle" class="fa fa-home"></i></a><span class="ffpath"> / </span> ';
				$fpath = explode("/", $_GET['dir']);
				$pth = "";
				$ik=0;
				foreach ($fpath as $key) {
					$ik++;
					if($ik != count($fpath)){
						if($ik == count($fpath)-1)
						{
							$pth = $pth.'/'.$key; 
							echo " <a class=\"_last\">".htmlentities(str_replace("|", "/", $key))."</a> <span class=\"ffpath\"></span>";	
						} else {
							$pth = $pth.'/'.$key; 
							echo " <a class=\"_link\" href=\"/dir=".htmlentities(substr($pth,1))."/\">".htmlentities(str_replace("|", "/", $key))."</a> <span class=\"ffpath\">/</span>";		
						}
					}
				}
				echo '</div></div>';
				echo '<div class="gallery">';
				$folder = $_GET['dir'];
				$chdir = "./photos/".$folder."/";
				$files = array_diff(scandir($chdir), array('..', '.', '.htaccess'));
				sort($files, SORT_STRING | SORT_FLAG_CASE);
				if(count($files) == 0){
					echo '<div style="margin:40px auto;text-align:center;width:auto;background:#EBEBEB;padding:20px 60px;">
					<div><i class="fa fa-warning" style="font-size:50px"></i></div>
					<div class="gallery_folder_name" style="padding:0;margin-top:0;font-size:25px"><span>Preview unavailable</span></div>
					<div class="download"><a class="element_link" style="font-size:16px" href="/zip/'.substr($folder,0,strlen($folder)-1).'.zip">Download zipped photos ('.filesize_formatted(realpath(__DIR__).'/zip/'.substr($folder,0,strlen($folder)-1).'.zip').')</a></div>
					</div>';
				}
				foreach ($files as $key) {
					if(is_dir($chdir.$key))
					{
						echo '<div class="gallery_folder" data-folder="'.$key.'">
						<div class="gallery_folder_name"><span>'.str_replace("|", "/", $key).'</span>
						<div class="download"><a class="element_link" href="/dir='.$folder.$key.'/">Show photos</a></div>
						<div class="download"><a class="element_link" href="/zip/'.$folder.$key.'.zip">Download zip</a></div></div>
						';
						echo '</div>';
					} else {
						if(strtolower(pathinfo($chdir.$key, PATHINFO_EXTENSION)) == "jpg" || 
							strtolower(pathinfo($chdir.$key, PATHINFO_EXTENSION)) == "jpeg" ||
							strtolower(pathinfo($chdir.$key, PATHINFO_EXTENSION)) == "png")
						{
							echo '<a class="ph" href="/photos/'.$_GET['dir']."/".$key.'"><div class="photo">
							<img src="/photos/'.$_GET['dir']."/".$key.'">
							</div></a>';
						} elseif(strtolower(pathinfo($chdir.$key, PATHINFO_EXTENSION)) == "mov" || strtolower(pathinfo($chdir.$key, PATHINFO_EXTENSION)) == "mp4"){
							echo '<a class="ph" href="/photos/'.$_GET['dir']."/".$key.'"><div class="photo">
							<video>
							<source src="/photos/'.$_GET['dir']."/".$key.'">
							</video>
							</div></a>';
						}
					}
				}
			} else {
				$chdir = "./photos/";
				$files = array_diff(scandir($chdir), array('..', '.', '.htaccess'));
				sort($files, SORT_STRING | SORT_FLAG_CASE);
				echo '
				<div id="element">
				<span class="title">Photos</span></div>';
				foreach ($files as $key) {
					if(is_dir($chdir.$key))
					{
						echo '<a class="main-page" href="/dir='.$key.'/"><div class="gallery_folder" data-folder="'.$key.'"><div class="gallery_folder_name__main"><span>'.$key.'</span></div></div></a>';

					}
				}
			}
			?>
		</div>
	</div>
</body>
</html>
 <?php
// 获取B站API
$av = isset($_GET['av']) ? $_GET['av'] : '';
$page = isset($_GET['p']) ? $_GET['p'] : '1';
$handle = file_get_contents("http://www.bilibilijj.com/Api/AvToCid/".$av."");
$content = json_decode($handle, true);
$fanju = $content["list"];
if ($content["msg"] !== "OK"){echo "错误的AV号！";exit;};
// print_r($fanju);
$key = array_search($page, array_column($fanju, 'P'));
if (isset($_GET['d1d'])) {
	// 输出内容
		header('HTTP/1.1 301 Moved Permanently');//发出301头部 
		header('Location:https://www.bilibili.com/html/html5player.html?aid='.$av.'&cid='.$fanju[$key]["CID"].'');//跳转到HTML5的Bilibili
		echo '<br><li><a target="_blank" href="https://www.bilibili.com/html/html5player.html?aid='.$av.'&cid='.$fanju[$key]["CID"].'">'.$content["title"].'</a></li>';
	}else{
		echo '<link rel="stylesheet" href="video.css" type="text/css" />';
		echo "\n";
		echo '<div class="vid-wrapper"><video controls="" width="800" preload="" poster="'.$content["img"].'"><source src="'.$fanju[$key]["Mp4Url"].'" type="video/mp4"/>你的浏览器不支持播放！</video></div>';
	};
?> 

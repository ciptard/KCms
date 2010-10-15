<?php
include "settings.php";
class KCms {
	private function Secure_Title($string) { // "." and "/" are transformed in _ and blank for a security reason
		return str_replace(".", "_", str_replace("/", "", $string));
	}
	
	public function WritePage($content, $title) {
		global $pages_path;
		$fd = fopen($pages_path."/".$this->Secure_Title($title), "w"); 
		fwrite($fd, nl2br($content)); // No htmlspecialchars, no strip/addslashes, if you are admin and you want to write your own HTML or JS/AJAX in your page, you can do it
		fclose($fd);
	}
	
	public function ReadPage($title) {
		global $pages_path;
		$fd = fopen($pages_path."/".$this->Secure_Title($title), "r"); // This transformation is a very high security tip, but if you don't want to receive an XSS attack, you don't have to set $page_path to /
		$read = fread($fd, filesize($pages_path."/".$this->Secure_Title($title)));
		$read .= "<br /><br /><br /><div id=\"footer\">Powered by <a href=\"http://hack2web.altervista.org\">KCms</a></div>";
		return $read;
	}
	
	public function ReadPageEdit($title) {
		global $pages_path;
		$fd = fopen($pages_path."/".$this->Secure_Title($title), "r"); // This transformation is a very high security tip, but if you don't want to receive an XSS attack, you don't have to set $page_path to /
		$read = fread($fd, filesize($pages_path."/".$this->Secure_Title($title)));
		return $read;
	}
	
	public function DeletePage($title) {
		global $pages_path;
		unlink($pages_path."/".$this->Secure_Title($title)); 
	}
	
	public function CreateMenu() {
		global $pages_path, $index_page, $theme;
		$pages = array();
		$dir = opendir($pages_path);
		while (false !== ($file = readdir($dir))) {
			if ($file != "." && $file != "..") {
				array_push($pages, $file);
			}
		}
		
		echo "<link rel=\"stylesheet\" href=\"themes/{$theme}.css\" type=\"text/css\">\n";
		if (isset($logo)) {
			echo "<center><img src=\"{$logo}\" /></center><br /><br />";
		}
		
		echo "<span class=\"div_style\">\n";
		$n = 0;
		foreach($pages as $page) {
			$n++;
			if ($n == count($pages)) { 
				echo "<a href=\"read.php?page=".htmlspecialchars($page)."\" >".htmlspecialchars($page)."</a>";
			} else {
				echo "<a href=\"read.php?page=".htmlspecialchars($page)."\" >".htmlspecialchars($page)."</a> - ";
			}
		}
		echo "</span><br /><br />";
	}
	
	public function Login($user_login, $pass_login) {
		global $user, $pass;
		
		if ($user_login == $user && md5($pass_login) == $pass) {
			session_start();
			$_SESSION['password'] = $pass;
			$_SESSION['user'] = $user;
			$_SESSION['is_logged'] = true;
		} else {
			session_start();
			$_SESSION['is_logged'] = false;
		}
	}
	
	public function Logout() {
		session_destroy();
	}		
}
?>

<?php
include "settings.php";
include "class.Template.php";

class KCms {
	var $preamble;
	var $post;
	
	public function MyConnect() {
		global $host, $my_user, $my_pass, $database;
		mysql_connect($host, $my_user, $my_pass);
		mysql_select_db($database);
	}
	
	
	private function strip_slashes($string) {
		if (get_magic_quotes_gpc()) {
			return stripslashes($string);
		} else {
			return $string;
		}
	}
	
	public function getNames() {
		$names = array();
		$ids = array();
		$res = mysql_query("SELECT title, id FROM kcms") or die(mysql_error());
		
		while ($r = mysql_fetch_array($res)) {
			array_push($names, $r["title"]);
			array_push($ids, $r["id"]);
		}
		
		return array_combine($ids, $names);
	}
	
	public function getLastId() {
		$lastid = null;
		$res = mysql_query("SELECT id FROM kcms") or die(mysql_error());
		
		while ($r = mysql_fetch_array($res)) {
			$lastid = $r["id"];
		}
		
		mysql_free_result($res);
		
		return $lastid;
	}
		
	public function ReadPage($id) {
		$id = intval($id);
		$res = mysql_query("SELECT * FROM kcms WHERE id={$id}") or die(mysql_error());
		$r = mysql_fetch_array($res);
		return array($r["content"], $r["title"]);
	}
	
	
	public function WritePage($content, $title) {
		if ($title == "") {
			return -1;
		}
		
		mysql_query("INSERT INTO kcms VALUES ('', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($content)."')") or die(mysql_error());
		
	}
	
	public function getNamefromId($id) {
		$id = intval($id);
		$res = mysql_query("SELECT title FROM kcms WHERE id={$id}") or die(mysql_error());
		$r = mysql_fetch_row($res);
		return $r[0];
	}
		
	
	public function PageEdit($id, $content, $newtitle = null) {
		$id = intval($id);
		if ($newtitle == null) {
			mysql_query("UPDATE kcms SET content='".mysql_real_escape_string($content)."' WHERE id={$id}") or die(mysql_error());
		} else {
			mysql_query("UPDATE kcms SET content='".mysql_real_escape_string($content)."', title='".mysql_real_escape_string($newtitle)."' WHERE id={$id}") or die(mysql_error());
		}
	}
	
	public function DeletePage($id) {
		$id = intval($id);
		mysql_query("DELETE FROM kcms WHERE id={$id}") or die(mysql_error());
	}
	
	private function findAll($r, $s) {
		$ret = array();
		preg_match_all($r, $s, $ret);
		return $ret;
	}
	
	public function CreateMenu($sel) {
		$menu = $this->getNames();
		$mu_menu = array();
		
		foreach ($menu as $k => $y) {
			if ($k == $sel) {
				array_push($mu_menu, array("index" => $y, "link" => "read.php?id=".$k, "selected" => true));
			} else {
				array_push($mu_menu, array("index" => $y, "link" => "read.php?id=".$k));
			}
		}
		
		return $mu_menu;
	}
	
	public function ParseTemplate($sel, $cont) {
		global $theme, $oth;
		$mus = file_get_contents("themes/".$theme."/template.mustache");
		$info = $this->CreateMenu($sel);
		
		$template = new Template;
		$template->content = $cont;
		$template->menu = $info;
		
		if (isset($oth) and $oth != array()) {
			$template->oth = $oth;
		}
		
		return $template->render($mus);
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
	
	public function LoadPlugins($html, $title) {
		$n = 0;
		$data = $html;
		if (!file_exists("plugins/active.txt")) {
			return $data;
		}
		
		$data1 = file_get_contents("plugins/active.txt");
		$data1 = str_replace("\n", "", $data1);
		
		if ($data1 == "") {
			return $data;
		} 
		
		$active = file("plugins/active.txt");
		foreach ($active as $plugin) {
			$new_data = $html;
			$plugin = str_replace("\n", "", $plugin);
			if (!file_exists("plugins/".$plugin)) {
				die("Plugin: ".$plugin." doesn't exist");
			}
			
			if (!preg_match("/[a-zA-Z0-9]*\.php/", $plugin)) {
				die("Plugin: ".$plugin." isn't a valid plugin");
			}
			
			require("plugins/{$plugin}");
			$func = array();
			$funcs = array(); // maybe, in future, it will have an explaination D:
			preg_match("/([a-zA-Z0-9]*)\.php/", $plugin, $func);
			array_push($funcs, $func[1]);
			$class = new $func[1];
			$n += 1;
			$new_data = $class->main($new_data, $title);
		}
		return $new_data;
	}
	
	public function Main($id) {
		$this->MyConnect();
		$content = $this->ReadPage($id);
		$html = $this->ParseTemplate($id, $this->LoadPlugins($content[0], $this->getNamefromId($id)));
		echo $html;
	}
				
}
?>

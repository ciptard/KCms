<?php
include "settings.php";
include "class.Main.php";
include "themes/{$theme}/settings.php";
$kcms = new KCms;


if (isset($_POST['login']) && isset($_POST['user']) && isset($_POST['pass'])) {
	$kcms->Login($_POST['user'], $_POST['pass']);
	if ($_SESSION['is_logged'] === false) {
		echo "<link rel=\"stylesheet\" href=\"themes/{$css}.css\" type=\"text/css\">\n";
		die("User or pass wrong");
		session_destroy();
	}
	echo "<meta http-equiv=\"Refresh\" content=\"0; url=admin.php\">"; 
} else {
	echo "<link rel=\"stylesheet\" href=\"themes/{$theme}/{$css}\" type=\"text/css\">\n";
	echo "<form method=\"POST\">";
	echo "<label>User: </label><input type=\"text\" name=\"user\"><br />";
	echo "<label>Pass: </label><input type=\"password\" name=\"pass\"><br />";
	echo "<input type=\"submit\" value=\"Login\">";
	echo "<input type=\"hidden\" name=\"login\">";
}
?>

<script language='javascript'>
function hide_div(id)
{
  document.getElementById(id).style.display = 'none';
  document.cookie=id+'=0;';
}
function show_div(id)
{
  document.getElementById(id).style.display = 'block';
  document.cookie=id+'=1;';
}
function change_divst(id)
{
  if (document.getElementById(id).style.display == 'none')
    show_div(id);
  else
    hide_div(id);
}


</script>
<?php

function br2nl($string) {
	return str_replace("<br />", "", $string);
} 
set_magic_quotes_runtime(false);
include "class.Main.php";
include "settings.php";
echo "<link rel=\"stylesheet\" href=\"themes/{$theme}.css\" type=\"text/css\">\n";
$kcms = new KCms;
@session_start();
if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true) {
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id1')\">Write</a></center>";
	echo "<div id=\"id1\" style=\"display: none;\">\n";
	echo "<form method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"write\">";
	echo "<input type=\"hidden\" value=\"".htmlspecialchars($_COOKIE['PHPSESSID'])."\" name=\"sid\">";
	echo "<label>Title: </label><input type=\"text\" name=\"title\"><br />\n";
	echo "<label>Content: </label><br />\n";
	echo "<textarea cols=121 rows=15 name=\"content\"></textarea><br />\n";
	echo "<input type=\"submit\" value=\"Add\">";
	echo "</form>";
	echo "</div>";
	
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id2')\">Delete</a></center>";
	echo "<div id=\"id2\" style=\"display: none;\">\n";
	echo "<form method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"delete\">";
	echo "<input type=\"hidden\" value=\"".htmlspecialchars($_COOKIE['PHPSESSID'])."\" name=\"sid\">";
	echo "<label>Title: </label><input type=\"text\" name=\"title\"><br />\n";
	echo "<input type=\"submit\" value=\"Delete\">";
	echo "</form>";
	echo "</div>";
	
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id3')\">Edit</a></center>";
	echo "<div id=\"id3\" style=\"display: none;\">\n";
	echo "<form method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"edit\">";
	echo "<input type=\"hidden\" value=\"".htmlspecialchars($_COOKIE['PHPSESSID'])."\" name=\"sid\">";
	echo "<label>Title: </label><input type=\"text\" name=\"title\"><br />\n";
	echo "<input type=\"submit\" value=\"Edit\">";
	echo "</form>";
	echo "</div>";
}

if (isset($_POST['write'])) {
	if (!isset($_POST['content']) || !isset($_POST['title']) || !isset($_POST['sid'])) {
		die("You must complete all fields (or you aren't logged)");
	}
	if ($_POST['sid'] != $_COOKIE['PHPSESSID'] || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	$kcms->WritePage($_POST['content'], $_POST['title']);
}

if (isset($_POST['delete'])) {
	if (!isset($_POST['title']) || !isset($_POST['sid'])) {
		die("You must complete all fields (or you aren't logged)");
	}
	if ($_POST['sid'] != $_COOKIE['PHPSESSID'] || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	$kcms->DeletePage($_POST['title']);
}

if (isset($_POST['edit'])) {
	if (!isset($_POST['title']) || !isset($_POST['sid'])) {
		die("You must complete all fields (or you aren't logged)");
	}
	if ($_POST['sid'] != $_COOKIE['PHPSESSID'] || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	echo "<form method=\"POST\">\n";
	echo "<label>New content: </label><br />\n";
	echo "<textarea cols=121 rows=15 name=\"new_content\">";
	echo htmlspecialchars(br2nl($kcms->ReadPageEdit($_POST['title'])), ENT_NOQUOTES);
	echo "</textarea><br />\n";
	echo "<input type=\"hidden\" name=\"sid\" value=\"".htmlspecialchars($_COOKIE['PHPSESSID'])."\">\n";
	echo "<input type=\"hidden\" name=\"title\" value=\"".htmlspecialchars($_POST['title'])."\">\n";
	echo "<input type=\"hidden\" name=\"edit_content\">\n";
	echo "<input type=\"submit\" value=\"Edit\">";
}
	
if (isset($_POST['edit_content'])) {
	if (!isset($_POST['title']) || !isset($_POST['sid']) || !isset($_POST['new_content'])) {
		die("You must complete all fields (or you aren't logged)");
	}

	if ($_POST['sid'] != $_COOKIE['PHPSESSID'] || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	$kcms->WritePage(htmlspecialchars_decode($_POST['new_content']), htmlspecialchars_decode($_POST['title']));
}

?>

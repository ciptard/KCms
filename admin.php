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
<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce_dev.js"></script>
<script type="text/javascript">
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        skin : "o2k7",
        skin_variant : "black",

        template_external_list_url : "tinymce/jscripts/tiny_mce/template_list.js",
        external_link_list_url : "tinymce/jscripts/tiny_mce/link_list.js",
        external_image_list_url : "tinymce/jscripts/tiny_mce/image_list.js",
        media_external_list_url : "tinymce/jscripts/tiny_mce/media_list.js",
});
</script>

<?php
error_reporting(E_ALL);
include "settings.php";
include "class.Main.php";
include "settings.php";
include "themes/{$theme}/settings.php";


$kcms = new KCms;
$kcms->MyConnect();
$names = $kcms->getNames();

echo "<link rel=\"stylesheet\" href=\"themes/{$theme}/{$css}\" type=\"text/css\">\n";
@session_start();
if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true) {
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id1')\">Write</a></center>";
	echo "<div id=\"id1\" style=\"display: none;\">\n";
	echo "<form method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"write\">";
	echo "<input type=\"hidden\" value=\"".htmlspecialchars(session_id())."\" name=\"sid\">";
	echo "<label>Title: </label><input type=\"text\" name=\"title\"><br />\n";
	echo "<label>Content: </label><br />\n";
	echo "<textarea cols=60 rows=8 name=\"content\"></textarea><br />\n";
	echo "<input type=\"submit\" value=\"Add\">";
	echo "</form>";
	echo "</div>";
	
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id2')\">Delete</a></center>";
	echo "<div id=\"id2\" style=\"display: none;\">\n";
	$sid = htmlspecialchars(session_id());
	foreach (array_keys($names) as $id) {
		echo "<a href=\"admin.php?id=".$id."&sid=".$sid."&delete\">".$names[$id]."</a> ";
	}
	echo "</div>";
	
	echo "<center><a style=\"cursor: pointer;\" onClick=\"change_divst('id3')\">Edit</a></center>";
	echo "<div id=\"id3\" style=\"display: none;\">\n";
	foreach (array_keys($names) as $id) {
		echo "<a href=\"admin.php?id=".$id."&sid=".$sid."&edit\">".$names[$id]."</a> ";
	}
	echo "</div>";
}

if (isset($_POST['write'])) {
	if (!isset($_POST['content']) || !isset($_POST['title']) || !isset($_POST['sid'])) {
		die("You must complete all fields (or you aren't logged)");
	}
	
	if ($_REQUEST['sid'] != session_id() || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	$kcms->WritePage($_REQUEST['content'], $_POST['title']);
}

if (isset($_REQUEST['delete'])) {
	if (!isset($_REQUEST['id']) || !isset($_REQUEST['sid'])) {
		die("You must complete all fields (or you aren't logged)");
	}
	if ($_REQUEST['sid'] != session_id() || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	$kcms->DeletePage($_REQUEST['id']);
}

	
if (isset($_REQUEST['edit'])) {
	if (isset($_REQUEST['id']) && isset($_REQUEST['sid']) && $_REQUEST['sid'] == session_id() && isset($_REQUEST['edit']) && !isset($_REQUEST['newtitle']) && !isset($_REQUEST['edit_content'])) {
		$cont = $kcms->ReadPage($_REQUEST['id']);
		echo "\n<form method=\"POST\" action=\"admin.php?edit\">\n";
		echo "<label>New Title: </label><input type=\"text\" name=\"new_title\" value=\"{$cont[1]}\"><br />";
		echo "<label>New Content: </label><br />\n";
		echo "<textarea cols=121 rows=15 name=\"edit_content\">{$cont[0]}";
		echo "</textarea><br />\n";
		echo "<input type=\"hidden\" name=\"sid\" value=\"".session_id()."\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"".htmlentities($_REQUEST['id'])."\">\n";
		echo "<input type=\"hidden\" name=\"edit\">\n";
		echo "<input type=\"submit\" value=\"Edit\">\n";
		echo "</form>";
	}
	

	if ($_REQUEST['sid'] != session_id() || !isset($_SESSION['is_logged']) || $_SESSION['is_logged'] === false ) {
		die("Are you trying to hacking KCms?");
	}
	
	if (isset($_REQUEST['id']) && isset($_REQUEST['sid']) && $_REQUEST['sid'] == session_id() && isset($_REQUEST['edit']) && isset($_REQUEST['new_title']) && isset($_REQUEST['edit_content'])) {
		$kcms->PageEdit($_REQUEST['id'],  $_REQUEST['edit_content'], $_REQUEST['new_title']);
	}
	
}
/*
		echo "<form method=\"POST\">\n";
		echo "<label>New content: </label><br />\n";
		echo "<textarea cols=121 rows=15 name=\"new_cjs\">";
		echo htmlspecialchars(br2nl($cont[0], ENT_NOQUOTES));
		echo "</textarea><br />\n";
		echo "<label>New Javascript: </label><br />\n";
		echo "<textarea cols=121 rows=15 name=\"new_content\">";
		echo htmlspecialchars(br2nl($cont[1], ENT_NOQUOTES));
		echo "</textarea><br />\n";
		echo "<input type=\"hidden\" name=\"sid\" value=\"".session_id()."\">\n";
		echo "<input type=\"hidden\" name=\"title\" value=\"".htmlspecialchars($_REQUEST['title'])."\">\n";
		echo "<input type=\"hidden\" name=\"edit_content\">\n";
		echo "<input type=\"submit\" value=\"Edit\">";
*/

if (isset($out_write)) {
	echo $out_write;
}

if (isset($out_edit)) {
	echo $out_edit;
}
?>

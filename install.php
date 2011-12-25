<?php
if (!isset($_POST['user']) || !isset($_POST['pass']) || !isset($_POST['host_user']) || !isset($_POST['host_pass']) || !isset($_POST['host']) || !isset($_POST['db'])) {
?>
<form method="post">
<label>Username: </label><input type="text" name="user"><br />
<label>Password: </label><input type="password" name="pass"><br />
<label>MySQL Username: </label><input type="text" name="host_user"><br />
<label>MySQL Password: </label><input type="text" name="host_pass"><br />
<label>MySQL DataBase: </label><input type="text" name="db"><br />
<label>MySQL Host: </label><input type="text" name="host" value="localhost"><br />
<input type="submit" value="Send">
</form>
<?php
} else {
	mysql_connect($_POST['host'], $_POST['host_user'], $_POST['host_pass']) or die(mysql_error());
	mysql_select_db($_POST['db']);
	mysql_query("CREATE TABLE kcms (id INT NOT NULL AUTO_INCREMENT KEY, title VARCHAR(500) NOT NULL, content LONGTEXT NOT NULL )") or die(mysql_error());
	$fd = fopen("settings.php", "w");
	fwrite($fd, "<?php\n\$index_id = 1;\n\$my_user = '{$_POST['host_user']}';\n\$my_pass = '{$_POST['host_pass']}';\n\$host = '{$_POST['host']}';\n\$database = '{$_POST['db']}'; \n\$pass = '".md5($_POST['pass'])."';\n\$user = '{$_POST['user']}';\n\$theme = 'default';\n\$opt = array();\n?>");
	fclose($fd);
	mysql_query("INSERT INTO kcms VALUES ('', 'Home', 'This is your first page, edit or delete it by going <a href=\"login.php\">here</a>')") or die(mysql_error());
	unlink("install.php");
	header("Location: index.php");
}
?>

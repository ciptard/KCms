<?php
class Title {
	public function main($data, $title) {
		$data = str_replace("<title></title>", "<title>{$title}</title>", $data);
		return $data;
	}
}
?>

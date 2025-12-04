<?php
require_once __DIR__ . '/model.php';
class View {
	// wypisz okienko JS ze zmienną SERVER_MENU (używana przez client-side controller.js)
	public static function printServerMenuScript() {
		echo "<script>window.SERVER_MENU = " . Model::getMenuJson() . ";</script>\n";
	}

	// krótkie helpery do komunikatów flash (możesz je użyć w HTML)
	public static function printFlashMessages() {
		if ($m = Model::flashGet('success')) echo "<div class='flash flash-success'>{$m}</div>";
		if ($m = Model::flashGet('error')) echo "<div class='flash flash-error'>{$m}</div>";
	}
}

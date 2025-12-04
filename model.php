<?php
class Model {
	// ...inicjalizacja i dane...
	public static function initSession() {
		if (session_status() === PHP_SESSION_NONE) session_start();
	}
	public static function getMenu(): array {
		return [
			["name"=>"Margherita","desc"=>"Klasyczna pizza z bazylią","price"=>22],
			["name"=>"Diavola","desc"=>"Pikantna pizza z chili","price"=>29],
			["name"=>"Capricciosa","desc"=>"Szynka, pieczarki, oliwki","price"=>32]
		];
	}
	public static function getMenuJson(): string {
		return json_encode(self::getMenu(), JSON_UNESCAPED_UNICODE);
	}

	// sesyjne opinie / cart / orders / flash
	public static function getReviews(): array { self::initSession(); return $_SESSION['reviews'] ?? []; }
	public static function addReview(int $rating, string $text) { self::initSession(); $_SESSION['reviews'][] = ['rating'=>$rating,'text'=>$text]; }
	public static function getCart(): array { self::initSession(); return $_SESSION['cart'] ?? []; }
	public static function addToCart(array $item) { self::initSession(); $_SESSION['cart'][] = $item; }
	public static function clearCart() { self::initSession(); $_SESSION['cart'] = []; }
	public static function placeOrder(): ?string {
		self::initSession();
		$cart = $_SESSION['cart'] ?? [];
		if (empty($cart)) return null;
		$num = 'KP' . mt_rand(0, 99999);
		$_SESSION['orders'][] = ['number'=>$num,'items'=>$cart];
		$_SESSION['cart'] = [];
		return $num;
	}
	public static function flashSet(string $k, $v) { self::initSession(); $_SESSION['flash'][$k] = $v; }
	public static function flashGet(string $k) { self::initSession(); $val = $_SESSION['flash'][$k] ?? null; if(isset($_SESSION['flash'][$k])) unset($_SESSION['flash'][$k]); return $val; }
}

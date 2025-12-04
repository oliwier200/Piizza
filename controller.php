<?php
require_once __DIR__ . '/model.php';
class Controller {
	public static function handleRequest() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') return; // nic do zrobienia
		// przetwarzanie prostych akcji
		$action = $_POST['action'] ?? '';
		switch ($action) {
			case 'add_review':
				$rating = intval($_POST['rating'] ?? 5);
				$text = trim($_POST['text'] ?? '');
				if ($text === '') Model::flashSet('error', 'Napisz opinię!');
				else { Model::addReview($rating, $text); Model::flashSet('success', 'Dodano opinię.'); }
				break;
			case 'place_order':
				$num = Model::placeOrder();
				if ($num) Model::flashSet('success', "Dziękujemy! Numer zamówienia: {$num}");
				else Model::flashSet('error', 'Koszyk jest pusty!');
				break;
			case 'reserve':
				$people = intval($_POST['people'] ?? 2);
				$time = $_POST['time'] ?? '';
				$name = trim($_POST['name'] ?? '');
				if (!$time || $name === '') Model::flashSet('error', 'Wypełnij wszystkie pola!');
				else Model::flashSet('success', "Zarezerwowano stolik dla {$people} osób o {$time}. Dziękujemy, {$name}!");
				break;
			// możesz dodać kolejne akcje (np. add_to_cart) i korzystać z Model::addToCart(...)
		}
		// po obsłudze POST -> redirect (PRG) aby uniknąć ponownego wysyłania
		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;
	}
}

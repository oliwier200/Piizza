<?php
header('Content-Type: text/html; charset=utf-8');

// usuń bezpośrednie $menuData - zamiast tego dołącz MVC:
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/view.php';
require_once __DIR__ . '/controller.php';

// obsłuż POST (jeśli są) przed wysłaniem HTML (PRG)
Controller::handleRequest();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Kalab Pizza – Najlepsza włoska pizza online</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* ==========================================
        PALETA I ZMIENNE
========================================== */
:root {
    --red: #c20808;
    --green: #1c8c41;
    --cream: #f7f3e9;
    --dark: #1b1b1b;
    --card-bg: #ffffff;
    --card-bg-dark: #2b2b2b;
    --text-dark: #111;
    --text-light: #fff;
    --shadow: 0 4px 14px #00000025;
    --radius: 14px;
}

body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: var(--cream);
    color: var(--text-dark);
    transition: 0.3s;
}

.dark {
    background: var(--dark);
    color: var(--text-light);
}

/* ==========================================
        NAWIGACJA + HAMBURGER
========================================== */
.nav {
    background: linear-gradient(90deg, var(--green), white, var(--red));
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 99;
}
.nav__logo {
    font-size: 24px;
    font-weight: bold;
}
.nav__toggle {
    display: none;
    background: none;
    border: none;
    font-size: 26px;
    cursor: pointer;
}
.nav__menu {
    display: flex;
    gap: 25px;
    list-style: none;
    margin: 0;
}
.nav__menu a {
    text-decoration: none;
    color: #000;
    font-size: 18px;
    font-weight: 500;
}

/* MOBILE */
@media (max-width: 768px) {
    .nav__toggle { display: block; }
    .nav__menu {
        position: absolute;
        top: 60px;
        right: 0;
        background: white;
        padding: 20px;
        flex-direction: column;
        display: none;
        width: 200px;
        box-shadow: var(--shadow);
    }
    .nav__menu.show { display: flex; }
}

/* ==========================================
        NAGŁÓWEK
========================================== */
.header {
    text-align: center;
    padding: 60px 20px;
    background: var(--cream);
}
.header__title {
    font-size: 40px;
    margin-bottom: 12px;
}
.header__subtitle {
    font-size: 22px;
    opacity: 0.8;
}

/* ==========================================
        SEKCJE
========================================== */
.section {
    padding: 40px 20px;
}
.section__title {
    font-size: 30px;
    margin-bottom: 25px;
    text-align: center;
}

/* ==========================================
        KARTY
========================================== */
.card {
    background: var(--card-bg);
    padding: 20px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 25px;
}
.dark .card { background: var(--card-bg-dark); }

/* ==========================================
        MENU
========================================== */
.menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 25px;
}
.menu-item h3 { margin-top: 0; }

/* ==========================================
        GALERIA
========================================== */
.gallery {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.gallery img {
    width: 30%;
    min-width: 250px;
    margin: 10px;
    border-radius: 12px;
}

/* ==========================================
        GWIAZDKI
========================================== */
.stars {
    font-size: 28px;
    color: gold;
    cursor: pointer;
}
.stars span { margin-right: 8px; }

/* ==========================================
        PRZYCISKI
========================================== */
button {
    background: var(--green);
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    margin-top: 10px;
    cursor: pointer;
}
button:hover {
    background: #136e2a;
}

/* Dark mode switch */
.dark-btn {
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: black;
    color: white;
    padding: 12px 16px;
    border-radius: 50%;
    cursor: pointer;
}

/* ==========================================
        FOOTER
========================================== */
.footer {
    text-align: center;
    padding: 20px;
    background: #ddd;
}
.dark .footer { background: #222; color: white; }
</style>
</head>
<body>

<!-- ========================
        NAV
======================== -->
<nav class="nav">
    <div class="nav__logo">🍕 Kalab Pizza</div>
    <button id="navToggle" class="nav__toggle">☰</button>
    <ul class="nav__menu" id="navMenu">
        <li><a href="#menu">Menu</a></li>
        <li><a href="#koszyk">Koszyk</a></li>
        <li><a href="#promo">Promocje</a></li>
        <li><a href="#galeria">Galeria</a></li>
        <li><a href="#opinie">Opinie</a></li>
        <li><a href="#rezerwacja">Rezerwacja</a></li>
    </ul>
</nav>

<button class="dark-btn" id="darkBtn">🌙</button>

<header class="header">
    <h1 class="header__title">Najlepsza pizza prosto z włoskiego pieca</h1>
    <p class="header__subtitle">Smak, którego nie zapomnisz 🇮🇹</p>
</header>

<!-- ========================
        PROMOCJA
======================== -->
<section class="section" id="promo">
    <h2 class="section__title">🔥 Promocje dnia</h2>

    <div class="card">
        <h3>⏳ Druga pizza -50%</h3>
        <p>Pozostało: <b id="promoTimer">20:00</b></p>
        <p class="promo__desc">Promocja automatycznie pojawi się w koszyku.</p>
    </div>
</section>

<!-- ========================
        MENU
======================== -->
<section class="section" id="menu">
    <h2 class="section__title">🍽 MENU</h2>
    <div class="menu" id="menuList"></div>
</section>

<!-- ========================
        KOSZYK
======================== -->
<section class="section" id="koszyk">
    <h2 class="section__title">🛒 Koszyk</h2>

    <div class="card">
        <div id="cartItems"></div>

        <h3>Podsumowanie</h3>
        <p>Wartość koszyka: <b id="cartTotal">0</b> zł</p>
        <p>Dostawa: <b id="deliveryCost">0</b> zł</p>
        <h3>Razem: <span id="grandTotal">0</span> zł</h3>

        <label>Dostawa:</label>
        <select id="deliverySelect">
            <option value="0">Odbiór osobisty – 0 zł</option>
            <option value="8">Dostawa standard – 8 zł</option>
            <option value="12">Dostawa ekspres – 12 zł</option>
        </select>

        <h3>Kod rabatowy</h3>
        <input id="couponInput" placeholder="ITALIA10 / PIZZA15">
        <button id="applyCoupon">Zastosuj</button>

        <button id="placeOrder">Złóż zamówienie</button>
        <p id="orderMessage"></p>
    </div>
</section>

<!-- ========================
        GALERIA
======================== -->
<section class="section" id="galeria">
    <h2 class="section__title">📸 Galeria</h2>
    <div class="gallery">
        <img src="https://images.unsplash.com/photo-1594007654729-407eedc4be3f">
        <img src="https://images.unsplash.com/photo-1601924572785-d318c6b471aa">
        <img src="https://images.unsplash.com/photo-1550317138-10000687a72b">
    </div>
</section>

<!-- ========================
        OPINIE
======================== -->
<section class="section" id="opinie">
    <h2 class="section__title">⭐ Opinie klientów</h2>

    <div class="card">
        <h3>Dodaj opinię</h3>

        <div class="stars" id="reviewStars"></div>

        <input id="reviewText" placeholder="Twoja opinia...">
        <button id="addReview">Dodaj opinię</button>
    </div>

    <h3>Średnia ocena: <span id="avgRating">0</span>/5</h3>
    <div id="reviewList"></div>
</section>

<!-- ========================
        REZERWACJA
======================== -->
<section class="section" id="rezerwacja">
    <h2 class="section__title">📅 Rezerwacja stolika</h2>

    <div class="card">
        <label>Liczba osób:</label>
        <input type="number" id="resPeople" min="1" max="12" value="2">

        <label>Godzina:</label>
        <input type="time" id="resTime">

        <label>Imię i nazwisko:</label>
        <input id="resName">

        <button id="resBtn">Rezerwuj</button>
        <p id="resMsg"></p>
    </div>
</section>

<footer class="footer">
    © 2025 Kalab Pizza – Autentyczny włoski smak 🇮🇹
</footer>

<!-- ========================
        JAVASCRIPT
======================== -->
<?php View::printFlashMessages(); ?>
<?php View::printServerMenuScript(); ?>
<script type="module" src="./controller.js"></script>
</body>
</html>

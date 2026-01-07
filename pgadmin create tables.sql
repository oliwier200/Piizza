-- Tworzenie tabeli produktów
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price NUMERIC(10, 2) NOT NULL,
    image_url VARCHAR(255)
);

-- Tworzenie tabeli zamówień
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_address TEXT NOT NULL,
    total_price NUMERIC(10, 2) NOT NULL,
    order_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tworzenie tabeli elementów zamówienia (relacja wiele-do-wielu)
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id),
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER NOT NULL,
    price_at_time NUMERIC(10, 2) NOT NULL
);

-- Przykładowe dane (żeby menu nie było puste)
INSERT INTO products (name, description, price, image_url) VALUES
('Margherita', 'Sos pomidorowy, mozzarella, bazylia', 25.00, 'margherita.jpg'),
('Pepperoni', 'Sos pomidorowy, mozzarella, salami pepperoni', 32.00, 'pepperoni.jpg'),
('Hawajska', 'Sos pomidorowy, mozzarella, szynka, ananas', 30.00, 'hawaii.jpg'),
('Capricciosa', 'Sos, ser, szynka, pieczarki', 34.00, 'capri.jpg');

-- Tabela administratorów
CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Dodanie domyślnego administratora (login: admin, hasło: admin)
INSERT INTO admins (username, password) VALUES ('admin', 'admin');
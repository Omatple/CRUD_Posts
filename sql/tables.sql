CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) UNIQUE NOT NULL,
    password VARCHAR(60) NOT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    status ENUM('PUBLICADO', 'BORRADOR') NOT NULL,
    category_id INT NOT NULL,
    CONSTRAINT fk_posts_categories FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
);
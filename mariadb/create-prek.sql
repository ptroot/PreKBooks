CREATE DATABASE prekbooks;
USE prekbooks;

-- =========================
-- BOX TABLE
-- =========================
CREATE TABLE box (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL UNIQUE
);

-- =========================
-- BOOK TABLE
-- =========================
CREATE TABLE book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    box_id INT,
    
    INDEX idx_book_box_id (box_id),

    CONSTRAINT fk_book_box
        FOREIGN KEY (box_id) 
        REFERENCES box(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================
-- OCCASION TABLE
-- =========================
CREATE TABLE occasion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL UNIQUE
);

-- =========================
-- OCC_BOOK (JUNCTION TABLE)
-- =========================
CREATE TABLE occ_book (
    book_id INT NOT NULL,
    occasion_id INT NOT NULL,
    
    PRIMARY KEY (book_id, occasion_id),

    INDEX idx_occbook_occasion_id (occasion_id),

    CONSTRAINT fk_occbook_book
        FOREIGN KEY (book_id)
        REFERENCES book(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_occbook_occasion
        FOREIGN KEY (occasion_id)
        REFERENCES occasion(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);




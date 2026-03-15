use prekbooks;
-- examples for creating the database in a batch rather than the with the gui

-- Boxes
INSERT INTO box (label) VALUES
('Box 1'),
('Box 2'),
('Box 3'),
('Box 4'),
('Box 5'),
('Box 6');

-- Occasions
INSERT INTO occasion (label) VALUES
('Birthday'),
('Christmas'),
('Bedtime'),
('All about Me'),
('Fall'),
('Halloween'),
('Fire Safety'),
('Thanksgiving'),
('Winter'),
('Valentines Day'),
('Spring'),
('Easter'),
('Noah'),
('Dinosaur'),
('Ocean'),
('Space'),
('Resturant'),
('Farm'),
('Summer'),
('Math/Science'),
('Transportation'),
('Dental Health'),
('Miscellaneous'),
('Books on CD');

-- Book ↔ Occasion relationships
-- INSERT INTO occ_book (book_id, occasion_id) VALUES
-- (1, 3),  -- Caterpillar → Bedtime
-- (2, 3),  -- Goodnight Moon → Bedtime
-- (3, 1),  -- Wild Things → Birthday
-- (4, 2),  -- Snowy Day → Christmas
-- (4, 4);  -- Snowy Day → Classroom

-- Books
-- INSERT INTO book (title, author, box_id) VALUES
-- ('The Very Hungry Caterpillar', 'Eric Carle', 1),
-- ('Goodnight Moon', 'Margaret Wise Brown', 1),
-- ('Where the Wild Things Are', 'Maurice Sendak', 2),
-- ('The Snowy Day', 'Ezra Jack Keats', 3);

INSERT INTO book (title, author, box_id) VALUES
('The Very Hungry Caterpillar', 'Eric Carle', 1),
('Goodnight Moon', 'Margaret Wise Brown', 1),
('The Dead Tree', 'Alvin Tresselt', 1),
('Treasury for Children', 'James Herriot', 1),
('Duck for President', 'Doreen Cronin, Betsy Lewin', 1),
('If I ran the Circus', 'Dr Seuss', 1),
('Zomo The Rabbit', 'Gerald McDermott', 1),
('Where the Wild Things Are', 'Marice Sendak', 1);

INSERT INTO occ_book (book_id, occasion_id) VALUES
(1,11),
(1,17),
(2,3),
(7,15),
(7,21);


use prekbooks;
-- Here are examples for creating the database in a batch rather than the with the gui

--	These SQL commands can be run with the mariadb (or mysql) command, or pasted into the SQL tab in phpMyAdmin for
--		the prekbooks database.

-- 		WARNING: using these commands for batch is prone to typographical errors, as indexes are used to link the elements
--				 together, and those indexes are internal to the system, and not displayed to the user in the web pages.
--				 It is recommended to do entry with the web page.

-- Boxes
--		Simply creating names for the boxes. They can be anything. 
--		The name is connected to an automatically incrementing index beginning at 1. 
--		This index is what is used to link a book to a box. 
--		This index is not tied to any number that may appear in the name. Since the
--			below list of box names is in numeric order, the index matches with the number in the box name. 
--			i.e. Box 1 has will have an index of 1 because it is the first label entered into the database.
--			However, PS 1 will have an index of 6 in the below example.
INSERT INTO box (label) VALUES
('Box 1'),
('Box 2'),
('Box 3'),
('Box 4'),
('Box 5'),
('PS 1');

-- Occasions/Themes
--		These are the themes you might want to reference to find a book
--		These are searchable in the search field. And are not case sensitive
--		These are used for example only. use whatever themes make sense to you for your curriculum
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

-- Books
-- 		Just adding a few books for testing
--		A book title is added to the database connecting it to an auto-incrementing index beginning with 1
--		The author is connected with that same index, and the index for the box is connected.  
--		The box index is prone to mistakes, better to use the web page.

INSERT INTO book (title, author, box_id) VALUES
('The Very Hungry Caterpillar', 'Eric Carle', 1),
('Goodnight Moon', 'Margaret Wise Brown', 1),
('The Dead Tree', 'Alvin Tresselt', 1),
('Treasury for Children', 'James Herriot', 1),
('Duck for President', 'Doreen Cronin, Betsy Lewin', 1),
('If I ran the Circus', 'Dr Seuss', 1),
('Zomo The Rabbit', 'Gerald McDermott', 1),
('Where the Wild Things Are', 'Marice Sendak', 1);

-- Book to Theme relationships
--		Both the Book and Theme must already be entered into the database
--		The book and the theme are referenced by an automatically assigned index
--			which starts at 1 and increments by 1
--		This is the table most likely to have errors in batch entry. It is better to use the web page.      

INSERT INTO occ_book (book_id, occasion_id) VALUES
(1,11),
(1,17),
(2,3),
(7,15),
(7,21);


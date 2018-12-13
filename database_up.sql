CREATE DATABASE product_search;
CREATE DATABASE product_search_test;

CREATE TABLE IF NOT EXISTS product_search.product
(
  id          INT AUTO_INCREMENT
    PRIMARY KEY,
  name        VARCHAR(45) NOT NULL,
  brand       VARCHAR(25) NOT NULL,
  description TEXT        NOT NULL
);

INSERT INTO product_search.product (name, brand, description) VALUES
('Flowered Dress', 'Sunflower', 'A beautiful flowered dress perfect for a flowered person.'),
('Daisy Hat', 'Sunflower', 'A cute daisy-shaped hat which is the latest trend in the garden!'),
('Banana Pajama', 'Banana Boat', 'A yellow pajama with brown ellipses on it.'),
('Banana shoes', 'Banana Boat', 'A pair of shoes that makes you look a little bit out of the box.'),
('Banana Leaf Coat', 'Banana Boat', 'A comfortable coat entirely made of banana leaves.'),
('Aviator Sunglasses', 'Tropical Wear', 'If you want to fly away, make sure to have one of these.'),
('Beach Shoes', 'Tropical Wear', 'A pair of shoes to use on beach. They are fresh and maintain your feet free of sweat.'),
('Beach Coat', 'Tropical Wear', 'If you think that bikinis are too revealing, this product is perfect for you!'),
('Clover Glasses', 'Sunflower', 'Put some luck in your life with the brand new product of our eyewear collection!'),
('New Year\'s Eve Dress', 'Primark', 'A white dress that helps your dreams come true in the next year.'),
('Black Jeans', 'Primark', 'An all purpose casual pair of jeans.'),
('Black Shoes', 'Primark', 'A classic pair of shoes that matches with your lifestyle.'),
('Black Sneakers', 'Primark', 'For youngsters of all ages.'),
('Trekking Bag', 'Ecko', 'A waterproof bag to carry all you will need into the wild.'),
('Trekking Boots', 'Ecko', 'A resistant and waterproof pair of boots to face all the terrains.'),
('Draconic Dress', 'Eastern Lands', 'A printed dress with cute dragons on it.'),
('Moon Dress', 'Eastern Lands', 'A white dress, printed with rabbits making rice balls.')
;

INSERT INTO product_search_test.product (name, brand, description) VALUES
('Flowered Dress', 'Sunflower', 'A beautiful flowered dress perfect for a flowered person.'),
('Daisy Hat', 'Sunflower', 'A cute daisy-shaped hat which is the latest trend in the garden!'),
('Banana Pajama', 'Banana Boat', 'A yellow pajama with brown ellipses on it.'),
('Banana shoes', 'Banana Boat', 'A pair of shoes that makes you look a little bit out of the box.'),
('Banana Leaf Coat', 'Banana Boat', 'A comfortable coat entirely made of banana leaves.'),
('Aviator Sunglasses', 'Tropical Wear', 'If you want to fly away, make sure to have one of these.'),
('Beach Shoes', 'Tropical Wear', 'A pair of shoes to use on beach. They are fresh and maintain your feet free of sweat.'),
('Beach Coat', 'Tropical Wear', 'If you think that bikinis are too revealing, this product is perfect for you!'),
('Clover Glasses', 'Sunflower', 'Put some luck in your life with the brand new product of our eyewear collection!'),
('New Year\'s Eve Dress', 'Primark', 'A white dress that helps your dreams come true in the next year.'),
('Black Jeans', 'Primark', 'An all purpose casual pair of jeans.'),
('Black Shoes', 'Primark', 'A classic pair of shoes that matches with your lifestyle.'),
('Black Sneakers', 'Primark', 'For youngsters of all ages.'),
('Trekking Bag', 'Ecko', 'A waterproof bag to carry all you will need into the wild.'),
('Trekking Boots', 'Ecko', 'A resistant and waterproof pair of boots to face all the terrains.'),
('Draconic Dress', 'Eastern Lands', 'A printed dress with cute dragons on it.'),
('Moon Dress', 'Eastern Lands', 'A white dress, printed with rabbits making rice balls.')
;
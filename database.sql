
-- Admin table
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    pass VARCHAR(100) NOT NULL
);

-- user table
CREATE TABLE user (
    u_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    coin INT DEFAULT 0,
    flag BOOLEAN DEFAULT FALSE,
    duration INT
);

-- Game table
CREATE TABLE Game (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    rating DECIMAL(3,2),
    price DECIMAL(10,2),
    space INT
);

-- Thread table
CREATE TABLE Thread (
    thread_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    u_id INT,
    flag BOOLEAN DEFAULT FALSE,
    admin_id INT,
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (admin_id) REFERENCES Admin(admin_id)
);

-- Thread Comments table
CREATE TABLE Thread_Comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT,
    comments TEXT NOT NULL,
    FOREIGN KEY (thread_id) REFERENCES Thread(thread_id)
);

-- Participate table
CREATE TABLE Participate (
    u_id INT,
    thread_id INT,
    PRIMARY KEY (u_id, thread_id),
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (thread_id) REFERENCES Thread(thread_id)
);

-- Buy relationship table
CREATE TABLE Buy (
    u_id INT,
    game_id INT,
    PRIMARY KEY (u_id, game_id),
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

-- Friend table
CREATE TABLE Friend (
    fr1_id INT,
    fr2_id INT,
    PRIMARY KEY (fr1_id, fr2_id),
    FOREIGN KEY (fr1_id) REFERENCES user(u_id),
    FOREIGN KEY (fr2_id) REFERENCES user(u_id)
);

-- user_Game_List table
CREATE TABLE user_Game_List (
    u_id INT,
    game_id INT,
    PRIMARY KEY (u_id, game_id),
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

-- Transaction table
CREATE TABLE Transaction (
    tr_id INT AUTO_INCREMENT PRIMARY KEY,
    amount INT,
    u_id INT,
    FOREIGN KEY (u_id) REFERENCES user(u_id)
);

-- Transaction method table
CREATE TABLE Transaction_Method (
    tr_id INT,
    method VARCHAR(50) NOT NULL,
    PRIMARY KEY (tr_id),
    FOREIGN KEY (tr_id) REFERENCES Transaction(tr_id)
);

-- Enlist table
CREATE TABLE Enlist (
    u_id INT,
    admin_id INT,
    game_id INT,
    role ENUM('USER','ADMIN') NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES Admin(admin_id),
    FOREIGN KEY (game_id) REFERENCES Game(game_id),
    FOREIGN KEY (u_id) REFERENCES user(u_id)
);
INSERT INTO Game (name, description, rating, price, space) VALUES 
('Sky Legends', 'An open-world aerial combat simulator with customizable aircraft.', 4.75, 49.99, 45000),
('Dungeon Echoes', 'A dark fantasy RPG with rich lore and turn-based combat.', 4.32, 59.99, 65000),
('Pixel Farm', 'A relaxing farming simulator with pixel-style graphics and daily quests.', 3.89, 19.99, 15000),
('Cyber Strike', 'A fast-paced multiplayer FPS set in a futuristic cyberpunk world.', 4.61, 39.99, 52000),
('Tower Architect', 'Design and manage the world\'s tallest skyscrapers in this strategy sim.', 4.10, 24.99, 28000),
('Alien Botanist', 'Grow alien plants and study ecosystems on distant planets.', 3.75, 29.99, 32000),
('Robo Kart', 'High-octane racing with customizable robot drivers.', 4.28, 44.99, 39000),
('Mystic Trails', 'An exploration-based puzzle game through enchanted forests and ancient ruins.', 4.50, 34.99, 26000),
('Shadow Operative', 'Stealth-based action with spy gadgets and global missions.', 4.67, 54.99, 47000),
('Battle Chess 3000', 'Classic chess with futuristic pieces and combat animations.', 3.95, 14.99, 12000);

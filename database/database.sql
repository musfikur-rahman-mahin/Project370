
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
    coin INT NOT NULL DEFAULT 0,
    flag BOOLEAN DEFAULT FALSE
);

-- Game table
CREATE TABLE Game (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    rating DECIMAL(3,2) DEFAULT 0.00,
    price DECIMAL(10,2),
    space DECIMAL(10,2)
);

-- Thread table
CREATE TABLE Thread (
    thread_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    u_id INT,
    flag BOOLEAN DEFAULT FALSE,
    admin_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (admin_id) REFERENCES Admin(admin_id)
);

-- Thread Comments table
CREATE TABLE Thread_Comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT,
    comments TEXT NOT NULL,
    u_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (u_id) REFERENCES user(u_id),
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
    buy_id INT AUTO_INCREMENT PRIMARY KEY,
    u_id INT,
    game_id INT,
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
    user_rating INT,
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

CREATE TABLE Used_Game_Marketplace (
    market_id INT AUTO_INCREMENT PRIMARY KEY,
    u_id INT NOT NULL,                           
    game_name VARCHAR(100) NOT NULL,  
    price INT NOT NULL,     
    description TEXT,                 
    game_user_id VARCHAR(100),        
    game_password VARCHAR(100),       
    listed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('AVAILABLE','SOLD') DEFAULT 'AVAILABLE',
    admin_status ENUM('PENDING','APPROVED','DISAPPROVED') NOT NULL DEFAULT 'PENDING',
    admin_flag BOOLEAN DEFAULT FALSE,
    buyer_id INT DEFAULT NULL,        
    FOREIGN KEY (u_id) REFERENCES user(u_id),
    FOREIGN KEY (buyer_id) REFERENCES user(u_id)
);

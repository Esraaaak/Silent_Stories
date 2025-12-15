-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS silent_stories;
USE silent_stories;

-- ---------------------------------------------
-- 1. Users table: store registered users
-- ---------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique user ID
    name VARCHAR(100) NOT NULL,             -- User's name
    email VARCHAR(100) NOT NULL UNIQUE,     -- User's email (must be unique)
    phone VARCHAR(10) NOT NULL,             -- User's phone number
    password VARCHAR(255) NOT NULL,         -- Hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Registration timestamp
);

-- ---------------------------------------------
-- 2. Contact messages table: store messages from users
-- ---------------------------------------------
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique message ID
    name VARCHAR(100) NOT NULL,             -- Sender's name
    email VARCHAR(100) NOT NULL,            -- Sender's email
    message TEXT NOT NULL,                  -- Message content
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp
);

-- ---------------------------------------------
-- 3. Events table: store event details
-- ---------------------------------------------
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Event ID
    title VARCHAR(100) NOT NULL,            -- Event title
    event_date VARCHAR(100) NOT NULL,       -- Event date (as string)
    location VARCHAR(100),                   -- Event location
    description TEXT,                        -- Event description
    image_url VARCHAR(255)                    -- Image path or URL
);

-- Sample events insertion
INSERT INTO events (title, event_date, location, description, image_url) VALUES
('Drop-in Drawing', 'December 10, 2025 — City Art Center', 'City Art Center', 'Get creative with Ruth Asawa’s art in a fun, drop-in drawing session for all ages!', 'images/Drop-in Drawing.png'),
('Member Early Hour', 'Sat, Nov 8, 9:30–10:30 a.m.', 'Gallery 1', 'Start your morning with art — register for Member Early Hour!', 'images/ev2.png'),
('Into The Wild', 'Thu, Nov 13, 2:30–4:00 p.m. — MoMA, Floor 1', 'MoMA, Floor 1', 'Discover 45 stunning artworks celebrating wildlife!', 'images/ev3.png'),
('Internet Art', 'Sat, Nov 15, 1:00–3:00 p.m. — Gallery 5', 'Gallery 5', 'Explore how the online world inspires a new visual language!', 'images/ev4.png'),
('Threadwork', 'Sun, Nov 16, 2:00–4:00 p.m. — Gallery 2', 'Gallery 2', 'Discover how needlework becomes cutting-edge art!', 'images/ev5.png');

-- ---------------------------------------------
-- 4. Registrations table: store which user registered for which event
-- ---------------------------------------------
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Registration ID
    user_id INT NOT NULL,                    -- Reference to users table
    event_id INT NOT NULL,
    user_name VARCHAR(100),
    user_email VARCHAR(100),                   -- Reference to events table
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Registration timestamp
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY user_event_unique (user_id, event_id) -- Prevent duplicate registration
);

-- ---------------------------------------------
-- 5. Wishlist table: store which user added which event to wishlist
-- ---------------------------------------------
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Wishlist ID
    user_id INT NOT NULL,                    -- Reference to users table
    event_id INT NOT NULL,                   -- Reference to events table
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when added
    user_name VARCHAR(100),
    user_email VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY user_wishlist_unique (user_id, event_id) -- Prevent duplicate wishlist entries
);

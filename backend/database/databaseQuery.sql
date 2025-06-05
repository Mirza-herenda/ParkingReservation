CREATE DATABASE IF NOT EXISTS parkingreservation;
USE parkingreservation;

-- Table: users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    carModel VARCHAR(100),
    carColor VARCHAR(50),
    licencePlate VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL
);

-- Table: zones
CREATE TABLE zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ZoneName VARCHAR(100) NOT NULL,
    zoneCapacity INT NOT NULL,
    zoneNumber INT NOT NULL,
    zonePrice INT NOT NULL,
    location TEXT NOT NULL
);

-- Table: parkingspots
CREATE TABLE parkingspots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zona INT NOT NULL,
    status ENUM('available', 'reserved', 'occupied') NOT NULL,
    FOREIGN KEY (zona) REFERENCES zones(id) ON DELETE CASCADE
);

-- Table: parkingreservations
CREATE TABLE parkingreservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    parkingSpot_id INT NOT NULL,
    dateAndTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    zone INT NOT NULL,
    location INT NOT NULL,
    duration INT NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parkingSpot_id) REFERENCES parkingspots(id) ON DELETE CASCADE,
    FOREIGN KEY (zone) REFERENCES zones(id) ON DELETE CASCADE
);

-- Table: messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailAdress VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

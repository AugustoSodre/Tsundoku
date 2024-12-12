<?php

//DataBase Configurations for a local setting
$host = "localhost";
$dbname = "rpg_characters";
$username = "root";
$password = "";

//Connecting to the DB
try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("There was an error connecting to the DB: " + $e);
}

//Creating tables
$query = 
"CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS characters(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    race VARCHAR(50) NOT NULL,
    age int NOT NULL,
    class VARCHAR(50) NOT NULL,
    level int DEFAULT 1,
    background TEXT,
    campaign VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users (id)
);

CREATE TABLE IF NOT EXISTS attributes(
    attribute_id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    strength INT NOT NULL,
    dexterity INT NOT NULL,
    constitution INT NOT NULL,
    intelligence INT NOT NULL,
    wisdom INT NOT NULL,
    charisma INT NOT NULL,
    FOREIGN KEY(character_id) REFERENCES characters (id)
);";

try{
    $pdo->exec($query);
} catch (PDOException $e){
    echo "There was an error in the DB: " + $e;
}
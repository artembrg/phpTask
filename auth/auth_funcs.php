<?php

// Validating email
function validateEmail($email) {
    $pattern = '@[a-z0-9]+\@[a-z]+\.[a-z]+@';
    return preg_match($pattern, $email);
}

// Checking if login exists
function checkLoginExists($login) {
    // Connecting to db
    include_once '../db/db_connect.php';
    $db = connectToDB();

    // Forming and executing query
    $result = $db->prepare('SELECT * FROM `user` WHERE username = :login');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->execute();

    // Returning result
    $result->setFetchMode(PDO::FETCH_ASSOC);
    return $result->fetch();
}

// Validating password
function validatingPassword($login, $password) {
    // Connecting to db
    include_once '../db/db_connect.php';
    $db = connectToDB();

    // Hashing the password (db stores hashed password)
    $password = hash('sha256', $password);

    // Forming and executing query
    $result = $db->prepare('SELECT * FROM `user` WHERE username = :login AND password_hashed = :password');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->bindParam(':password', $password, PDO::PARAM_STR);
    $result->execute();

    // Returning result
    $result->setFetchMode(PDO::FETCH_ASSOC);
    return $result->fetch();
}

// Registration of the new user
function register($login, $password) {
    // Connecting to db
    include_once '../db/db_connect.php';
    $db = connectToDB();

    // Hashing the password
    $password = hash('sha256', $password);

    // Forming and executing query
    $result = $db->prepare('INSERT INTO `user` VALUES (:login, :password)');
    $result->bindParam(':login', $login, PDO::PARAM_STR);
    $result->bindParam(':password', $password, PDO::PARAM_STR);
    $result->execute();
}
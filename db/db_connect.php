<?php

// Returns PDO object
function connectToDB()
{
    // Getting the DB settings from config file
    include_once 'db_config.php';
    $params = getConfDB();

    // Forming PDO object
    $host_db = 'mysql:host='.$params['host'].';dbname='.$params['dbname'];
    return new PDO($host_db, $params['user'], $params['password']);
}
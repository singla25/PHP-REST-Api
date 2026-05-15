<?php

$host = "localhost";
$username = 'admin';
$password = 'redhat';
$database = "rest_api";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die(json_encode([
        'status' => false,
        'message' => 'Database Connection Failed'
    ]));
}
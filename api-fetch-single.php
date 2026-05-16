<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

/*
|--------------------------------------------------------------------------
| Get Raw JSON Data
|--------------------------------------------------------------------------
*/

$data = json_decode(
    file_get_contents("php://input"),
    true
);

/*
|--------------------------------------------------------------------------
| Validate Student ID
|--------------------------------------------------------------------------
*/

if (!isset($data['sid']) || empty($data['sid'])) {

    echo json_encode([
        'status'  => false,
        'message' => 'Student ID is Required'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Sanitize ID
|--------------------------------------------------------------------------
*/

$student_id = intval($data['sid']);

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/

include "config.php";

/*
|--------------------------------------------------------------------------
| SQL Query
|--------------------------------------------------------------------------
*/

$sql = "SELECT * FROM user WHERE id = '$student_id'";

$result = mysqli_query($conn, $sql);

/*
|--------------------------------------------------------------------------
| Query Failed
|--------------------------------------------------------------------------
*/

if (!$result) {

    echo json_encode([
        'status'  => false,
        'message' => 'Database Query Failed'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Record Found
|--------------------------------------------------------------------------
*/

if (mysqli_num_rows($result) > 0) {

    $student = mysqli_fetch_assoc($result);

    echo json_encode([
        'status'  => true,
        'message' => 'Student Found',
        'data'    => $student
    ], JSON_PRETTY_PRINT);

} else {

    echo json_encode([
        'status'  => false,
        'message' => 'No Record Found'
    ]);
}

/*
|--------------------------------------------------------------------------
| Close Connection
|--------------------------------------------------------------------------
*/

mysqli_close($conn);
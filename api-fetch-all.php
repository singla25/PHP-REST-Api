<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

/*
|--------------------------------------------------------------------------
| Include Database
|--------------------------------------------------------------------------
*/

include "config.php";

/*
|--------------------------------------------------------------------------
| SQL Query
|--------------------------------------------------------------------------
*/

$sql = "SELECT * FROM user ORDER BY id DESC";

/*
|--------------------------------------------------------------------------
| Execute Query
|--------------------------------------------------------------------------
*/

$result = mysqli_query($conn, $sql);

/*
|--------------------------------------------------------------------------
| Query Failed
|--------------------------------------------------------------------------
*/

if (!$result) {

    echo json_encode([
        'status'  => false,
        'message' => 'Database Query Failed',
        'error'   => mysqli_error($conn)
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Records Found
|--------------------------------------------------------------------------
*/

if (mysqli_num_rows($result) > 0) {

    $students = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $students[] = $row;
    }

    echo json_encode([
        'status'  => true,
        'message' => 'Students Fetched Successfully',
        'total'   => count($students),
        'data'    => $students
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
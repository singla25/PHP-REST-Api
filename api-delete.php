<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

/*
|--------------------------------------------------------------------------
| Include Database
|--------------------------------------------------------------------------
*/

include "config.php";

/*
|--------------------------------------------------------------------------
| Get JSON Data
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

if (empty($data['sid'])) {

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
| Delete Query
|--------------------------------------------------------------------------
*/

$sql = "DELETE FROM user WHERE id = '$student_id'";

/*
|--------------------------------------------------------------------------
| Execute Query
|--------------------------------------------------------------------------
*/

$result = mysqli_query($conn, $sql);

/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/

if ($result) {

    if (mysqli_affected_rows($conn) > 0) {

        echo json_encode([
            'status'  => true,
            'message' => 'Student Record Deleted Successfully'
        ]);

    } else {

        echo json_encode([
            'status'  => false,
            'message' => 'Student ID Not Found'
        ]);
    }

} else {

    echo json_encode([
        'status'  => false,
        'message' => mysqli_error($conn)
    ]);
}

/*
|--------------------------------------------------------------------------
| Close Connection
|--------------------------------------------------------------------------
*/

mysqli_close($conn);
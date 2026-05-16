<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

/*
|--------------------------------------------------------------------------
| Include Database First
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
| Validate Data
|--------------------------------------------------------------------------
*/

if (
    empty($data['sname']) ||
    empty($data['semail']) ||
    empty($data['sgender']) ||
    empty($data['sdob']) ||
    empty($data['ssalary'])
) {

    echo json_encode([
        'status'  => false,
        'message' => 'All Fields Are Required'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Sanitize Data
|--------------------------------------------------------------------------
*/

$student_name   = mysqli_real_escape_string($conn, $data['sname']);
$student_email  = mysqli_real_escape_string($conn, $data['semail']);
$student_gender = mysqli_real_escape_string($conn, $data['sgender']);
$student_dob    = mysqli_real_escape_string($conn, $data['sdob']);
$student_salary = floatval($data['ssalary']);

/*
|--------------------------------------------------------------------------
| Insert Query
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO user
        (
            name,
            email,
            gender,
            date_of_birth,
            salary
        )

        VALUES
        (
            '$student_name',
            '$student_email',
            '$student_gender',
            '$student_dob',
            '$student_salary'
        )";

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

    echo json_encode([
        'status'  => true,
        'message' => 'Student Record Inserted Successfully'
    ]);

} else {

    echo json_encode([
        'status'  => false,
        'message' => mysqli_error($conn)
    ]);
}
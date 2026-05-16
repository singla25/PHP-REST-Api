<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
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
| Validate ID
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
| Sanitize Data
|--------------------------------------------------------------------------
*/

$student_id     = intval($data['sid']);

$student_name   = mysqli_real_escape_string($conn, $data['sname']);
$student_email  = mysqli_real_escape_string($conn, $data['semail']);
$student_gender = mysqli_real_escape_string($conn, $data['sgender']);
$student_dob    = mysqli_real_escape_string($conn, $data['sdob']);
$student_salary = floatval($data['ssalary']);

/*
|--------------------------------------------------------------------------
| Update Query
|--------------------------------------------------------------------------
*/

$sql = "UPDATE user

        SET

            name = '$student_name',
            email = '$student_email',
            gender = '$student_gender',
            date_of_birth = '$student_dob',
            salary = '$student_salary'

        WHERE id = '$student_id'";

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
        'message' => 'Student Record Updated Successfully'
    ]);

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
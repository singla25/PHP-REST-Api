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
| SEARCH USING GET METHOD
|--------------------------------------------------------------------------
|
| Example:
| http://localhost83.local.com/RestApi/api-search.php?s=shu
|
*/

if (isset($_GET['s']) && !empty($_GET['s'])) {

    $search_value = mysqli_real_escape_string(
        $conn,
        $_GET['s']
    );
}

/*
|--------------------------------------------------------------------------
| SEARCH USING POST METHOD
|--------------------------------------------------------------------------
|
| Uncomment below code if using POST request
|
| Example JSON:
|
| {
|     "s": "shu"
| }
|
*/

/*

$data = json_decode(
    file_get_contents("php://input"),
    true
);

if (!isset($data['s']) || empty($data['s'])) {

    echo json_encode([
        'status'  => false,
        'message' => 'Search Value Required'
    ]);

    exit;
}

$search_value = mysqli_real_escape_string(
    $conn,
    $data['s']
);

*/

/*
|--------------------------------------------------------------------------
| Search Query
|--------------------------------------------------------------------------
*/

$sql = "SELECT * FROM user
        WHERE name LIKE '%$search_value%'
        ORDER BY id DESC";

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
        'message' => mysqli_error($conn)
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
        'message' => 'Students Found',
        'total'   => count($students),
        'data'    => $students
    ], JSON_PRETTY_PRINT);

} else {

    echo json_encode([
        'status'  => false,
        'message' => 'No Student Found'
    ]);
}

/*
|--------------------------------------------------------------------------
| Close Connection
|--------------------------------------------------------------------------
*/

mysqli_close($conn);
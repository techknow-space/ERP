<?php
$location_id_s1 = 'acc39f12-68bb-4c9a-8791-d52ab49fcd12';
$location_id_to = 'eafd1e4c-898d-432e-bdca-e2639e8887a7';
$manufacturer_id = 'da94a413-dd74-4a79-9f96-c3f3c8f6c523';
$brand_id = '387363f3-6a62-47e4-b3b2-ecd26845e169';

$mid_6 = '26a2aa03-9aba-4f8a-a6c4-760cb9591cca';
$mid_6p = 'b229c140-4505-4734-b894-76b2b39413b8';
$mid_6s = '6fbd9d65-9498-4e0e-a50c-c35992421be5';
$mid_6sp = '64f4830c-6eba-4428-a564-1d0473ed8b44';

set_time_limit(0);
ini_set('max_execution_time', 0);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tts-erp-01";

$conn = new mysqli($servername, $username);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sl_db_q = "USE ttserp01";

if (mysqli_query($conn, $sl_db_q)) {
    echo "Database Selected Successfully";
} else {
    echo "Error: " . $sl_db_q . "<br>" . mysqli_error($conn);
}



$q_insert_location = "INSERT INTO locations (id,location,location_code) VALUES ('$location_id_s1','Square One Mississuage','S1') , ('$location_id_to','Front Street Toronto','TO1')";
if (mysqli_query($conn, $q_insert_location)) {
    echo "Locations created successfully";
} else {
    echo "Error: " . $q_insert_location . "<br>" . mysqli_error($conn);
}

$q_insert_manufacturer = "INSERT INTO manufacturers (id,manufacturer) VALUES ('$manufacturer_id','Apple') ";
if (mysqli_query($conn, $q_insert_manufacturer)) {
    echo "Manufacturer created successfully";
} else {
    echo "Error: " . $q_insert_manufacturer . "<br>" . mysqli_error($conn);
}

$q_insert_brand = "INSERT INTO brands (id,name,manufacturer_id) VALUES ('$brand_id','iPhone','$manufacturer_id') ";;
if (mysqli_query($conn, $q_insert_brand)) {
    echo "Brand created successfully";
} else {
    echo "Error: " . $q_insert_brand . "<br>" . mysqli_error($conn);
}

$q_insert_device = "INSERT INTO devices (id,model_name,model_number,brand_id) VALUES ('$mid_6','6','A1549,A1586,A1589','387363f3-6a62-47e4-b3b2-ecd26845e169'), ('$mid_6p','6 Plus','A1522,A1524,A1593','387363f3-6a62-47e4-b3b2-ecd26845e169'),('$mid_6s','6S','A1633,A1688,A1691,A1700','387363f3-6a62-47e4-b3b2-ecd26845e169'),('$mid_6sp','6S Plus','A1634,A1687,A1690,A1699','387363f3-6a62-47e4-b3b2-ecd26845e169') ";
if (mysqli_query($conn, $q_insert_device)) {
    echo "Device created successfully";
} else {
    echo "Error: " . $q_insert_device . "<br>" . mysqli_error($conn);
}

include 'convert_to_json.php';

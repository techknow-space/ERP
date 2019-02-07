<?php

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



function generate_uuid4() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function get_device_id($model_name)
{
    $mid_6 = '26a2aa03-9aba-4f8a-a6c4-760cb9591cca';
    $mid_6p = 'b229c140-4505-4734-b894-76b2b39413b8';
    $mid_6s = '6fbd9d65-9498-4e0e-a50c-c35992421be5';
    $mid_6sp = '64f4830c-6eba-4428-a564-1d0473ed8b44';


    switch (trim($model_name)){
        case "6":
            return $mid_6;
            break;
        case "6 Plus":
            return $mid_6p;
            break;
        case "6S":
            return $mid_6s;
            break;
        case "6S Plus":
            return $mid_6sp;
            break;
    }
}


$location_id_s1 = 'acc39f12-68bb-4c9a-8791-d52ab49fcd12';
$location_id_to = 'eafd1e4c-898d-432e-bdca-e2639e8887a7';
$manufacturer_id = 'da94a413-dd74-4a79-9f96-c3f3c8f6c523';
$brand_id = '387363f3-6a62-47e4-b3b2-ecd26845e169';

$mid_6 = '26a2aa03-9aba-4f8a-a6c4-760cb9591cca';
$mid_6p = 'b229c140-4505-4734-b894-76b2b39413b8';
$mid_6s = '6fbd9d65-9498-4e0e-a50c-c35992421be5';
$mid_6sp = '64f4830c-6eba-4428-a564-1d0473ed8b44';





$fileHandle = fopen("iphone_import_sample.csv", "r");
echo "<pre>";
while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
    //Dump out the row for the sake of clarity.
    $all_data[] = [
        'manufacturer' => $row[0],
        'brand' => $row[1],
        'model_name' => $row[2],
        'model_number' => $row[3],
        'part_name' => $row[4],
        'price' => $row[5],
        'qty_to' => $row[6],
        'qty_s1' => $row[7],
        'sold_to' => $row[8],
        'sold_s1' => $row[9],
        'cost' => $row[10],
        'first_received' => $row[11],
        'sku' => $row[12]
    ];

}

$master = [];

foreach ($all_data as $row){

    $part_id = generate_uuid4();

    $part[] = [
        'id' => $part_id,
        'sku' => $row['sku'],
        'name' => $row['part_name'],
        'device_id' => get_device_id($row['model_name'])
    ];

    $part_price[] = [
        'id' => generate_uuid4(),
        'part_id'=> $part_id,
        'last_cost' => $row['cost'],
        'selling_price' => $row['price']
    ];

    $part_stock[] = [
        'id' => generate_uuid4(),
        'part_id' => $part_id,
        'stock_qty' => $row['qty_to'],
        'sold_all_time' => $row['sold_to'],
        'location_id' => $location_id_to
    ];

    $part_stock[] = [
        'id' => generate_uuid4(),
        'part_id' => $part_id,
        'stock_qty' => $row['qty_s1'],
        'sold_all_time' => $row['sold_s1'],
        'location_id' => $location_id_s1
    ];


}

foreach ($part as $pt){

    $id = $pt['id'];
    $sku = $pt['sku'];
    $name = $pt['name'];
    $device_id = $pt['device_id'];

    $q_insert_location = "INSERT INTO parts (id,sku,part_name,device_id) VALUES ('$id','$sku','$name','$device_id')";
    if (mysqli_query($conn, $q_insert_location)) {
        echo "Part created successfully";
    } else {
        echo "Error: " . $q_insert_location . "<br>" . mysqli_error($conn);
    }
    echo "/n";
}

foreach ($part_price as $ptp){
    $id = $ptp['id'];
    $part_id = $ptp['part_id'];
    $last_cost = $ptp['last_cost'];
    $selling_price = $ptp['selling_price'];

    $q_insert_location = "INSERT INTO part_prices (id,part_id,last_cost,selling_price_b2c) VALUES ('$id','$part_id','$last_cost','$selling_price')";
    if (mysqli_query($conn, $q_insert_location)) {
        echo "Price created successfully";
    } else {
        echo "Error: " . $q_insert_location . "<br>" . mysqli_error($conn);
    }
    echo "/n";
}

foreach ($part_stock as $ptp){
    $id = $ptp['id'];
    $part_id = $ptp['part_id'];
    $stock_qty= $ptp['stock_qty'];
    $stock_all_time = $ptp['sold_all_time'];
    $location_id = $ptp['location_id'];

    $q_insert_location = "INSERT INTO part_stocks (id,part_id,stock_qty,location_id,sold_all_time) VALUES ('$id','$part_id','$stock_qty','$location_id','$stock_all_time')";
    if (mysqli_query($conn, $q_insert_location)) {
        echo "Stock created successfully";
    } else {
        echo "Error: " . $q_insert_location . "<br>" . mysqli_error($conn);
    }
    echo "/n";
}



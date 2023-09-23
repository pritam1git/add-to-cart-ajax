<?php
include_once 'db.php';

if (isset($_GET['download']) && $_GET['download'] === 'csv') {
    
    // Set headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="example.csv"'); // Change the filename as needed
    // Create a file handle
    $output = fopen('php://output', 'w');

    // Output CSV header
    $csvHeader = array( 'Name', 'Email', 'Phone');
    fputcsv($output, $csvHeader);

    // Fetch data from your database and write it to the CSV
    $query = "SELECT * FROM orders WHERE id"; // Replace with your table name
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)){
        $csvData = array(
            $row['name'],
            $row['email'],
            $row['phone'],
        );
        fputcsv($output, $csvData);
    }

    // Close the file handle
    fclose($output);

    exit; // Terminate the script
}
if (isset($_GET['download']) && $_GET['download'] === 'xml') {
    
    // Set headers to force download
    header('Content-Type: text/xml');
    header('Content-Disposition: attachment; filename="example.xml"'); // Change the filename as needed
    // Create a file handle
    $output = fopen('php://output', 'w');

    // Output CSV header
    $csvHeader = array( 'Name', 'Email', 'Phone');
    fputcsv($output, $csvHeader);

    // Fetch data from your database and write it to the CSV
    $query = "SELECT * FROM orders WHERE id"; // Replace with your table name
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)){
        $csvData = array(
            $row['name'],
            $row['email'],
            $row['phone'],
        );
        fputcsv($output, $csvData);
    }

    // Close the file handle
    fclose($output);

    exit; // Terminate the script
}
if (isset($_GET['download']) && $_GET['download'] === 'json') {
    
    // Set headers to force download
    header('Content-Type: text/json');
    header('Content-Disposition: attachment; filename="example.json"'); // Change the filename as needed
    // Create a file handle
    $output = fopen('php://output', 'w');

    // Output CSV header
    $csvHeader = array( 'Name', 'Email', 'Phone');
    fputcsv($output, $csvHeader);

    // Fetch data from your database and write it to the CSV
    $query = "SELECT * FROM orders WHERE id"; // Replace with your table name
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)){
        $csvData = array(
            $row['name'],
            $row['email'],
            $row['phone'],
        );
        fputcsv($output, $csvData);
    }

    // Close the file handle
    fclose($output);

    exit; // Terminate the script
}
?>

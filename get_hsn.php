<?php 
session_start();
require "./config/database.php";

$product_name = $_GET['product_name'];
$sql = "SELECT hsn_code FROM product_hsn_mapping WHERE product_name = ?";
$stmt = $pdo->prepare($sql);

// Execute the statement
$stmt->execute([$product_name]);

// Fetch the HSN code
$hsn_code = $stmt->fetchColumn();

// Check if HSN code was found
if ($hsn_code !== false) {
    header('Content-Type: application/json');
    $response = [];
    $response['hsn_code'] = $hsn_code; // Directly assign the fetched hsn_code
    echo json_encode($response);
} else {
    echo json_encode(["error" => "No HSN Code found for the product: " . htmlspecialchars($product_name)]);
}
?>

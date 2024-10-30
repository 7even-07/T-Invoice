<?php
session_start();
require "./config/database.php";

$input = json_decode(file_get_contents('php://input'), true);
$product_name = $input['product_name'];

$stmt = $pdo->prepare("SELECT gst_rate FROM product_hsn_mapping WHERE product_name = ?");
$stmt->execute([$product_name]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['gst_rate' => $data['gst_rate'] ?? null]);
?>

<?php 
session_start();
require "../config/database.php";
require "../fpdf/fpdf.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: invoice_view.php");
    exit();
}

$invoice_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tax_invoice WHERE id = ? AND user_id = ?");
$stmt->execute([$invoice_id, $_SESSION['user_id']]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    header("Location: invoice_view.php");
    exit();
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Styling Constants
$pdf->SetMargins(10, 10, 10);
$border = 1;
$lineHeight = 8;

// Company Header
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Ajay Infotech', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Office No.004, Anubhav Shanti Nagar, C-14 / Sector-5, Near Shivsena Sakha, Mira Road-(East)', 0, 1, 'C');
$pdf->Cell(0, 6, 'UDYAM Reg No.: UDYAM-MH-18-0000303 (Micro), GSTIN/UIN: 27ASEPS9111L1ZB, State: Maharashtra (27)', 0, 1, 'C');
$pdf->Cell(0, 6, 'E-Mail: ajayinfotech20@gmail.com', 0, 1, 'C');
$pdf->Ln(10);

// Invoice Title
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'TAX INVOICE', 0, 1, 'C');
$pdf->Ln(5);

// Invoice Info
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $lineHeight, 'Invoice No:', 0, 0);
$pdf->Cell(60, $lineHeight, 'GST/0069/23-24', 0, 1);
$pdf->Cell(30, $lineHeight, 'Date:', 0, 0);
$pdf->Cell(60, $lineHeight, '12-Jun-23', 0, 1);
$pdf->Ln(5);

// Buyer and Consignee
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, 'Consignee (Ship to)', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, "GoEzylift Tech Pvt Ltd.\nUnit no.2 Janki Industrial Estate,\nNear Arrow Engineers,\nVasai East-401208\nGSTIN/UIN: 27AAHCG7706R1ZV, State: Maharashtra (27)", 0, 'L');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $lineHeight, 'Sl No', $border, 0, 'C');
$pdf->Cell(80, $lineHeight, 'Description of Services', $border, 0, 'C');
$pdf->Cell(30, $lineHeight, 'HSN/SAC', $border, 0, 'C');
$pdf->Cell(20, $lineHeight, 'Rate', $border, 0, 'R');
$pdf->Cell(20, $lineHeight, 'Quantity', $border, 0, 'R');
$pdf->Cell(30, $lineHeight, 'Amount', $border, 1, 'R');

// Sample Data for Invoice Items (This should be dynamically populated)
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, $lineHeight, '1', $border, 0, 'C');
$pdf->Cell(80, $lineHeight, 'Implementation Chg.', $border, 0, 'L');
$pdf->Cell(30, $lineHeight, '998313', $border, 0, 'C');
$pdf->Cell(20, $lineHeight, number_format(4000, 2), $border, 0, 'R');
$pdf->Cell(20, $lineHeight, number_format(1, 2), $border, 0, 'R');
$pdf->Cell(30, $lineHeight, number_format(4000, 2), $border, 1, 'R');

// Tax Details
$pdf->Cell(140, $lineHeight, 'CGST @9%', $border, 0, 'L');
$pdf->Cell(30, $lineHeight, number_format(360, 2), $border, 1, 'R');

$pdf->Cell(140, $lineHeight, 'SGST @9%', $border, 0, 'L');
$pdf->Cell(30, $lineHeight, number_format(360, 2), $border, 1, 'R');

// Total
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(140, $lineHeight, 'Total', $border, 0, 'L');
$pdf->Cell(30, $lineHeight, number_format(4720, 2), $border, 1, 'R');

$pdf->Ln(10);

// Amount in Words
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Amount Chargeable (in words): Indian Rupees Four Thousand Seven Hundred Twenty Only', 0, 1);
$pdf->Ln(5);

// Declaration Section
$pdf->SetFont('Arial', 'I', 8);
$pdf->MultiCell(0, 6, "Declaration: \nDeclaration of Non-Deduction of TDS (For TALLY LICENSE PRODUCT ONLY): We hereby confirm that software supplied vide this invoice is acquired in a subsequent transfer without any modification...", 0, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'This is a Computer Generated Invoice', 0, 1, 'C');

// Output PDF
$pdf->Output();
?>

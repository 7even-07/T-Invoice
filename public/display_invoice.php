<?php 
session_start();
require "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is set in the URL
if (!isset($_GET['id'])) {
    header("Location: invoice_view.php");
    exit();
}

// Fetch the invoice details based on the ID
$invoice_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tax_invoice WHERE id = ? AND user_id = ?");
$stmt->execute([$invoice_id, $_SESSION['user_id']]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if invoice exists
if (!$invoice) {
    header("Location: invoice_view.php");
    exit();
}

// Fetch the items associated with this invoice
$itemStmt = $pdo->prepare("SELECT * FROM tax_invoice WHERE id = ?");
$itemStmt->execute([$invoice_id]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice Details</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/invoice.css" />
</head>
<body>
    <section class="wrapper-invoice">
        <div class="invoice">
            <div class="invoice-information">
                <p><strong>Invoice #</strong>: <?= htmlspecialchars($invoice['id']); ?></p>
                <p><strong>Created Date</strong>: <?= htmlspecialchars(date('Y-m-d', strtotime($invoice['date']))); ?></p>
            </div>
            <div class="invoice-logo-brand">
                <img src="../ai.jfif" alt="Brand Logo" />
            </div>
            <div class="invoice-head">
                <div class="head client-info">
                    <p><strong>Company Name</strong>: <?= htmlspecialchars($invoice['company_name']); ?></p>
                    <p><strong>Address</strong>: <?= htmlspecialchars($invoice['address']); ?></p>
                    <p><strong>State</strong>: <?= htmlspecialchars($invoice['state']); ?></p>
                </div>
                <div class="head client-data">
                    <p><strong>Client Name</strong>: <?= htmlspecialchars($invoice['name']); ?></p>
                    <p><strong>Client Address</strong>: <?= htmlspecialchars($invoice['address']); ?></p>
                    <p><strong>Client State</strong>: <?= htmlspecialchars($invoice['state']); ?></p>
                </div>
            </div>
            <div class="invoice-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>HSN Code</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']); ?></td>
                            <td><?= htmlspecialchars($item['hsn_code']); ?></td>
                            <td><?= htmlspecialchars($item['cgst']); ?></td>
                            <td><?= htmlspecialchars($item['sgst']); ?></td>
                            <td><?= htmlspecialchars($item['igst']); ?></td>
                            <td><?= htmlspecialchars($item['total']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="invoice-total-amount">
                    <p>Total: Rp. <?= htmlspecialchars($invoice['total']); ?></p>
                </div>
            </div>
            <div class="invoice-footer">
                <p>Thank you, Happy Tally!</p>
            </div>
        </div>
    </section>

    <div class="invoice-actions">
        <a href="invoice_view.php">Back to Invoices</a>
        <a href="download_invoice.php?id=<?= $invoice['id']; ?>">Click here to download Invoice</a>
    </div>
</body>
</html>

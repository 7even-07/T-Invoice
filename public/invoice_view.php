<?php 
session_start();
require "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch invoices for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tax_invoice WHERE user_id = ?");
$stmt->execute([$user_id]);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tax Invoices</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <?php include "../linkcss.php" ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Your Tax Invoices</h1>


    <form action="./process_invoices.php" method="POST">
        <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-primary mr-2" href="../public/invoice_form.php">
            <i class="fa-regular fa-square-plus"></i> Create Invoice
        </a>
            <button type="submit" class="btn btn-warning mr-2" name="edit">Edit Selected</button>
            <button type="submit" class="btn btn-danger" name="delete" onclick="return confirmDelete();">Delete Selected</button>
        </div>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Select</th>
                    <th>Invoice ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Address</th>
                    <th>Company Name</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($invoices): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_invoices[]" value="<?= htmlspecialchars($invoice['id']); ?>">
                            </td>
                            <td><?= htmlspecialchars($invoice['id']); ?></td>
                            <td><?= htmlspecialchars($invoice['name']); ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($invoice['date']))); ?></td>
                            <td><?= htmlspecialchars($invoice['address']); ?></td>
                            <td><?= htmlspecialchars($invoice['company_name']); ?></td>
                            <td><?= htmlspecialchars($invoice['product_name']); ?></td>
                            <td><?= htmlspecialchars($invoice['price']); ?></td>
                            <td><?= htmlspecialchars($invoice['quantity']); ?></td>
                            <td><?= htmlspecialchars($invoice['cgst']); ?></td>
                            <td><?= htmlspecialchars($invoice['sgst']); ?></td>
                            <td><?= htmlspecialchars($invoice['igst']); ?></td>
                            <td><?= htmlspecialchars($invoice['total']); ?></td>
                            <td>
                                <a class="btn btn-info" href="../public/display_invoice.php?id=<?= $invoice['id']; ?>">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="14" class="text-center">No Invoice Found</td>
                    </tr>
                <?php endif; ?>    
            </tbody>
        </table>
    </form>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include "../linkscript.php" ?>

<script>
    function confirmDelete() {
        return confirm("Are You Sure, You Want To Delete The Selected Invoices ?");
    }
</script>

</body>
</html>

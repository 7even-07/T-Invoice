<?php 
session_start();
require "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the invoice ID from the query string
if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Fetch the invoice details
    $stmt = $pdo->prepare("SELECT * FROM tax_invoice WHERE id = ? AND user_id = ?");
    $stmt->execute([$invoice_id, $_SESSION['user_id']]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the invoice exists
    if (!$invoice) {
        echo "Invoice not found.";
        exit();
    }
} else {
    echo "No invoice ID provided.";
    exit();
}

// Handle form submission for updating the invoice
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $gst_rate = $_POST['gst_rate']; // Get the GST rate input

    // Calculate CGST and SGST
    $cgst = $gst_rate / 2;
    $sgst = $gst_rate / 2;

    // Fetch HSN code based on product name
    $hsn_stmt = $pdo->prepare("SELECT hsn_code FROM product_hsn_mapping WHERE product_name = ?");
    $hsn_stmt->execute([$product_name]);
    $hsn_code = $hsn_stmt->fetchColumn();

    // Calculate total based on price, quantity, and GST
    $total = ($price * $quantity) + $cgst + $sgst; // Ensure total calculation is correct
    $date = $_POST['date'];

    // Update the invoice details in the database
    $stmt = $pdo->prepare("UPDATE tax_invoice SET name = ?, company_name = ?, address = ?, state = ?, product_name = ?, price = ?, quantity = ?, gst_rate = ?, cgst = ?, sgst = ?, total = ?, date = ?, hsn_code = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$name, $company_name, $address, $state, $product_name, $price, $quantity, $gst_rate, $cgst, $sgst, $total, $date, $hsn_code, $invoice_id, $_SESSION['user_id']]);

    // Redirect to invoices list or display a success message
    header("Location: invoice_view.php?message=Invoice Update Successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <?php include "../linkcss.php" ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Invoice</h1>
        <form action="edit_invoice.php?id=<?= $invoice_id ?>" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($invoice['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($invoice['date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" name="address" required><?= htmlspecialchars($invoice['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="text" class="form-control" name="company_name" value="<?= htmlspecialchars($invoice['company_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_name">Product:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($invoice['product_name']); ?>" oninput="fetchHSN()" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($invoice['price']); ?>" oninput="calculateTotal()" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($invoice['quantity']); ?>" oninput="calculateTotal()" required>
            </div>
            <div class="form-group">
                <label for="hsn_code">HSN Code</label>
                <input type="text" class="form-control" id="hsn_code" name="hsn_code" value="<?= isset($invoice['hsn_code']) ? htmlspecialchars($invoice['hsn_code']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="cgst">CGST:</label>
                <input type="number" class="form-control" id="cgst" name="cgst" step="0.01" value="<?= htmlspecialchars($invoice['cgst']); ?>" oninput="calculateTotal()" readonly>
            </div>
            <div class="form-group">
                <label for="sgst">SGST:</label>
                <input type="number" class="form-control" id="sgst" name="sgst" step="0.01" value="<?= htmlspecialchars($invoice['sgst']); ?>" oninput="calculateTotal()" readonly>
            </div>
            <div class="form-group">
                <label for="igst">IGST:</label>
                <input type="number" class="form-control" id="igst" name="igst" step="0.01" value="<?= htmlspecialchars($invoice['igst']); ?>" oninput="calculateTotal()" readonly>
            </div>
            <div class="form-group">
                <label for="total">Total:</label>
                <input type="number" class="form-control" id="total" name="total" value="<?= htmlspecialchars($invoice['total']); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-success">Update Invoice</button>
            <a href="invoice_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include "../linkscript.php" ?>
    <script>
// Fetch HSN code based on product name input
function fetchHSN() {
    const productName = document.getElementById('product_name').value;

    if (productName) {
        fetch(`../get_hsn.php?product_name=${encodeURIComponent(productName)}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('hsn_code').value = data.hsn_code || ''; // Clear if not found
            })
            .catch(error => console.error('Error fetching HSN:', error));
    } else {
        document.getElementById('hsn_code').value = ''; // Clear if input is empty
    }
}

// GST rate for CGST and SGST
document.getElementById('product_name').addEventListener('input', function() {
    const productName = this.value;

    if (productName) {
        fetch('../get_gstrate.php', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_name: productName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.gst_rate) {
                const gstRate = parseFloat(data.gst_rate);
                const cgst = gstRate / 2;
                const sgst = gstRate / 2;

                document.getElementById('cgst').value = cgst.toFixed(2);
                document.getElementById('sgst').value = sgst.toFixed(2);
                calculateTotal();  // Update total dynamically
            } else {
                document.getElementById('cgst').value = '';
                document.getElementById('sgst').value = '';
            }
        })
        .catch(error => console.error("Error fetching GST rate: ", error));
    } else {
        document.getElementById('cgst').value = '';
        document.getElementById('sgst').value = '';
    }
});


function calculateTotal() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const quantity = parseFloat(document.getElementById('quantity').value) || 1;
    const gstRate = parseFloat(document.getElementById('cgst').value) * 2 || 0;  // CGST + SGST = GST Rate

    const gstAmount = (price * quantity * gstRate) / 100;
    const total = (price * quantity) + gstAmount;

    document.getElementById('total').value = total.toFixed(2);
}
    </script>

</body>
</html>

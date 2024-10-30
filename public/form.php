<?php
session_start();
require "../config/database.php";


// redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $date = $_POST['date'];
    $gstin = $_POST['gstin'];
    $total = $_POST['total']; // Total calculated in JavaScript

    // Arrays for multiple products
    $product_names = $_POST['product_name'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];
    $cgsts = $_POST['cgst'];
    $sgsts = $_POST['sgst'];
    $igsts = $_POST['igst'];
    $item_totals = $_POST['item_total'];

    // Loop through each product to fetch HSN code and store details
    foreach ($product_names as $index => $product_name) {
        // Fetch HSN code based on the product name
        $hsn_stmt = $pdo->prepare("SELECT hsn_code FROM product_hsn_mapping WHERE product_name = ?");
        $hsn_stmt->execute([$product_name]);
        $hsn_code = $hsn_stmt->fetchColumn();

        if ($hsn_code === false) {
            echo "HSN code not found for the product: " . htmlspecialchars($product_name);
            exit();
        }

        $price = $prices[$index];
        $quantity = $quantities[$index];
        $cgst = $cgsts[$index];
        $sgst = $sgsts[$index];
        $igst = $igsts[$index];
        $item_total = $item_totals[$index];

        // Insert each product's details into tax_invoice table
        $stmt = $pdo->prepare("INSERT INTO tax_invoice (user_id, name, company_name, address, state, product_name, hsn_code, price, quantity, cgst, sgst, igst, total, date, gstin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $company_name, $address, $state, $product_name, $hsn_code, $price, $quantity, $cgst, $sgst, $igst, $item_total, $date, $gstin]);
    }

    header("Location: invoice_view.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Create Invoice</h1>

    <form method="POST" action="../public/form.php" id="invoiceForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="Enter name" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" required />
        </div>

        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" name="company_name" placeholder="Enter company name" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" placeholder="Enter address" required></textarea>
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" placeholder="Enter state" required>
        </div>

        <div class="form-group">
            <label for="gstin">GSTIN</label>
            <input type="text" name="gstin" placeholder="Enter GSTIN" required>
        </div>

        <!-- Products Table -->
        <table class="table table-bordered" id="invoice_table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>HSN Code</th>
                    <th>GST Rate</th>
                    <th>CGST (%)</th>
                    <th>SGST (%)</th>
                    <th>IGST (%)</th>
                    <th>Total</th>
                    <th><button type="button" onClick="addItem()" class="btn btn-success btn-xs">Add +</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="form-control product-name" name="product_name[]" placeholder="Enter product name" oninput="fetchHSN(this)" required></td>
                    <td><input type="number" class="form-control calculate quantity" name="quantity[]" placeholder="Quantity" oninput="calculateTotal()" required></td>
                    <td><input type="number" class="form-control calculate price" name="price[]" placeholder="Price" oninput="calculateTotal()" required></td>
                    <td><input type="text" class="hsn-code" name="hsn_code[]" placeholder="HSN Code" readonly/>
                    </td>
                    <td><input type="number" class="gst-rate" name="gst_rate[]" placeholder="GST Rate" step="0.01" required></td>
                    <td><input type="number" class="cgst" name="cgst[]" placeholder="CGST Rate" step="0.01" oninput="calculateTotal()" required />
                    </td>
                    <td><input type="number" class="sgst" name="sgst[]" placeholder="SGST Rate" step="0.01" oninput="calculateTotal()" required />
                    </td>
                    <td><input type="number" class="form-control calculate igst" name="igst[]" placeholder="IGST" step="0.01"></td>
                    <td><input type="text" class="form-control item-total" name="item_total[]" placeholder="Total" readonly></td>
                    <td><button type="button" onClick="removeRow(this)" class="btn btn-danger btn-xs">Remove</button></td>
                </tr>
            </tbody>
        </table>

        <div class="form-group text-right">
            <label for="total">Total Amount</label>
            <input type="text" id="total" name="total" placeholder="Total Amount" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
// Fetch HSN code and GST rate based on product name input
function fetchHSN(input) {
    const productName = input.value;
    const row = input.closest('tr'); // Get the closest <tr> for dynamic updates

    if (productName) {
        // Fetch HSN code
        fetch(`../get_hsn.php?product_name=${encodeURIComponent(productName)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Check if HSN code is returned and set it
                if (data.hsn_code) {
                    row.querySelector('.hsn-code').value = data.hsn_code; // Set HSN code
                } else {
                    row.querySelector('.hsn-code').value = ''; // Clear if not found
                }

                // Now fetch GST rate based on product name
                return fetch('../get_gstrate.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ product_name: productName })
                });
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Check if GST rate is returned and set CGST and SGST
                if (data.gst_rate) {
                    const gstRate = parseFloat(data.gst_rate);
                    const cgst = gstRate / 2;
                    const sgst = gstRate / 2;

                    // Update CGST and SGST fields
                    row.querySelector('.gst-rate').value = gstRate.toFixed(2); // Set GST Rate
                    row.querySelector('.cgst').value = cgst.toFixed(2);
                    row.querySelector('.sgst').value = sgst.toFixed(2);
                } else {
                    // Clear GST rate, CGST, and SGST if GST rate not found
                    row.querySelector('.gst-rate').value = '';
                    row.querySelector('.cgst').value = '';
                    row.querySelector('.sgst').value = '';
                }

                // Recalculate total after updating fields
                calculateTotal();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('An error occurred while fetching data.'); // Inform the user
            });
    } else {
        // Clear HSN, CGST, SGST, and GST rate if input is empty
        row.querySelector('.hsn-code').value = '';
        row.querySelector('.cgst').value = '';
        row.querySelector('.sgst').value = '';
        row.querySelector('.gst-rate').value = '';
    }
}


    // Calculate total for each product and overall invoice total
    function calculateTotal() {
        let invoiceTotal = 0;

        document.querySelectorAll('#invoice_table tbody tr').forEach(row => {
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const cgst = parseFloat(row.querySelector('.cgst').value) || 0;
            const sgst = parseFloat(row.querySelector('.sgst').value) || 0;
            const igst = parseFloat(row.querySelector('.igst').value) || 0;

            const gstRate = cgst + sgst + igst;
            const itemTotal = (price * quantity) * (1 + gstRate / 100);
            row.querySelector('.item-total').value = itemTotal.toFixed(2);

            invoiceTotal += itemTotal;
        });

        document.getElementById('total').value = invoiceTotal.toFixed(2);
    }

    // Add more product rows
    function addItem() {
        const row = `<tr>
            <td><input type="text" class="form-control product-name" name="product_name[]" placeholder="Enter product name" oninput="fetchHSN(this)" required></td>
            <td><input type="number" class="form-control calculate quantity" name="quantity[]" placeholder="Quantity" oninput="calculateTotal()" required></td>
            <td><input type="number" class="form-control calculate price" name="price[]" placeholder="Price" oninput="calculateTotal()" required></td>
            <td><input type="text" class="form-control hsn-code" name="hsn_code[]" placeholder="HSN Code" readonly></td>
            <td><input type="number" class="form-control gst-rate" name="gst_rate" placeholder="GST Rate"></td>
            <td><input type="number" class="form-control calculate cgst" name="cgst[]" placeholder="CGST" step="0.01" ></td>
            <td><input type="number" class="form-control calculate sgst" name="sgst[]" placeholder="SGST" step="0.01" ></td>
            <td><input type="number" class="form-control calculate igst" name="igst[]" placeholder="IGST" step="0.01"></td>
            <td><input type="text" class="form-control item-total" name="item_total[]" placeholder="Total" readonly></td>
            <td><button type="button" onClick="removeRow(this)" class="btn btn-danger btn-xs">Remove</button></td>
        </tr>`;
        $("#invoice_table tbody").append(row);
    }

    // Remove a product row
    function removeRow(button) {
        $(button).closest("tr").remove();
        calculateTotal();
    }
</script>

</body>
</html>

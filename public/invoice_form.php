<?php 
session_start();
require "../config/database.php"; // Assuming this is for the `tax_invoice` database

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_SESSION['user_id'];
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

    $cgst = $_POST['cgst'];
    $sgst = $_POST['sgst'];

    $total = $_POST['total'];  // Total is calculated in JavaScript
    $date = $_POST['date'];

    // Fetch HSN code based on product name
    $hsn_stmt = $pdo->prepare("SELECT hsn_code FROM product_hsn_mapping WHERE product_name = ?");
    $hsn_stmt->execute([$product_name]);
    $hsn_code = $hsn_stmt->fetchColumn();

    if ($hsn_code === false) {
        echo "HSN code not found for the product.";
        exit();
    }

    // Insert invoice details into tax_invoice database
    $stmt = $pdo->prepare("INSERT INTO tax_invoice (user_id, name, company_name, address, state, product_name, hsn_code, price, quantity, cgst, sgst, igst, total, date) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $company_name, $address, $state, $product_name, $hsn_code, $price, $quantity, $cgst, $sgst, 0, $total, $date]); // IGST can be set to 0 if not used

    header("Location: invoice_view.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Form</title>
    <!-- including styling start -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- including styling end -->
</head>
<body>
    <div class="container">
        <h1>Create Invoice</h1>
        <form method="POST" action="invoice_form.php">
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
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" class="pname" name="product_name" placeholder="Enter product name" oninput="fetchHSN()" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" placeholder="Price" required oninput="calculateTotal()"/>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" placeholder="Quantity" required oninput="calculateTotal()"/>
            </div>
            
            <div class="form-group">
                <label for="hsn_code">HSN Code</label>
                <input type="text" id="hsn_code" name="hsn_code" placeholder="HSN Code" readonly/>
            </div>
            
            <div class="form-group">
                <label for="cgst">CGST (%)</label>
                <input type="number" id="cgst" name="cgst" placeholder="CGST Rate" step="0.01" oninput="calculateTotal()" required />
            </div>
            
            <div class="form-group">
                <label for="sgst">SGST (%)</label>
                <input type="number" id="sgst" name="sgst" placeholder="SGST Rate" step="0.01" oninput="calculateTotal()" required />
            </div>
            
            <div class="form-group">
                <label for="igst">IGST (%)</label>
                <input type="number" id="igst" name="igst" placeholder="IGST Rate" oninput="calculateTotal()" step="0.01"/>
            </div>

            <div class="form-group">
                <button type="button" onClick="addItem()" id="invoice-items">Add More Product + </button>
            </div>
            
            <div class="form-group">
                <label for="total">Total Amount</label>
                <input type="number" id="total" name="total" placeholder="Total Amount" readonly>
            </div>

            <button class="btn" type="submit">Submit</button>
        </form>
    </div>

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

function addItem() {
    var newItem = document.createElement("div");
    newItem.classList.add("form-group"); // Use class instead of id for new items
    
    newItem.innerHTML = `
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="pname" name="product_name[]" placeholder="Enter product name" oninput="fetchHSN(this)" required>
        </div>
        
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price[]" placeholder="Price" required oninput="calculateTotal()"/>
        </div>
        
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity[]" placeholder="Quantity" required oninput="calculateTotal()"/>
        </div>
        
        <div class="form-group">
            <label for="hsn_code">HSN Code</label>
            <input type="text" name="hsn_code[]" placeholder="HSN Code" readonly/>
        </div>
        
        <div class="form-group">
            <label for="cgst">CGST (%)</label>
            <input type="number" name="cgst[]" placeholder="CGST Rate" step="0.01" oninput="calculateTotal()" required />
        </div>
        
        <div class="form-group">
            <label for="sgst">SGST (%)</label>
            <input type="number" name="sgst[]" placeholder="SGST Rate" step="0.01" oninput="calculateTotal()" required />
        </div>
        
        <div class="form-group">
            <label for="igst">IGST (%)</label>
            <input type="number" name="igst[]" placeholder="IGST Rate" oninput="calculateTotal()" step="0.01"/>
        </div>
    `;
    
    document.getElementById('invoice-items').appendChild(newItem);
}


    </script>
</body>
</html>

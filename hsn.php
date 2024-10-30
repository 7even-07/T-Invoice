<?php 
session_start();
require "./config/database.php";  // Ensure the $pdo connection is correctly set in this file

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $product_name = $_POST['product_name'];
    $hsn_code = $_POST['hsn_code'];
    $gst_rate = $_POST['gst_rate'];

    // Prepare SQL query to insert data into the database
    $sql = "INSERT INTO product_hsn_mapping (product_name, hsn_code, gst_rate) VALUES (?, ?, ?)";

    if($stmt = $pdo->prepare($sql)) {
        // Bind parameters using bindValue (for PDO)
        $stmt->bindValue(1, $product_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $hsn_code, PDO::PARAM_STR);
        $stmt->bindValue(3, $gst_rate, PDO::PARAM_INT);

        // Execute the query
        if($stmt->execute()) {
            echo "Product details submitted successfully!";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error preparing the SQL query.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSN Code Form</title>
</head>
<body>

    <form method="POST" action="./hsn.php">
        <label for="product_name">Product Name: </label>
        <input type="text" id="product_name" name="product_name" required><br>

        <label for="hsn_code">HSN Code: </label>
        <input type="text" name="hsn_code" required><br>

        <label for="gst_rate">GST Rate: </label>
        <input type="number" name="gst_rate" required><br>

        <input type="submit" value="Submit">
    </form>
    
    <script>
        const productInput = document.getElementById('product_name');
        productInput.addEventListener("input", () => {
            productInput.value = productInput.value.toLowerCase();
        });

    </script>

</body>
</html>

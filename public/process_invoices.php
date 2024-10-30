<?php 
session_start();
require "../config/database.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Ensure selected invoices are set
    if (isset($_POST['selected_invoices'])) {
        $selected_ids = $_POST['selected_invoices'];

        if (isset($_POST['delete'])) {
            // Delete selected invoices
            $placeholders = rtrim(str_repeat('?,', count($selected_ids)), ','); // Add a comma before the last placeholder
            $stmt = $pdo->prepare("DELETE FROM tax_invoice WHERE id IN ($placeholders)");
            $stmt->execute($selected_ids);
            header("Location: invoice_view.php");
            exit();
        }

        if (isset($_POST['edit'])) {
            // Redirect to edit page with the first selected invoice's ID
            $first_id = $selected_ids[0];
            header("Location: edit_invoice.php?id=$first_id"); // Pass the ID in the URL
            exit();
        }
    } else {
        // No invoice selected
        echo "No invoice selected"; // Added a semicolon at the end
    }
}
?>

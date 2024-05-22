<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['user'] == "") {
    header("location:./login.php");
    exit; // Stop further execution
}

require_once("connection/connectionConfig.php");

// Function to handle CSV file uploads
function handleCSVUpload($file, $tableName, $dbh) {
    // Check if the file was uploaded without errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "File upload error: " . $file['error'];
        return false;
    }

    // Check if the uploaded file is a CSV
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (strtolower($fileType) !== 'csv') {
        echo "Invalid file format. Please upload a CSV file.";
        return false;
    }

    // Read the CSV file
    $csvData = array_map('str_getcsv', file($file['tmp_name']));

    // Insert data into the specified table
    $columns = implode(",", array_map('trim', $csvData[0]));
    $values = array_slice($csvData, 1);
    $placeholders = implode(",", array_fill(0, count($csvData[0]), "?"));

    $stmt = $dbh->prepare("INSERT INTO $tableName ($columns) VALUES ($placeholders)");

    try {
        $dbh->beginTransaction();
        foreach ($values as $row) {
            $stmt->execute($row);
        }
        $dbh->commit();
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Handle CSV file uploads
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the files were uploaded
    if (isset($_FILES['csv_items']) && isset($_FILES['csv_invoice']) && isset($_FILES['csv_invoicedt'])) {
        // Handle each CSV file upload
        $success_items = handleCSVUpload($_FILES['csv_items'], 'items', $dbh);
        $success_invoice = handleCSVUpload($_FILES['csv_invoice'], 'invoice', $dbh);
        $success_invoicedt = handleCSVUpload($_FILES['csv_invoicedt'], 'invoicedt', $dbh);

        // Check if all uploads were successful
        if ($success_items && $success_invoice && $success_invoicedt) {
            echo "All files uploaded successfully.";
        } else {
            echo "An error occurred during file upload.";
        }
    } else {
        echo "Please upload all required CSV files.";
    }
}
?>

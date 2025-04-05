<?php
session_start();
require '../../config.php';

// Redirect if not admin or not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload error: " . $file['error'];
    } elseif ($file['type'] !== 'text/csv' && !preg_match('/\.csv$/i', $file['name'])) {
        $error = "Only CSV files are allowed";
    } else {
        // Process Excel file
        try {
            // Simple CSV processing for SQLite compatibility
            $handle = fopen($file['tmp_name'], 'r');
            if ($handle !== false) {
                try {
                    // Skip header row
                    fgetcsv($handle);
                    
                    $conn->beginTransaction();
                    $stmt = $conn->prepare("INSERT INTO shipping_data 
                        (invoice_number, invoice_date, trans_date, cust_po, ship_via, comment, 
                        ship_to_name, item_code, description, qty_ordered, qty_shipped, 
                        qty_backorder, pro_number) 
                        VALUES (:invoice_number, :invoice_date, :trans_date, :cust_po, :ship_via, :comment, 
                        :ship_to_name, :item_code, :description, :qty_ordered, :qty_shipped, 
                        :qty_backorder, :pro_number)");
                    
                    while (($row = fgetcsv($handle)) !== false) {
                        // Skip empty rows
                        if (empty(array_filter($row))) continue;
                        
                        $stmt->bindValue(':invoice_number', $row[0]);
                        $stmt->bindValue(':invoice_date', $row[1]);
                        $stmt->bindValue(':trans_date', $row[2]);
                        $stmt->bindValue(':cust_po', $row[3]);
                        $stmt->bindValue(':ship_via', $row[4]);
                        $stmt->bindValue(':comment', $row[5]);
                        $stmt->bindValue(':ship_to_name', $row[6]);
                        $stmt->bindValue(':item_code', $row[7]);
                        $stmt->bindValue(':description', $row[8]);
                        $stmt->bindValue(':qty_ordered', (int)$row[9]);
                        $stmt->bindValue(':qty_shipped', (int)$row[10]);
                        $stmt->bindValue(':qty_backorder', (int)$row[11]);
                        $stmt->bindValue(':pro_number', $row[12]);
                        
                        $stmt->execute();
                    }
                    $conn->commit();
                    $message = "Data uploaded successfully!";
                } catch (PDOException $e) {
                    if (isset($conn) && $conn->inTransaction()) {
                        $conn->rollback();
                    }
                    $error = "Database error: " . $e->getMessage();
                } finally {
                    fclose($handle);
                }
            } else {
                $error = "Could not read the uploaded file";
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Error processing file: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Upload Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar {
            width: 250px;
        }
        .content {
            margin-left: 250px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-white fixed h-full">
            <div class="p-4">
                <h1 class="text-xl font-bold"><?php echo htmlspecialchars($_SESSION['business_name']); ?></h1>
                <p class="text-gray-400 text-sm">Admin Portal</p>
            </div>
            <nav class="mt-6">
                <a href="upload.php" class="block py-2 px-4 bg-gray-700">Upload Data</a>
                <a href="preview.php" class="block py-2 px-4 hover:bg-gray-700">Preview Data</a>
                <a href="../../logout.php" class="block py-2 px-4 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content flex-1 p-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Upload Shipping Data</h2>
                
                <?php if ($message): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Excel File (.xlsx)</label>
                        <div class="mt-1 flex items-center">
                            <input type="file" name="excel_file" accept=".xlsx" required
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Upload daily shipping data in Excel format</p>
                    </div>
                    
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-upload mr-2"></i> Upload Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// process_edit_car.php

include 'php/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $car_id      = intval($_POST['car_id']);
    $make        = $conn->real_escape_string($_POST['make']);
    $model       = $conn->real_escape_string($_POST['model']);
    $year        = intval($_POST['year']);
    $category    = $conn->real_escape_string($_POST['category']);
    $status      = $conn->real_escape_string($_POST['status']);
    
    $seaters     = intval($_POST['seaters']);
    $num_doors   = intval($_POST['num_doors']);
    $runs_on_gas = $conn->real_escape_string($_POST['runs_on_gas']);
    $mpg         = floatval($_POST['mpg']);
    
    // Initialize variable for new display image path if uploaded
    $display_image_path = "";
    
    // Process display image upload if a new file is provided
    if (isset($_FILES['display_image']) && $_FILES['display_image']['error'] == 0) {
        $targetDir = "images/cars/"; // Adjust path if necessary
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename   = uniqid() . "_" . basename($_FILES['display_image']['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES['display_image']['tmp_name'], $targetFile)) {
            // Store the relative path (adjust if your stored path should be different)
            $display_image_path = "images/cars/" . $filename;
        } else {
            die("Error uploading new display image.");
        }
    }
    
    // Update the cars table
    if (!empty($display_image_path)) {
        $updateSQL = "UPDATE cars SET 
                        make = '$make', 
                        model = '$model', 
                        year = '$year', 
                        category = '$category', 
                        status = '$status', 
                        seaters = $seaters,
                        num_doors = $num_doors,
                        runs_on_gas = '$runs_on_gas',
                        mpg = $mpg,
                        display_image = '$display_image_path'
                      WHERE id = $car_id";
    } else {
        $updateSQL = "UPDATE cars SET 
                        make = '$make', 
                        model = '$model', 
                        year = '$year', 
                        category = '$category', 
                        status = '$status',
                        seaters = $seaters,
                        num_doors = $num_doors,
                        runs_on_gas = '$runs_on_gas',
                        mpg = $mpg
                      WHERE id = $car_id";
    }
    
    if (!$conn->query($updateSQL)) {
        die("Error updating car: " . $conn->error);
    }
    
    // Update rental rates:
    // Delete existing rates for this car
    $deleteSQL = "DELETE FROM car_rental_rates WHERE car_id = $car_id";
    $conn->query($deleteSQL);
    
    // Insert new rates for days 1 to 7
    for ($d = 1; $d <= 7; $d++) {
        if (isset($_POST["rental_rate_$d"]) && $_POST["rental_rate_$d"] !== "") {
            $rate = floatval($_POST["rental_rate_$d"]);
            $insertSQL = "INSERT INTO car_rental_rates (car_id, rental_day, rate) VALUES ($car_id, $d, $rate)";
            if (!$conn->query($insertSQL)) {
                die("Error inserting rental rate for day $d: " . $conn->error);
            }
        }
    }

    // Process additional images upload if provided
if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'][0] === 0) {
    // Loop through each additional image file
    for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
        if ($_FILES['additional_images']['error'][$i] === 0) {
            $targetDir = "images/cars/"; // Ensure this folder exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $filename = uniqid() . "_" . basename($_FILES['additional_images']['name'][$i]);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $targetFile)) {
                // Insert this additional image into the car_images table
                $sqlImage = "INSERT INTO car_images (car_id, image_path) VALUES ($car_id, '$targetFile')";
                $conn->query($sqlImage);
            } else {
                // Optionally handle an individual file upload error (e.g., log it)
                // For now, you can ignore errors for additional images.
            }
        }
    }
}
    
    $conn->close();
    
    // Output an HTML page that notifies success and instructs the parent to close the modal and reload
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Success</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-3">
            <div class="alert alert-success">
                Car updated successfully!
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var modalEl = window.parent.document.getElementById('editCarModal');
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalEl);
            }
            modalInstance.hide();
            setTimeout(function(){
                window.parent.location.reload();
            }, 2000);
        </script>
    </body>
    </html>
    <?php
    exit();
} else {
    die("Invalid request method.");
}
?>

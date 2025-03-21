<?php
// process_edit_car.php

include 'php/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $car_id   = intval($_POST['car_id']);
    $make     = $conn->real_escape_string($_POST['make']);
    $model    = $conn->real_escape_string($_POST['model']);
    $year     = intval($_POST['year']);
    $category = $conn->real_escape_string($_POST['category']);
    $status   = $conn->real_escape_string($_POST['status']);
    
    // Initialize variable for new display image path if uploaded
    $display_image_path = "";
    
    // Process display image upload if a new file is provided
    if (isset($_FILES['display_image']) && $_FILES['display_image']['error'] == 0) {
        $targetDir = "../images/cars/"; // Adjust path if necessary
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = uniqid() . "_" . basename($_FILES['display_image']['name']);
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
                        display_image = '$display_image_path'
                      WHERE id = $car_id";
    } else {
        $updateSQL = "UPDATE cars SET 
                        make = '$make', 
                        model = '$model', 
                        year = '$year', 
                        category = '$category', 
                        status = '$status'
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
    
    $conn->close();
    
    // Output an HTML page with a Bootstrap modal that shows a success message and redirects
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Success</title>
        <!-- Use Bootstrap 5 CSS from CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <!-- Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
              </div>
              <div class="modal-body">
                Car updated successfully!
              </div>
            </div>
          </div>
        </div>
        
        <!-- Bootstrap 5 Bundle with Popper from CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Create and show the modal
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            // Redirect after 2 seconds
            setTimeout(function(){
                window.location.href = 'admin-dashboard.php';
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

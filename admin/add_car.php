<?php
include '../php/dbconfig.php';

// Initialize variables for messages
$error = "";
$success = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect car details from form inputs
    $make        = trim($_POST['make']);
    $model       = trim($_POST['model']);
    $year        = intval($_POST['year']);
    $category    = trim($_POST['category']);
    $rental_rate = floatval($_POST['rental_rate']);
    $status      = trim($_POST['status']);

    // Process the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'images/cars/'; // Directory where images are stored (make sure it exists and is writable)
        // Generate a unique file name to avoid collisions
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $filename;

    } else {
        $error = "No image uploaded or an error occurred during the upload.";
    }

    // If no errors,     the new car record into the database
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO cars (make, model, year, category, rental_rate, status, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // 'ssisdss' stands for: string, string, integer, string, double, string, string.
        $stmt->bind_param("ssisdss", $make, $model, $year, $category, $rental_rate, $status, $uploadFile);
        if ($stmt->execute()) {
            $success = "New car added successfully!";
        } else {
            $error = "Error adding car: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Add New Car</title>
    <link rel="icon" href="images/rel-icon.png" type="image/gif" sizes="32x32">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/coloring.css" rel="stylesheet" type="text/css">
    <link id="colors" href="css/colors/scheme-07.css" rel="stylesheet" type="text/css">
</head>
<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">
        <div id="de-preloader"></div>

        <header class="transparent has-topbar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex sm-pt10">
                            <div class="de-flex-col">
                                <div id="logo">
                                    <a href="index.html">
                                        <img class="logo-1" src="images/logo-purerental.png" alt="">
                                        <img class="logo-2" src="images/logo-purerental.png" alt="">
                                    </a>
                                </div>
                                <!-- logo close -->
                            </div>
                        </div>
                        <div class="de-flex-col header-col-mid">
                            <ul id="mainmenu">
                                <li><a href="index.html" class="menu-item">Home</a></li>
                                <li><a href="cars-list.php" class="menu-item">Car Fleet</a></li>
                                <li><a href="about.html" class="menu-item">About Us</a></li>
                            </ul>
                        </div>
                        <div class="de-flex-col">
                            <div class="menu_side_area">
                                <a href="login.php" class="btn-main">Sign In</a>
                                <span id="menu-btn"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>


    </div>
    <!-- Header or Navigation can be included here if needed -->
    <div class="container" style="padding: 50px;">
        <h1 class="mb-4">Add New Car</h1>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        
        <!-- The form includes enctype="multipart/form-data" for file uploads -->
        <form action="process_add_car.php" method="post" enctype="multipart/form-data">
            <!-- Car Basic Details -->
            <label>Make:</label>
            <input type="text" name="make" required>
            
            <label>Model:</label>
            <input type="text" name="model" required>
            
            <label>Year:</label>
            <input type="number" name="year" required>
            
            <label>Category:</label>
            <input type="text" name="category" required>
            
            <!-- Official (Display) Image -->
            <label>Official (Display) Image:</label>
            <input type="file" name="display_image" required>
            
            <!-- Additional Images -->
            <label>Additional Images:</label>
            <input type="file" name="additional_images[]" multiple>
            
            <!-- Rental Rates for 1 to 7 Days (if needed) -->
                <label>Rental Rate (1-Day):</label>
                <input type="number" step="0.01" name="rental_rate_1" required>

                <label>Rental Rate (2-Day):</label>
                <input type="number" step="0.01" name="rental_rate_2" required>

                <label>Rental Rate (3-Day):</label>
                <input type="number" step="0.01" name="rental_rate_3" required>

                <label>Rental Rate (4-Day):</label>
                <input type="number" step="0.01" name="rental_rate_4" required>

                <label>Rental Rate (5-Day):</label>
                <input type="number" step="0.01" name="rental_rate_5" required>

                <label>Rental Rate (6-Day):</label>
                <input type="number" step="0.01" name="rental_rate_6" required>

                <label>Rental Rate (7-Day):</label>
                <input type="number" step="0.01" name="rental_rate_7" required>
            
            <input type="submit" value="Add Car">
        </form>


    </div>

    <!-- Javascript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
</body>
</html>

<?php
include '../php/dbconfig.php';

// Initialize variables for messages
$error = "";
$success = "";

// Process form submission if POSTed to this page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect car details from form inputs
    $make        = trim($_POST['make']);
    $model       = trim($_POST['model']);
    $year        = intval($_POST['year']);
    $category    = trim($_POST['category']);
    // Set status as Available by default.
    $status      = "Available";

    // Process the uploaded display image
    if (isset($_FILES['display_image']) && $_FILES['display_image']['error'] === 0) {
        $uploadDir = 'images/cars/'; // Ensure this directory exists and is writable
        // Generate a unique file name to avoid collisions
        $filename = uniqid() . '_' . basename($_FILES['display_image']['name']);
        $uploadFile = $uploadDir . $filename;
        if (!move_uploaded_file($_FILES['display_image']['tmp_name'], $uploadFile)) {
            $error = "Error uploading the display image.";
        }
    } else {
        $error = "No display image uploaded or an error occurred during the upload.";
    }

    // If no errors, insert the new car record into the database
    if (empty($error)) {
        // Insert the main car record
        $stmt = $conn->prepare("INSERT INTO cars (make, model, year, category, status, display_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $make, $model, $year, $category, $status, $uploadFile);
        if ($stmt->execute()) {
            // After inserting the car, get its ID
            $car_id = $stmt->insert_id;
            $stmt->close();

            // Now insert the rental rates for days 1 to 7
            $rateFields = [
                'rental_rate_1',
                'rental_rate_2',
                'rental_rate_3',
                'rental_rate_4',
                'rental_rate_5',
                'rental_rate_6',
                'rental_rate_7'
            ];
            $insertRates = $conn->prepare("INSERT INTO car_rental_rates (car_id, rental_day, rate) VALUES (?, ?, ?)");
            foreach ($rateFields as $index => $field) {
                $day = $index + 1;
                $rate = floatval($_POST[$field]);
                $insertRates->bind_param("iid", $car_id, $day, $rate);
                $insertRates->execute();
            }
            $insertRates->close();

            $success = "New car added successfully!";
        } else {
            $error = "Error adding car: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../images/rel-icon.png" type="image/gif" sizes="32x32">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pure Rental Group Webpage">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- CSS Files -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="../css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="../css/plugins.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/coloring.css" rel="stylesheet" type="text/css">
    <!-- Color scheme -->
    <link id="colors" href="../css/colors/scheme-07.css" rel="stylesheet" type="text/css">
    <style>
        /* Dark-scheme settings to match index.php */
        body.dark-scheme {
            background-color: #1e1e2d;
            color: #c7c7c7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Header styling (consistent with index.php) */
        header.transparent {
            background: rgba(0, 0, 0, 0.7);
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        header .menu-item {
            color: #fff;
            margin: 0 10px;
            transition: color 0.3s;
        }
        header .menu-item:hover {
            color: #3498db;
        }
        header .btn-main {
            background-color: #3498db;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        header .btn-main:hover {
            background-color: #2980b9;
        }
        /* Container for the add car form */
        .add-car-container {
            background: rgba(46, 46, 62, 0.9);
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            margin: 80px auto;
            max-width: 800px;
        }
        .add-car-container h1 {
            color: #fff;
            margin-bottom: 30px;
            text-align: center;
        }
        /* Form group and input styling */
        .add-car-container .form-group label {
            color: #ddd;
            font-weight: 500;
        }
        .add-car-container .form-control,
        .add-car-container .form-control-file {
            background: #444;
            border: 1px solid #555;
            color: #fff;
        }
        .add-car-container .form-control:focus {
            background: #555;
            border-color: #3498db;
            box-shadow: none;
        }
        /* Button styling matching btn-main */
        .add-car-container .btn-main {
            background-color: #3498db;
            color: #fff;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-car-container .btn-main:hover {
            background-color: #2980b9;
        }
        /* Alert styling */
        .add-car-container .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">
        <div id="de-preloader"></div>

        <!-- Header Begin -->
        <header class="transparent scroll-light has-topbar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <!-- Logo Begin -->
                        <div id="logo">
                            <a href="../index.php">
                                <img class="logo-1" src="../images/logo-purerental.png" alt="">
                                <img class="logo-2" src="../images/logo-purerental.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Main Content -->
        <div class="container add-car-container" style="margin-top: 150px;">
            <h1>Add New Car</h1>

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

            <!-- Form with file upload support -->
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Car Basic Details -->
                <div class="form-group">
                    <label for="make">Make:</label>
                    <input type="text" name="make" id="make" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" name="model" id="model" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" name="year" id="year" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" name="category" id="category" class="form-control" required>
                </div>
                
                <!-- Official (Display) Image -->
                <div class="form-group">
                    <label for="display_image">Official (Display) Image:</label>
                    <input type="file" name="display_image" id="display_image" class="form-control-file" required>
                </div>
                
                <!-- Additional Images -->
                <div class="form-group">
                    <label for="additional_images">Additional Images:</label>
                    <input type="file" name="additional_images[]" id="additional_images" class="form-control-file" multiple>
                </div>
                
                <!-- Rental Rates for Days 1 to 7 -->
                <div class="form-group">
                    <label for="rental_rate_1">Rental Rate (1-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_1" id="rental_rate_1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_2">Rental Rate (2-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_2" id="rental_rate_2" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_3">Rental Rate (3-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_3" id="rental_rate_3" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_4">Rental Rate (4-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_4" id="rental_rate_4" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_5">Rental Rate (5-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_5" id="rental_rate_5" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_6">Rental Rate (6-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_6" id="rental_rate_6" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rental_rate_7">Rental Rate (7-Day):</label>
                    <input type="number" step="0.01" name="rental_rate_7" id="rental_rate_7" class="form-control" required>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn-main">Add Car</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript Files -->
    <script src="../js/plugins.js"></script>
    <script src="../js/designesia.js"></script>
</body>
</html>

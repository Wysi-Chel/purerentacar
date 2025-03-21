<?php
// admin-edit-car.php

include 'php/dbconfig.php';

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}

$car_id = intval($_GET['id']);

// Fetch car details from the cars table
$sql = "SELECT * FROM cars WHERE id = $car_id";
$carResult = $conn->query($sql);
if ($carResult->num_rows == 0) {
    die("Car not found.");
}
$car = $carResult->fetch_assoc();

// Fetch rental rates for this car (assuming days 1 to 7)
$rentalRates = [];
$sqlRates = "SELECT rental_day, rate FROM car_rental_rates WHERE car_id = $car_id";
$ratesResult = $conn->query($sqlRates);
if ($ratesResult && $ratesResult->num_rows > 0) {
    while ($row = $ratesResult->fetch_assoc()) {
        $rentalRates[$row['rental_day']] = $row['rate'];
    }
}
// Ensure all days 1-7 exist (default to empty if not set)
for ($d = 1; $d <= 7; $d++) {
    if (!isset($rentalRates[$d])) {
        $rentalRates[$d] = "";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Car Details</title>
    <link rel="icon" href="images/rel-icon.png" type="image/gif" sizes="16x16">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/plugins.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/coloring.css" rel="stylesheet">
    <link id="colors" href="css/colors/scheme-07.css" rel="stylesheet">
    <style>
      .current-img {
          max-width: 200px;
          margin-bottom: 10px;
      }
    </style>
</head>
<body class="dark-scheme">
    <div class="container mt-5">
        <h2>Edit Car Details</h2>
        <form action="process_edit_car.php" method="post" enctype="multipart/form-data">
            <!-- Hidden input for car id -->
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">

            <div class="mb-3">
                <label for="make" class="form-label">Make</label>
                <input type="text" class="form-control" id="make" name="make" value="<?php echo htmlspecialchars($car['make']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($car['year']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($car['category']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Available" <?php echo (strtolower($car['status']) === 'available' || $car['status'] == '1') ? "selected" : ""; ?>>Available</option>
                    <option value="Unavailable" <?php echo (strtolower($car['status']) !== 'available' && $car['status'] != '1') ? "selected" : ""; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Official (Display) Image</label><br>
                <?php if (!empty($car['display_image'])): ?>
                    <img src="<?php echo $car['display_image']; ?>" alt="Current Display Image" class="current-img">
                <?php else: ?>
                    <p>No official image uploaded.</p>
                <?php endif; ?>
                <input type="file" class="form-control" name="display_image">
                <small class="form-text text-muted">Upload a new image to replace the current official image.</small>
            </div>

            <hr>
            <h4>Rental Rates</h4>
            <p>Enter rental rate for each day (1 - 7). Leave blank if not applicable.</p>
            <?php for ($d = 1; $d <= 7; $d++): ?>
                <div class="mb-3">
                    <label for="rental_rate_<?php echo $d; ?>" class="form-label"><?php echo $d; ?>-Day Rate</label>
                    <input type="number" step="0.01" class="form-control" id="rental_rate_<?php echo $d; ?>" name="rental_rate_<?php echo $d; ?>" value="<?php echo htmlspecialchars($rentalRates[$d]); ?>">
                </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Javascript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
</body>
</html>

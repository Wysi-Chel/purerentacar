<?php
// car-single.php
include 'php/dbconfig.php';

// Check for a valid car id in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Car not found.");
}
$car_id = intval($_GET['id']);

// Fetch car details from the cars table with the 1-day rental rate
$sql = "SELECT c.*, 
        (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 1) AS rental_rate 
        FROM cars c WHERE c.id = $car_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Car not found.");
}
$car = $result->fetch_assoc();

// Fetch additional images for the carousel (if available)
$sqlImages = "SELECT image_path FROM car_images WHERE car_id = $car_id ORDER BY id ASC";
$resultImages = $conn->query($sqlImages);
$images = [];
if ($resultImages->num_rows > 0) {
    while ($row = $resultImages->fetch_assoc()) {
        $images[] = $row['image_path'];
    }
} else {
    // If no additional images, fallback to the display image or a default image
    if (!empty($car['display_image'])) {
        $images[] = $car['display_image'];
    } else {
        $images[] = "images/default-car.jpg";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking</title>
</head>
<?php include 'head.php';?>
<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">
        <div id="de-preloader"></div>
        <?php include 'header.php';?>

        <!-- content begin -->
        <div class="no-bottom no-top" id="content">
            <!-- Subheader Section -->
            <section>
            </section>
            <!-- Section Car Details -->
            <section id="section-car-details" style="margin-top: -130px;">
                <div class="container">
                    <div class="row g-5">
                        <!-- Carousel Column -->
                        <div class="col-lg-6">
                            <div id="slider-carousel" class="owl-carousel">
                                <?php foreach ($images as $img): ?>
                                    <div class="item">
                                        <img src="<?php echo $img; ?>" alt="<?php echo $car['make'] . " " . $car['model']; ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Car Details Column -->
                        <div class="col-lg-3">
                            <h3><?php echo $car['make'] . " " . $car['model']; ?></h3>
                            <p><?php echo isset($car['description']) ? $car['description'] : "No description available."; ?></p>
                            <div class="spacer-10"></div>
                            <h4>Specifications</h4>
                            <div class="de-spec">
                                <div class="d-row">
                                    <span class="d-title">Body</span>
                                    <span class="d-value"><?php echo $car['body_type']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Seat</span>
                                    <span class="d-value"><?php echo $car['seats']; ?> seats</span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Door</span>
                                    <span class="d-value"><?php echo $car['doors']; ?> doors</span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Luggage</span>
                                    <span class="d-value"><?php echo $car['luggage']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Fuel Type</span>
                                    <span class="d-value"><?php echo $car['fuel']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Engine</span>
                                    <span class="d-value"><?php echo $car['engine']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Year</span>
                                    <span class="d-value"><?php echo $car['year']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Mileage</span>
                                    <span class="d-value"><?php echo $car['mileage']; ?></span>
                                </div>  
                                <div class="d-row">
                                    <span class="d-title">Transmission</span>
                                    <span class="d-value"><?php echo $car['transmission']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Drive</span>
                                    <span class="d-value"><?php echo $car['drive']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Fuel Economy</span>
                                    <span class="d-value"><?php echo $car['fuel_economy']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Exterior Color</span>
                                    <span class="d-value"><?php echo $car['exterior_color']; ?></span>
                                </div>
                                <div class="d-row">
                                    <span class="d-title">Interior Color</span>
                                    <span class="d-value"><?php echo $car['interior_color']; ?></span>
                                </div>
                            </div>
                            <div class="spacer-single"></div>
                            <h4>Features</h4>
                            <ul class="ul-style-2">
                                <?php
                                $features = explode(",", $car['features']);
                                foreach ($features as $feature) {
                                    echo "<li>" . trim($feature) . "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- Booking Column -->
                        <div class="col-lg-3">
                            <div class="de-price text-center">
                                Daily rate
                                <h3>$<?php echo (!empty($car['rental_rate']) ? number_format($car['rental_rate'], 2) : "N/A"); ?></h3>
                            </div>
                            <div class="spacer-30"></div>
                            <div class="de-box mb25">
                                <form name="contactForm" id="contact_form" method="post" action="process_booking.php">
                                    <h4>Booking this car</h4>
                                    <div class="spacer-20"></div>
                                    <div class="row">
                                        <div class="col-lg-12 mb20">
                                            <h5>Pick Up Location</h5>
                                            <span>9521 Lumley Rd, Morrisville, NC 27560, United States</span>
                                        </div>
                                        <div class="col-lg-12 mb20">
                                            <h5>Drop Off Location</h5>
                                            <span>9521 Lumley Rd, Morrisville, NC 27560, United States</span></div>
                                        <div class="col-lg-12 mb20">
                                            <h5>Pick Up Date & Time</h5>
                                            <div class="date-time-field">
                                                <input type="text" id="date-picker" name="PickUpDate" value="">
                                                <select name="PickUpTime" id="pickup-time" class="form-control">
                                                    <?php
                                                    for ($h = 0; $h < 24; $h++) {
                                                        for ($m = 0; $m < 60; $m += 30) {
                                                            $time = sprintf("%02d:%02d", $h, $m);
                                                            echo "<option value='$time'>$time</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb20">
                                            <h5>Return Date & Time</h5>
                                            <div class="date-time-field">
                                                <input type="text" id="date-picker-2" name="ReturnDate" value="">
                                                <select name="ReturnTime" id="return-time" class="form-control">
                                                    <?php
                                                    for ($h = 0; $h < 24; $h++) {
                                                        for ($m = 0; $m < 60; $m += 30) {
                                                            $time = sprintf("%02d:%02d", $h, $m);
                                                            echo "<option value='$time'>$time</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                                    <input type="submit" id="send_message" value="Book Now" class="btn-main btn-fullwidth">
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                            <!-- <div class="de-box">
                                <h4>Share</h4>
                                <div class="de-color-icons">
                                    <span><i class="fa fa-twitter fa-lg"></i></span>
                                    <span><i class="fa fa-facebook fa-lg"></i></span>
                                    <span><i class="fa fa-reddit fa-lg"></i></span>
                                    <span><i class="fa fa-linkedin fa-lg"></i></span>
                                    <span><i class="fa fa-pinterest fa-lg"></i></span>
                                    <span><i class="fa fa-stumbleupon fa-lg"></i></span>
                                    <span><i class="fa fa-delicious fa-lg"></i></span>
                                    <span><i class="fa fa-envelope fa-lg"></i></span>
                                </div>
                            </div> -->
                        </div>                
                    </div>
                </div>
            </section>
        </div>
        <!-- content close -->

        <a href="#" id="back-to-top"></a>
        <?php include 'footer.php';?>
    </div>

    <!-- Javascript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&amp;libraries=places&amp;callback=initPlaces" async defer></script>
</body>
</html>

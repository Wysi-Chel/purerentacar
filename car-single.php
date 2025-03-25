<?php
include 'php/dbconfig.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}
$car_id = intval($_GET['id']);

// Fetch main car record
$sql = "SELECT * FROM cars WHERE id = $car_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Car not found.");
}
$car = $result->fetch_assoc();

// Fetch additional images for this car using the correct foreign key "car_id"
$sql_addimage = "SELECT * FROM car_images WHERE car_id = $car_id";
$result_addImage = $conn->query($sql_addimage);
$additional_images = [];
if ($result_addImage && $result_addImage->num_rows > 0) {
    while ($row = $result_addImage->fetch_assoc()) {
        $additional_images[] = $row;
    }
}

// Retrieve the 1-day rental rate as the daily rate
$sqlRate = "SELECT rate FROM car_rental_rates WHERE car_id = $car_id AND rental_day = 1 LIMIT 1";
$resultRate = $conn->query($sqlRate);
if ($resultRate && $resultRate->num_rows > 0) {
    $rateRow = $resultRate->fetch_assoc();
    $daily_rate = $rateRow['rate'];
} else {
    $daily_rate = "N/A";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Details - <?php echo $car['make'] . " " . $car['model']; ?></title>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div id="wrapper">
        <?php include 'header.php'; ?>
        <!-- Subheader Section Begin -->
        <section id="subheader" class="jarallax text-light">
            <img src="images/background/2.jpg" class="jarallax-img" alt="">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>Book Your Car</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Subheader Section Close -->

        <!-- Car Details Section Begin -->
        <section id="section-car-details">
            <div class="container">
                <div class="row g-5">
                    <!-- Slider Column -->
                    <div class="col-lg-6">
                        <div id="slider-carousel" class="owl-carousel">
                            <div class="item">
                                <img src="<?php echo $car['display_image']; ?>" alt="">
                            </div>
                            <?php
                            // Loop through additional images, if any
                            if (!empty($additional_images)):
                                foreach ($additional_images as $img):
                            ?>
                                <div class="item">
                                    <img src="<?php echo $img['image_path']; ?>" alt="">
                                </div>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <!-- Optionally add a default image if no additional images exist -->
                                <div class="item">
                                    <img src="images/default-car.jpg" alt="Default Image">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Car Info & Specifications Column -->
                    <div class="col-lg-3">
                        <h3><?php echo $car['make'] . " " . $car['model'] . " " . $car['year']; ?></h3>
                        <p><?php echo "Experience the excellence of " . $car['make'] . " " . $car['model'] . ". "; ?></p>
                        <div class="spacer-10"></div>

                        <h4>Specifications</h4>
                        <div class="de-spec">
                            <div class="d-row">
                                <span class="d-title">Category</span>
                                <span class="d-value"><?php echo $car['category']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Seat</span>
                                <span class="d-value"><?php echo $car['seaters']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Door</span>
                                <span class="d-value"><?php echo $car['num_doors']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Fuel Type</span>
                                <span class="d-value"><?php echo ucfirst($car['runs_on_gas']); ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Year</span>
                                <span class="d-value"><?php echo $car['year']; ?></span>
                            </div>
                            <div class="d-row">
                                <span class="d-title">Miles per Gallon (MPG)</span>
                                <span class="d-value"><?php echo sprintf('%g', $car['mpg']); ?></span>
                            </div>
                        </div>

                        <div class="spacer-single"></div>
                    </div>

                    <!-- Price & Booking Form Column -->
                    <div class="col-lg-3">
                        <div class="de-price text-center">
                            Daily rate
                            <h3><?php echo ($daily_rate !== "N/A" ? "$" . number_format($daily_rate, 2) : "N/A"); ?></h3>
                        </div>
                        <div class="spacer-30"></div>
                        <div class="de-box mb25">
                            <form name="contactForm" id="contact_form" method="post">
                                <h4>Booking this car</h4>
                                <div class="spacer-20"></div>
                                <div class="row">
                                    <div class="col-lg-12 mb20">
                                        <i class="fa fa-home"></i>
                                        <h5>Pick Up Location</h5>
                                        <h6 style="color:#333;">9521 Lumley Rd, Morrisville, NC 27560, United States</h6>
                                    </div>
                                    <div class="col-lg-12 mb20">
                                        <i class="fa fa-map-marker"></i>
                                        <h5>Drop Off Location</h5>
                                        <h6 style="color:#333;">9521 Lumley Rd, Morrisville, NC 27560, United States</h6>
                                    </div>
                                    <div class="spacer-20"></div>
                                    <div class="col-lg-12 mb20">
                                        <h5>Pick Up Date & Time</h5>
                                        <div class="date-time-field">
                                            <input type="text" id="date-picker" name="PickUpDate" value="">
                                            <select name="PickUpTime" id="pickup-time">
                                                <option selected disabled value="Select time">Time</option>
                                                <!-- Add time options -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb20">
                                        <h5>Return Date & Time</h5>
                                        <div class="date-time-field">
                                            <input type="text" id="date-picker-2" name="ReturnDate" value="">
                                            <select name="ReturnTime" id="collection-time">
                                                <option selected disabled value="Select time">Time</option>
                                                <!-- Add time options -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" id="send_message" value="Book Now" class="btn-main btn-fullwidth">
                                <div class="clearfix"></div>
                            </form>
                        </div>

                        <div class="de-box">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Car Details Section Close -->

        <!-- content close -->
        <a href="#" id="back-to-top"></a>
    </div>

    <!-- Javascript Files ================================================== -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <!-- (Include any additional required JS such as Owl Carousel initialization) -->
</body>
</html>

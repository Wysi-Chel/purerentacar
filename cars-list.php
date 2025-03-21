<?php
// Include the database configuration file
include 'php/dbconfig.php';

// Fetch car data from the 'cars' table along with the 1-day rental rate from car_rental_rates
$sql = "SELECT c.*, 
        (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 1) AS rental_rate 
        FROM cars c";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head><title>Car Fleet</title></head>
<?php include 'head.php';?>
<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">
        <div id="de-preloader"></div>
        <?php include 'header.php';?>

        <!-- content begin -->
        <div class="no-bottom no-top zebra" id="content">
            <div id="top"></div>
            <!-- Subheader Section -->
            <section id="subheader" class="jarallax text-light">
                <img src="images/background/2.jpg" class="jarallax-img" alt="">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Cars Available for Rent</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Section Cars -->
            <section id="section-cars">
                <div class="container">
                    <div class="row">
                        <!-- Sidebar Filters (unchanged) -->
                        <div class="col-lg-3">
                            <!-- [Your sidebar filter code remains unchanged] -->
                        </div>
                        <!-- Car Listings -->
                        <div class="col-lg-9">
                            <div class="row">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="col-lg-12">
                                    <div class="de-item-list mb30">
                                        <div class="d-img">
                                            <!-- Display the car's official (profile) image -->
                                            <?php if (!empty($row['display_image'])): ?>
                                                <img src="<?php echo $row['display_image']; ?>" alt="<?php echo $row['make'] . ' ' . $row['model']; ?>" class="img-fluid">
                                            <?php else: ?>
                                                <img src="images/default-car.jpg" alt="No image available" class="img-fluid">
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-info">
                                            <div class="d-text">
                                                <h4><?php echo $row['make'] . " " . $row['model']; ?></h4>
                                            </div>
                                        </div>
                                        <div class="d-price">
                                            Daily rate from <span>$<?php echo (!empty($row['rental_rate']) ? number_format($row['rental_rate'], 2) : "N/A"); ?></span>
                                            <!-- Redirect to cars-single.php when clicked -->
                                            <a class="btn-main" href="car-single.php?id=<?php echo $row['id']; ?>">Rent Now</a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo "<p>No cars available.</p>";
                                }
                                $conn->close();
                                ?>
                            </div>
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
</body>
</html>

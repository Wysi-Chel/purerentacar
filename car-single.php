<?php
include 'php/dbconfig.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}
$car_id = intval($_GET['id']);

// Fetch main car record (which now includes daily_rate)
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

// Retrieve the daily rate directly from the car record.
$daily_rate = (!empty($car['daily_rate'])) ? $car['daily_rate'] : "N/A";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Details - <?php echo $car['make'] . " " . $car['model']; ?></title>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" rel="stylesheet" />
    <style>
        /* Additional styling for the booking form if needed */
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
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
        <!-- Subheader Section End -->

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
                            <?php if (!empty($additional_images)): ?>
                                <?php foreach ($additional_images as $img): ?>
                                    <div class="item">
                                        <img src="<?php echo $img['image_path']; ?>" alt="">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
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
                            <?php if (trim(strtolower($car['runs_on_gas'])) !== 'battery'): ?>
                            <div class="d-row">
                                <span class="d-title">MPG</span>
                                <span class="d-value"><?php echo sprintf('%g', $car['mpg']); ?></span>
                            </div>
                            <?php endif; ?>
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
                            <!-- Booking Form -->
                            <form name="contactForm" id="contact_form" action="process_booking.php" method="post">
                                <h4>Booking this car</h4>
                                <div class="spacer-20"></div>
                                <!-- Hidden fields for car and user id -->
                                <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                                <input type="hidden" name="user_id" value="1"> <!-- Replace with actual user id -->
                                <div class="row">
                                    <div class="col-lg-12 mb20">
                                        <label for="PickUpDate"><i class="fa fa-home"></i> Pick Up Date:</label>
                                        <input type="date" id="PickUpDate" name="PickUpDate" class="form-control" required>
                                    </div>
                                    <div class="col-lg-12 mb20">
                                        <label for="ReturnDate"><i class="fa fa-map-marker"></i> Return Date:</label>
                                        <input type="date" id="ReturnDate" name="ReturnDate" class="form-control" required>
                                    </div>
                                </div>
                                <button type="submit" id="send_message" class="btn-main btn-fullwidth">Book Now</button>
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
                <!-- Calendar Display Section -->
                <section id="booking-calendar" style="margin-top:40px;">
                    <h2 class="text-center">Booking Calendar</h2>
                    <div id="calendar"></div>
                </section>
            </div>
        </section>
        <!-- Car Details Section End -->

        <!-- content close -->
        <a href="#" id="back-to-top"></a>
    </div>

    <!-- Javascript Files ================================================== -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          // Fetch events for this car by passing car_id to get_bookings.php
          events: 'get_bookings.php?car_id=<?php echo $car_id; ?>',
          eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
          },
          eventClick: function(info) {
            alert('Booking ID: ' + info.event.id);
          }
        });
        calendar.render();
      });
    </script>
</body>
</html>

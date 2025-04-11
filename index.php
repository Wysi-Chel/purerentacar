<?php
// Include the database configuration file
include 'php/dbconfig.php';

$sql = "SELECT c.*
        FROM cars c";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage</title>
</head>
<?php include 'head.php'; ?>

<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">

        <div id="de-preloader"></div>
        <?php include 'header.php'; ?>

        <!-- content begin -->
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
            <section id="de-carousel" class="no-top no-bottom carousel slide carousel-fade" data-mdb-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators z1000">
                    <li data-mdb-target="#de-carousel" data-mdb-slide-to="0" class="active"></li>
                    <li data-mdb-target="#de-carousel" data-mdb-slide-to="1"></li>
                    <li data-mdb-target="#de-carousel" data-mdb-slide-to="2"></li>
                </ol>

                <!-- Inner -->
                <div class="carousel-inner position-relative">

                    <!-- First item -->
                    <div class="carousel-item active jarallax" style="position: relative; overflow: hidden;">
                        <video class="jarallax-video" autoplay loop muted playsinline
                            style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                            <source src="images/slider/video.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="mask">
                            <div class="no-top no-bottom">
                                <div class="h-100 v-center">
                                    <div class="container">
                                        <div class="row gx-5 align-items-center">
                                            <div class="col-lg-6 offset-lg-3 text-center mb-sm-30">
                                                <h1 class="s3 mb-3 wow fadeInUp">Premium Cars</h1>
                                                <p class="lead wow fadeInUp" data-wow-delay=".3s" style="color:#fff;">Top-Tier Vehicles for First-Class Adventures.</p>
                                                <div class="spacer-10"></div>
                                                <a class="btn-line mb10 wow fadeInUp" data-wow-delay=".6s" href="cars.php">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second item -->
                    <div class="carousel-item jarallax">
                        <video class="jarallax-video" autoplay loop muted playsinline
                            style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                            <source src="images/slider/video2.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="mask">
                            <div class="no-top no-bottom">
                                <div class="h-100 v-center">
                                    <div class="container">
                                        <div class="row gx-5 align-items-center">
                                            <div class="col-lg-6 offset-lg-3 text-center mb-sm-30">
                                                <h1 class="s3 mb-3 wow fadeInUp">Gig Cars</h1>
                                                <p class="lead wow fadeInUp" data-wow-delay=".3s"style="color:#fff;">Perfect for Every Task. Built for Every Hustle.</p>
                                                <div class="spacer-10"></div>
                                                <a class="btn-line mb10 wow fadeInUp" data-wow-delay=".6s" href="cars.php">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third item -->
                    <div class="carousel-item jarallax">
                        <video class="jarallax-video" autoplay loop muted playsinline
                            style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                            <source src="images/slider/video3.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="mask">
                            <div class="no-top no-bottom">
                                <div class="h-100 v-center">
                                    <div class="container">
                                        <div class="row gx-5 align-items-center">
                                            <div class="col-lg-6 offset-lg-3 text-center mb-sm-30">
                                                <h1 class="s3 mb-3 wow fadeInUp">Uber, DoorDash, Lyft, Instacart</h1>
                                                <p class="lead wow fadeInUp" data-wow-delay=".3s" style="color:#fff;">Get on the Road & Start Earning Instantly.</p>
                                                <div class="spacer-10"></div>
                                                <a class="btn-line mb10 wow fadeInUp" data-wow-delay=".6s" href="cars.php">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inner -->

                <!-- Controls -->
                <a class="carousel-control-prev" href="#de-carousel" role="button" data-mdb-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#de-carousel" role="button" data-mdb-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                <div class="de-gradient-edge-bottom"></div>
            </section>


            <section id="section-cars">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 offset-lg-3 text-center">
                            <h2>Our Fleet, Your Journey</h2>
                            <p>Bringing your driving dreams to life with a refined selection of vehicles for every adventure.</p>
                            <div class="spacer-20"></div>
                        </div>
                        <div id="items-carousel" class="owl-carousel wow fadeIn">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <div class="col-lg-12">
                                        <div class="de-item mb30">
                                            <div class="d-img">
                                                <!-- Display the car's official (profile) image -->
                                                <?php if (!empty($row['display_image'])): ?>
                                                    <img src="<?php echo $row['display_image']; ?>" class="img-fluid" alt="<?php echo $row['make'] . ' ' . $row['model']; ?>">
                                                <?php else: ?>
                                                    <img src="images/default-car.jpg" class="img-fluid" alt="No image available">
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-info">
                                                <div class="d-text">
                                                    <h4><?php echo $row['make'] . " " . $row['model']; ?></h4>
                                                    <div class="d-item_like">
                                                        <i class="fa fa-heart"></i><span><?php echo isset($row['likes']) ? $row['likes'] : '0'; ?></span>
                                                    </div>
                                                    <div class="d-atr-group">
                                                        <span class="d-atr">
                                                            <img src="images/icons/1-green.svg" alt="Seaters">
                                                            <?php echo $row['seaters']; ?>
                                                        </span>
                                                        <span class="d-atr">
                                                            <img src="images/icons/3-green.svg" alt="Doors">
                                                            <?php echo $row['num_doors']; ?>
                                                        </span>
                                                            <span class="d-atr">
                                                                <img src="images/icons/gauge.png" alt="MPG">
                                                                <?php echo sprintf("%g", $row['mpg']); ?> MPG
                                                            </span>
                                                    </div>
                                                    <div class="d-price">
                                                        Daily rate from <span>$<?php echo (!empty($row['daily_rate']) ? number_format($row['daily_rate'], 2) : "N/A"); ?></span>
                                                        <a class="btn-main" href="car-single.php?id=<?php echo $row['id']; ?>">Rent Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No cars available.</p>
                            <?php endif; ?>
                            <?php $conn->close(); ?>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-label="section" class="no-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 offset-lg-3 text-center">
                            <h2>Our Features</h2>
                            <p>Discover a world of convenience, safety, and customization, paving the way for unforgettable adventures and seamless mobility solutions.</p>
                            <div class="spacer-20"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-3">
                            <div class="box-icon s2 p-small mb20 wow fadeInRight" data-wow-delay=".5s">
                                <i class="fa bg-color fa-trophy"></i>
                                <div class="d-inner">
                                    <h4>First class services</h4>
                                    Where luxury meets exceptional care, creating unforgettable moments and exceeding your every expectation.
                                </div>
                            </div>
                            <div class="box-icon s2 p-small mb20 wow fadeInL fadeInRight" data-wow-delay=".75s">
                                <i class="fa bg-color fa-road"></i>
                                <div class="d-inner">
                                    <h4>24/7 road assistance</h4>
                                    Reliable support when you need it most, keeping you on the move with confidence and peace of mind.
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <img src="images/misc/car-2.png" alt="" class="img-fluid wow fadeInUp">
                        </div>

                        <div class="col-lg-3">
                            <div class="box-icon s2 d-invert p-small mb20 wow fadeInL fadeInLeft" data-wow-delay="1s">
                                <i class="fa bg-color fa-tag"></i>
                                <div class="d-inner">
                                    <h4>Quality at Minimum Expense</h4>
                                    Unlocking affordable brilliance with elevating quality while minimizing costs for maximum value.
                                </div>
                            </div>
                            <div class="box-icon s2 d-invert p-small mb20 wow fadeInL fadeInLeft" data-wow-delay="1.25s">
                                <i class="fa bg-color fa-map-pin"></i>
                                <div class="d-inner">
                                    <h4>Free Pick-Up & Drop-Off</h4>
                                    Enjoy free pickup and drop-off services, adding an extra layer of ease to your car rental experience.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="text-light jarallax" aria-label="section" >
                <img src="images/background/3.jpg" alt="" class="jarallax-img">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <h1>Let Your Adventure Begin</h1>
                            <div class="spacer-20"></div>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-trophy de-icon mb20"></i>
                            <h4>First Class Services</h4>
                            <p>Where luxury meets exceptional care, creating unforgettable moments and exceeding your every expectation.</p>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-road de-icon mb20"></i>
                            <h4>24/7 road assistance</h4>
                            <p>Reliable support when you need it most, keeping you on the move with confidence and peace of mind.</p>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-map-pin de-icon mb20"></i>
                            <h4>Free Pick-Up & Drop-Off</h4>
                            <p>Enjoy free pickup and drop-off services, adding an extra layer of ease to your car rental experience.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-faq">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <h2>Have Any Questions?</h2>
                            <div class="spacer-20"></div>
                        </div>
                    </div>
                    <div class="row g-custom-x">
                        <div class="col-md-6 wow fadeInUp">
                            <div class="accordion secondary">
                                <div class="accordion-section">
                                    <div class="accordion-section-title" data-tab="#accordion-1">
                                        How do I get started with Car Rental?
                                    </div>
                                    <div class="accordion-section-content" id="accordion-1">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                    <div class="accordion-section-title" data-tab="#accordion-2">
                                        Can I rent a car with a debit card??
                                    </div>
                                    <div class="accordion-section-content" id="accordion-2">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                    <div class="accordion-section-title" data-tab="#accordion-3">
                                        What kind of Car Rental do I need?
                                    </div>
                                    <div class="accordion-section-content" id="accordion-3">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 wow fadeInUp">
                            <div class="accordion secondary">
                                <div class="accordion-section">
                                    <div class="accordion-section-title" data-tab="#accordion-b-4">
                                        What is a rental car security deposit?
                                    </div>
                                    <div class="accordion-section-content" id="accordion-b-4">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                    <div class="accordion-section-title" data-tab="#accordion-b-5">
                                        Can I cancel or modify my reservation?
                                    </div>
                                    <div class="accordion-section-content" id="accordion-b-5">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                    <div class="accordion-section-title" data-tab="#accordion-b-6">
                                        Is it possible to extend my rental period?
                                    </div>
                                    <div class="accordion-section-content" id="accordion-b-6">
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        </section>
    </div>

    <!-- content close -->
    <a href="#" id="back-to-top"></a>
    <?php include 'footer.php'; ?>
    </div>


    <!-- Javascript Files
    ================================================== -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgiM7ogCAA2Y5pgSk2KXZfxF5S_1jsptA&amp;libraries=places&amp;callback=initPlaces" async defer></script>

</body>

</html>
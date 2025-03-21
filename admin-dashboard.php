<?php
// admin_dashboard.php
include 'php/dbconfig.php'; // Adjust the path if necessary

// Updated query: use the official image from the cars table and fetch each day's rental rate
$sql = "SELECT 
            c.id, 
            c.make, 
            c.model, 
            c.year, 
            c.category, 
            c.status,
            c.display_image AS image,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 1) AS rate1,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 2) AS rate2,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 3) AS rate3,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 4) AS rate4,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 5) AS rate5,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 6) AS rate6,
            (SELECT rate FROM car_rental_rates WHERE car_id = c.id AND rental_day = 7) AS rate7
        FROM cars c";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Admin - Dashboard</title></head>
<?php include 'head.php';?>
<body onload="initialize()" class="dark-scheme">
<div id="wrapper">
    <!-- header begin -->
    <header class="transparent has-topbar">
        <div id="topbar" class="topbar-dark text-light"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex sm-pt10">
                        <div class="de-flex-col">
                            <div id="logo">
                                <a href="../index.php">
                                    <img class="logo-1" src="images/logo-purerental.png" alt="">
                                    <img class="logo-2" src="images/logo-purerental.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="de-flex-col header-col-mid">
                            <ul id="mainmenu">
                                <li><a class="menu-item" href="admin-dashboard.php">Dashboard</a></li>
                                <li><a class="menu-item" href="admin-cars.php">Cars</a></li>
                                <li><a class="menu-item" href="admin-employees.php">Employees</a></li>
                                <li><a class="menu-item" href="admin-users.php">Users</a></li>
                            </ul>
                        </div>
                        <div class="de-flex-col">
                            <div class="mainmenu">
                                <a href="logout.php" class="btn-main">Logout</a>
                                <span id="menu-btn"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header close -->

    <!-- content begin -->
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>
        <section id="admin-dashboard" class="container-fluid" style="padding: 120px 50px;">
            <!-- Overview Cards (unchanged) -->
            <div class="row">
                <div class="col-lg-3 mb30">
                    <div class="card p-4 rounded-5">
                        <div class="symbol mb40">
                            <i class="fa fa-car fa-2x"></i>
                        </div>
                        <h5 class="mb0">Total Cars</h5>
                        <span class="h1">0</span>
                    </div>
                </div>
                <!-- Additional overview cards can be added here -->
            </div>

            <!-- Cars Management Section -->
            <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Cars</h4>
                    <a href="admin/add_car.php" class="btn-main btn-small">List New Car</a>
                </div>
                <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Display Image</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Category</th>
                            <th>1-Day Rate</th>
                            <th>2-Day Rate</th>
                            <th>3-Day Rate</th>
                            <th>4-Day Rate</th>
                            <th>5-Day Rate</th>
                            <th>6-Day Rate</th>
                            <th>7-Day Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>";
                            if (!empty($row['image'])) {
                                // Use the display_image stored in the cars table.
                                echo "<img src='" . $row['image'] . "' width='50' class='car-thumb' alt='" . $row['make'] . " " . $row['model'] . "'>";
                            } else {
                                echo "No image";
                            }
                            echo "</td>";
                            echo "<td>" . $row['make'] . "</td>";
                            echo "<td>" . $row['model'] . "</td>";
                            echo "<td>" . $row['year'] . "</td>";
                            echo "<td>" . $row['category'] . "</td>";
                            
                            // Format each rate with a $ and two decimal places if available, otherwise display "N/A"
                            for ($d = 1; $d <= 7; $d++) {
                                $rate = $row["rate$d"];
                                echo "<td>" . (!empty($rate) ? "$" . number_format($rate, 2) : "N/A") . "</td>";
                            }
                            
                            if (strtolower($row['status']) === 'available' || $row['status'] == '1') {
                                echo "<td><span class='badge bg-success'>Available</span></td>";
                            } else {
                                echo "<td><span class='badge bg-danger'>Unavailable</span></td>";
                            }
                            
                            echo "<td>
                                    <a href='admin-edit-car.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Edit</a>
                                    <a href='admin-delete-car.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>No cars found.</td></tr>";
                    }
                    $conn->close();
                    ?>  
                    </tbody>
                </table>
            </div>
            </div>

            <!-- Employees and Users Management Sections (unchanged) -->
            <!-- <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Employees</h4>
                    <a href="admin-add-employee.php" class="btn-main btn-small">Add New Employee</a>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>555-1234</td>
                            <td>Manager</td>
                            <td>
                                <a href="admin-edit-employee.php?id=1" class="btn btn-sm btn-info">Edit</a>
                                <a href="admin-delete-employee.php?id=1" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

            <!-- <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Users</h4>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td>1</td>
                            <td>Alice Johnson</td>
                            <td>alice.johnson@example.com</td>
                            <td>555-9876</td>
                            <td>2025-01-15</td>
                            <td>
                                <a href="admin-edit-user.php?id=1" class="btn btn-sm btn-info">Edit</a>
                                <a href="admin-delete-user.php?id=1" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
        </section>
    </div>
    <!-- content close -->

    <a href="#" id="back-to-top"></a>
</div>

<!-- Javascript Files -->
<script src="../js/plugins.js"></script>
<script src="../js/designesia.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&amp;libraries=places&amp;callback=initPlaces" async defer></script>
</body>
</html>

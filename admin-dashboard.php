<?php
// admin_dashboard.php
include 'php/dbconfig.php'; // Adjust the path if necessary

// Updated query: use the official image from the cars table, fetch new columns, and retrieve each day's rental rate
$sql = "SELECT 
            c.id, 
            c.make, 
            c.model, 
            c.year, 
            c.category, 
            c.status,
            c.display_image AS image,
            c.seaters,
            c.num_doors,
            c.runs_on_gas,
            c.mpg,
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
<head>
    <title>Admin - Dashboard</title>
    
    <?php include 'head.php';?>
</head>
<body class="dark-scheme">
    <div id="wrapper">
    <header class="transparent has-topbar">
        <div id="topbar" class="topbar-dark text-light"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex sm-pt10">
                        <div class="de-flex-col">
                            <div id="logo">
                                <a href="index.php">
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
        <div id="content" class="no-bottom no-top">
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
                                    <th>MPG</th>
                                    <th>Day Rate</th>
                                    <th>Weekly Rate</th>
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
                                    echo "<td>" . $row['mpg'] . "</td>";
                                    
                                    // Format each rate with a $ and two decimal places if available, otherwise display "N/A"
                                    for ($d = 1; $d <= 2; $d++) {
                                        $rate = $row["rate$d"];
                                        echo "<td>" . (!empty($rate) ? "$" . number_format($rate, 2) : "N/A") . "</td>";
                                    }
                                    
                                    if (strtolower($row['status']) === 'available' || $row['status'] == '1') {
                                        echo "<td><span class='badge bg-success'>Available</span></td>";
                                    } else {
                                        echo "<td><span class='badge bg-danger'>Unavailable</span></td>";
                                    }
                                    
                                    // Actions column: Edit button triggers modal, Delete button as before.
                                    echo "<td>
                                            <button type='button' class='btn-main edit-btn' data-id='" . $row['id'] . "' data-bs-toggle='modal' data-bs-target='#editCarModal'><i class='fa fa-pencil'></i></button>
                                            <button type='button' class='btn btn-sm btn-danger' data-id='" . $row['id'] . "' data-bs-toggle='modal' data-bs-target='#deleteCarModal'><i class='fa fa-trash'></i></button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='19'>No cars found.</td></tr>";
                            }
                            $conn->close();
                            ?>  
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Additional sections can be added here -->
            </section>
        </div>
        <!-- content close -->

        <a href="#" id="back-to-top"></a>
    </div>

    <!-- Modal for editing car details -->
    <div class="modal fade" id="editCarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCarModalLabel">Edit Car Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <iframe id="editCarIframe" src="" style="width:100%; height:600px; border:none;"></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- Javascript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
      // When the editCarModal is shown, update the iframe's src to load the admin-edit-car page for the selected car.
      var editCarModal = document.getElementById('editCarModal');
      editCarModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget; // Button that triggered the modal
          var carId = button.getAttribute('data-id'); // Get the car id from data attribute
          var iframe = document.getElementById('editCarIframe');
          iframe.src = "admin-edit-car.php?id=" + carId;
      });
    </script>
</body>
</html>
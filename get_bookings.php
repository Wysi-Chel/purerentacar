<?php
include 'php/dbconfig.php';

header('Content-Type: application/json');

$sql = "SELECT id, car_id, start_date, end_date FROM bookings";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // FullCalendar treats the end date as exclusive, so add one day if needed.
        $events[] = [
            'id'    => $row['id'],
            'title' => "Booking #{$row['id']}",
            'start' => $row['start_date'],
            'end'   => date("Y-m-d", strtotime($row['end_date'] . ' +1 day'))
        ];
    }
}

echo json_encode($events);
$conn->close();
?>

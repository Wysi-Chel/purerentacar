-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 08:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `purerentaldb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateBooking` (IN `p_customer_id` INT, IN `p_car_id` INT, IN `p_start_date` DATE, IN `p_end_date` DATE)   BEGIN
    DECLARE v_daily_rate DECIMAL(10,2);
    DECLARE v_num_days INT;
    DECLARE v_total_cost DECIMAL(10,2);
    DECLARE v_conflict INT;

    -- Check for booking conflicts for the selected car and period
    SELECT COUNT(*) INTO v_conflict
    FROM bookings
    WHERE car_id = p_car_id
      AND (p_start_date < end_date AND p_end_date > start_date)
      AND status IN ('pending', 'confirmed', 'completed');

    IF v_conflict > 0 THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Car is already booked for the selected period';
    END IF;

    -- Get the car's daily rental rate
    SELECT rental_rate INTO v_daily_rate 
    FROM cars 
    WHERE id = p_car_id;

    -- Calculate the number of rental days (inclusive)
    SET v_num_days = DATEDIFF(p_end_date, p_start_date) + 1;
    SET v_total_cost = v_daily_rate * v_num_days;

    -- Insert the booking record and set its status to confirmed
    INSERT INTO bookings (customer_id, car_id, booking_date, start_date, end_date, total_cost, status)
    VALUES (p_customer_id, p_car_id, CURDATE(), p_start_date, p_end_date, v_total_cost, 'confirmed');
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `license_plate` varchar(20) DEFAULT NULL,
  `rental_rate` decimal(10,2) NOT NULL,
  `status` enum('available','rented','maintenance') DEFAULT 'available',
  `location_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `make`, `model`, `year`, `category`, `license_plate`, `rental_rate`, `status`, `location_id`, `created_at`, `updated_at`) VALUES
(1, 'McLaren', '650S', 2023, 'Exotic', 'MC650S-01', 1200.00, 'available', 1, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Lamborghini', 'Huracan', 2022, 'Exotic', 'LHN-2022', 1500.00, 'available', 1, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(3, 'Rolls Royce', 'Ghost', 2023, 'Luxury', 'RRG-1234', 2000.00, 'available', 2, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(4, 'Mustang', 'Shelby GT350', 2021, 'Sports', 'MSGT350', 800.00, 'available', 1, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(5, 'Nissan', 'GTR', 2023, 'Sports', 'NISGTR', 950.00, 'maintenance', 2, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(6, 'Tesla', 'Model X', 2024, 'Electric', 'TSLX-2024', 1100.00, 'available', 1, '2025-03-18 17:40:49', '2025-03-18 17:40:49');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '555-1234', '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '555-5678', '2025-03-18 17:40:49', '2025-03-18 17:40:49');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `address`, `city`, `state`, `zip`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Raleigh Office', '123 Main St', 'Raleigh', 'NC', '27601', '(984) 327-7870', '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Surf City Office', '456 Beach Ave', 'Surf City', 'NC', '28455', '(984) 327-7871', '2025-03-18 17:40:49', '2025-03-18 17:40:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

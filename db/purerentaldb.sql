-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 07:15 PM
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
  `make` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Available',
  `display_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `make`, `model`, `year`, `category`, `status`, `display_image`) VALUES
(4, 'bmw', 'm6', 2005, 'available', 'Available', 'images/cars/67db1aad584e4_bmw-m5.jpg'),
(5, 'bmw', 'm6', 2010, 'available', 'Available', 'images/cars/67db1b6fa15a3_bmw-m5.jpg'),
(6, 'ford', 'raptor', 2020, 'Sport', 'Available', 'images/cars/67db3cd016a57_ford-raptor.jpg'),
(7, 'jeep', 'renegade', 2020, 'jeep', 'Available', 'images/cars/67db3cfd1ba7e_jeep-renegade.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `car_images`
--

CREATE TABLE `car_images` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `image_path`) VALUES
(32, 4, 'images/cars/67db1aad5944f_hyundai-staria.jpg'),
(33, 4, 'images/cars/67db1aad59cf1_jeep-renegade.jpg'),
(34, 4, 'images/cars/67db1aad5a91f_lexus.jpg'),
(35, 4, 'images/cars/67db1aad5b7fa_range-rover.jpg'),
(36, 4, 'images/cars/67db1aad5c34b_toyota-rav.jpg'),
(37, 4, 'images/cars/67db1aad5ca2a_vw-polo.jpg'),
(38, 5, 'images/cars/67db1b6fa291c_chevrolet-camaro.jpg'),
(39, 5, 'images/cars/67db1b6fa3277_ferrari-enzo.jpg'),
(40, 5, 'images/cars/67db1b6fa3e6a_ford-raptor.jpg'),
(41, 5, 'images/cars/67db1b6fa45be_hyundai-staria.jpg'),
(42, 5, 'images/cars/67db1b6fa529c_jeep-renegade.jpg'),
(43, 5, 'images/cars/67db1b6fa5cd1_toyota-rav.jpg'),
(44, 5, 'images/cars/67db1b6fa6833_vw-polo.jpg'),
(45, 6, 'images/cars/67db3cd01c15f_range-rover.jpg'),
(46, 6, 'images/cars/67db3cd01ce72_toyota-rav.jpg'),
(47, 6, 'images/cars/67db3cd01dca4_vw-polo.jpg'),
(48, 7, 'images/cars/67db3cfd1cbdf_bmw-m5.jpg'),
(49, 7, 'images/cars/67db3cfd1da31_chevrolet-camaro.jpg'),
(50, 7, 'images/cars/67db3cfd1e61d_ferrari-enzo.jpg'),
(51, 7, 'images/cars/67db3cfd1ee21_ford-raptor.jpg'),
(52, 7, 'images/cars/67db3cfd1f8e7_range-rover.jpg'),
(53, 7, 'images/cars/67db3cfd1ffeb_toyota-rav.jpg'),
(54, 7, 'images/cars/67db3cfd20efc_vw-polo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `car_rental_rates`
--

CREATE TABLE `car_rental_rates` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rental_day` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_rental_rates`
--

INSERT INTO `car_rental_rates` (`id`, `car_id`, `rental_day`, `rate`) VALUES
(51, 4, 1, 2.00),
(52, 4, 2, 3.00),
(53, 4, 3, 4.00),
(54, 4, 4, 5.00),
(55, 4, 5, 6.00),
(56, 4, 6, 7.00),
(57, 4, 7, 8.00),
(58, 5, 1, 5.00),
(59, 5, 2, 6.00),
(60, 5, 3, 7.00),
(61, 5, 4, 8.00),
(62, 5, 5, 9.00),
(63, 5, 6, 0.00),
(64, 5, 7, 8.00),
(65, 6, 1, 800.00),
(66, 6, 2, 1600.00),
(67, 6, 3, 4234729.00),
(68, 6, 4, 934279.00),
(69, 6, 5, 42938.00),
(70, 6, 6, 9234.00),
(71, 6, 7, 78924.00),
(72, 7, 1, 234.00),
(73, 7, 2, 423234.00),
(74, 7, 3, 234.00),
(75, 7, 4, 2.00),
(76, 7, 5, 42342.00),
(77, 7, 6, 24234.00),
(78, 7, 7, 243423.00);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

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
-- Constraints for table `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  ADD CONSTRAINT `car_rental_rates_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

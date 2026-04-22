-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2026 at 06:23 PM
-- Server version: 10.6.25-MariaDB
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bestwest_newbest`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `created_at`, `last_login`, `is_active`) VALUES
(2, 'adminuser1', 'admin@mail.com', '$2y$10$fCRVIL.JBbqFYsjSBWhcVeH2a5wqc7XSngj/LdixXZsPanpm1.Uh6', '2026-04-19 14:28:07', '2026-04-19 15:45:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `filename`, `original_name`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `uploaded_at`) VALUES
(1, 'img_69e5012c888538.99289342.jpg', 'TheLussoAbjdrone copy.jpg', 'assets/uploads/img_69e5012c888538.99289342.jpg', 'image/jpeg', 259568, 2, '2026-04-19 16:22:04');

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `content_type` enum('text','html','image','json') DEFAULT 'text',
  `content` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`id`, `page`, `section_key`, `content_type`, `content`, `updated_at`) VALUES
(1, 'index', 'hero_trust_badge', 'text', 'Travelers Choice 2026', '2026-04-19 02:28:18'),
(2, 'index', 'hero_show_stars', 'text', '1', '2026-04-19 02:28:18'),
(3, 'index', 'hero_title', 'html', 'Luxury on the Shores<br/><span class=\"italic text-surface\">of Oxbow Lake.</span>', '2026-04-19 02:28:18'),
(4, 'index', 'hero_subtitle', 'text', 'An international standard of hospitality in the heart of Bayelsa.', '2026-04-19 02:28:18'),
(5, 'index', 'hero_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 02:28:18'),
(6, 'index', 'home_booking_guarantee_line', 'text', 'Best Rate Guarantee', '2026-04-19 02:28:18'),
(7, 'index', 'home_philosophy_kicker', 'text', 'Our Heritage', '2026-04-19 02:28:18'),
(8, 'index', 'home_philosophy_title_html', 'html', 'Where Heritage Meets Hospitality', '2026-04-19 02:28:18'),
(9, 'index', 'home_philosophy_body', 'html', '<p>Nestled in the heart of Bayelsa State, our hotel blends rich heritage with modern hospitality. As part of the Best Western <span class=\"text-brand-red font-semibold\">Plus</span> collection, we uphold a legacy of excellence while delivering a distinctively Nigerian warmth.</p>', '2026-04-19 02:28:18'),
(10, 'index', 'home_philosophy_link_text', 'text', 'Explore the Story', '2026-04-19 02:28:18'),
(11, 'index', 'home_philosophy_link_href', 'text', '/about', '2026-04-19 02:28:18'),
(12, 'index', 'home_philosophy_main_img', 'image', 'assets/images/placeholders/placeholder-detail.svg', '2026-04-19 02:28:18'),
(13, 'index', 'home_rooms_kicker', 'text', 'Exquisite suites designed for the refined traveler', '2026-04-19 02:28:18'),
(14, 'index', 'home_rooms_title', 'text', 'Sanctuaries of Calm', '2026-04-19 02:28:18'),
(15, 'index', 'home_rooms_view_all_href', 'text', '/rooms', '2026-04-19 02:28:18'),
(16, 'index', 'home_dining_kicker', 'text', 'Gastronomy', '2026-04-19 02:28:18'),
(17, 'index', 'home_dining_heading_html', 'html', '<span class=\"italic\">Culinary Excellence</span>', '2026-04-19 02:28:18'),
(18, 'index', 'home_dining_venue1_title', 'text', 'Mama Oxbow', '2026-04-19 02:28:18'),
(19, 'index', 'home_dining_venue1_body', 'text', 'Authentic local delicacies crafted with a modern touch, overlooking the gentle ripples of the lake.', '2026-04-19 02:28:18'),
(20, 'index', 'home_dining_venue2_title', 'text', 'Red Lotus', '2026-04-19 02:28:18'),
(21, 'index', 'home_dining_venue2_body', 'text', 'An Asian-fusion journey where tradition meets contemporary culinary innovation.', '2026-04-19 02:28:18'),
(22, 'index', 'home_dining_image_top', 'image', 'assets/images/placeholders/placeholder-gallery.svg', '2026-04-19 02:28:18'),
(23, 'index', 'home_dining_image_bottom', 'image', 'assets/images/placeholders/placeholder-detail.svg', '2026-04-19 02:28:18'),
(24, 'index', 'home_facilities_title', 'text', 'Leisure & Wellness', '2026-04-19 02:28:18'),
(25, 'index', 'home_facilities_blurb', 'text', 'Designed to rejuvenate your senses and enhance your productivity.', '2026-04-19 02:28:18'),
(26, 'index', 'home_facilities_bento_json', 'json', '[{\"image\":\"assets/images/placeholders/placeholder-hero.svg\",\"title\":\"The Infinity Pool\",\"subtitle\":\"Open Daily • 6AM - 10PM\"},{\"image\":\"assets/images/placeholders/placeholder-detail.svg\",\"title\":\"Wellness Spa\",\"subtitle\":\"\"},{\"image\":\"assets/images/placeholders/placeholder-gallery.svg\",\"title\":\"Elite Gym\",\"subtitle\":\"\"},{\"image\":\"assets/images/placeholders/placeholder-room.svg\",\"title\":\"Akassa Hall\",\"subtitle\":\"Business & Events\"}]', '2026-04-19 02:28:18'),
(27, 'index', 'home_location_title', 'text', 'The Serenity of Oxbow Lake', '2026-04-19 02:28:18'),
(28, 'index', 'home_location_body', 'text', 'A peaceful retreat away from the city''s pulse, offering breathtaking views and tranquil mornings.', '2026-04-19 02:28:18'),
(29, 'index', 'home_location_bullets_json', 'json', '[\"5 min to Government House\",\"15 min to Airport\",\"Oxbow Lake waterfront\"]', '2026-04-19 02:28:18'),
(30, 'index', 'home_location_address', 'text', 'Oxbow Lake Rd, Yenagoa, Bayelsa', '2026-04-19 02:28:18'),
(31, 'index', 'home_location_map_image', 'image', 'assets/images/placeholders/placeholder-gallery.svg', '2026-04-19 02:28:18'),
(32, 'index', 'hero_bg_slides', 'json', '[]', '2026-04-19 02:28:18'),
(33, 'index', 'hero_youtube_url', 'text', '', '2026-04-19 02:28:18'),
(34, 'index', 'booking_widget_html', 'html', '', '2026-04-19 02:28:18'),
(35, 'about', 'page_title', 'text', 'About', '2026-04-19 16:22:14'),
(36, 'about', 'hero_established', 'text', 'Established 2024', '2026-04-19 16:22:14'),
(37, 'about', 'hero_title_html', 'html', 'Lorem Ipsum <br/><span class=\"font-bold italic text-primary/90 site-hero-accent-text\">Dolor Sit</span>', '2026-04-19 16:22:14'),
(38, 'about', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:14'),
(39, 'about', 'hero_bg', 'image', 'assets/uploads/img_69e5012c888538.99289342.jpg', '2026-04-19 16:22:14'),
(40, 'about', 'story_title_html', 'html', 'Lorem Ipsum <br/><span class=\"font-semibold text-primary\">Dolor Sit</span>', '2026-04-19 16:22:14'),
(41, 'about', 'story_p1', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:14'),
(42, 'about', 'story_p2', 'text', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '2026-04-19 16:22:14'),
(43, 'about', 'story_image', 'image', 'assets/images/placeholders/placeholder-detail.svg', '2026-04-19 16:22:14'),
(44, 'about', 'story_quote', 'text', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\"', '2026-04-19 16:22:14'),
(45, 'about', 'values_kicker', 'text', 'Lorem Ipsum', '2026-04-19 16:22:15'),
(46, 'about', 'values_title', 'text', 'Dolor Sit Amet', '2026-04-19 16:22:15'),
(47, 'about', 'values_image', 'image', 'assets/images/placeholders/placeholder-gallery.svg', '2026-04-19 16:22:15'),
(48, 'about', 'values_card_icon', 'text', 'spa', '2026-04-19 16:22:15'),
(49, 'about', 'values_card_title', 'text', 'Lorem Ipsum Dolor', '2026-04-19 16:22:15'),
(50, 'about', 'values_card_body', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:15'),
(51, 'about', 'values_card_link', 'text', 'Learn More', '2026-04-19 16:22:15'),
(52, 'about', 'values_card_link_href', 'text', '/amenities', '2026-04-19 16:22:15'),
(53, 'about', 'journey_title_html', 'html', 'Lorem <span class=\"font-bold italic text-primary\">Ipsum</span>', '2026-04-19 16:22:15'),
(54, 'about', 'journey_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:15'),
(55, 'about', 'team_kicker', 'text', 'Gallery', '2026-04-19 16:22:15'),
(56, 'about', 'team_heading', 'text', 'Lorem Ipsum', '2026-04-19 16:22:15'),
(57, 'about', 'team_intro', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:15'),
(58, 'about', 'parallax_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 16:22:15'),
(59, 'about', 'parallax_quote', 'text', '\"Lorem ipsum dolor sit amet.\"', '2026-04-19 16:22:15'),
(60, 'about', 'cta_title', 'text', 'Lorem Ipsum Dolor', '2026-04-19 16:22:15'),
(61, 'about', 'cta_body', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 16:22:15'),
(62, 'about', 'cta_btn1', 'text', 'Explore Rooms', '2026-04-19 16:22:15'),
(63, 'about', 'cta_btn1_href', 'text', '/rooms', '2026-04-19 16:22:15'),
(64, 'about', 'cta_btn2', 'text', 'Contact Us', '2026-04-19 16:22:16'),
(65, 'about', 'cta_btn2_href', 'text', '/contact', '2026-04-19 16:22:16'),
(66, 'contact', 'page_title', 'text', 'Contact Us', '2026-04-19 02:28:18'),
(67, 'contact', 'intro_kicker', 'text', 'Lorem Ipsum', '2026-04-19 02:28:18'),
(68, 'contact', 'intro_title', 'text', 'Get in Touch', '2026-04-19 02:28:18'),
(69, 'contact', 'intro_body', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 02:28:18'),
(70, 'contact', 'address_html', 'html', '123 Lorem Avenue<br/>\nIpsum City<br/>\nCountry', '2026-04-19 02:28:18'),
(71, 'contact', 'map_address', 'text', '123 Lorem Avenue, Ipsum City, Country', '2026-04-19 02:28:18'),
(72, 'contact', 'map_embed_url', 'text', '', '2026-04-19 02:28:18'),
(73, 'contact', 'directions_href', 'text', '#', '2026-04-19 02:28:18'),
(74, 'contact', 'concierge_phone', 'text', '+1 555 010 0000', '2026-04-19 02:28:18'),
(75, 'contact', 'inquiries_email', 'text', 'hello@example.com', '2026-04-19 02:28:18'),
(76, 'contact', 'map_image', 'image', 'assets/images/placeholders/placeholder-gallery.svg', '2026-04-19 02:28:18'),
(77, 'contact', 'map_pin_label', 'text', 'Lorem Location', '2026-04-19 02:28:18'),
(78, 'hotel-policy', 'page_title', 'text', 'Hotel Policy', '2026-04-19 02:28:18'),
(79, 'hotel-policy', 'hero_kicker', 'text', 'Policy', '2026-04-19 02:28:18'),
(80, 'hotel-policy', 'hero_title', 'text', 'Hotel Policy', '2026-04-19 02:28:18'),
(81, 'hotel-policy', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '2026-04-19 02:28:18'),
(82, 'hotel-policy', 'last_updated', 'text', 'Last updated: April 18, 2026', '2026-04-19 02:28:18'),
(83, 'hotel-policy', 'body_html', 'html', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><h2>Lorem Ipsum</h2><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>', '2026-04-19 02:28:18'),
(84, 'privacy-policy', 'page_title', 'text', 'Privacy Policy', '2026-04-19 02:28:18'),
(85, 'privacy-policy', 'hero_kicker', 'text', 'Legal', '2026-04-19 02:28:18'),
(86, 'privacy-policy', 'hero_title', 'text', 'Privacy Policy', '2026-04-19 02:28:18'),
(87, 'privacy-policy', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '2026-04-19 02:28:18'),
(88, 'privacy-policy', 'last_updated', 'text', 'Last updated: April 18, 2026', '2026-04-19 02:28:18'),
(89, 'privacy-policy', 'body_html', 'html', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><h2>Lorem Ipsum</h2><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>', '2026-04-19 02:28:18'),
(90, 'terms-and-conditions', 'page_title', 'text', 'Terms & Conditions', '2026-04-19 02:28:18'),
(91, 'terms-and-conditions', 'hero_kicker', 'text', 'Legal', '2026-04-19 02:28:18'),
(92, 'terms-and-conditions', 'hero_title', 'text', 'Terms & Conditions', '2026-04-19 02:28:18'),
(93, 'terms-and-conditions', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '2026-04-19 02:28:18'),
(94, 'terms-and-conditions', 'last_updated', 'text', 'Last updated: April 18, 2026', '2026-04-19 02:28:18'),
(95, 'terms-and-conditions', 'body_html', 'html', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><h2>Lorem Ipsum</h2><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>', '2026-04-19 02:28:18'),
(96, 'gallery', 'page_title', 'text', 'Gallery', '2026-04-19 02:28:18'),
(97, 'gallery', 'hero_kicker', 'text', 'Lorem Ipsum', '2026-04-19 02:28:18'),
(98, 'gallery', 'hero_title_html', 'html', 'VISUAL <span class=\"font-bold italic text-primary\">LOREM</span>', '2026-04-19 02:28:18'),
(99, 'gallery', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 02:28:18'),
(100, 'gallery', 'hero_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 02:28:18'),
(101, 'dining', 'page_title', 'text', 'Dining', '2026-04-19 02:28:18'),
(102, 'dining', 'hero_kicker', 'text', 'Dining', '2026-04-19 02:28:18'),
(103, 'dining', 'hero_title_html', 'html', 'Lorem Ipsum <br/><i class=\"font-light opacity-90\">Dolor Sit</i>', '2026-04-19 02:28:18'),
(104, 'dining', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 02:28:18'),
(105, 'dining', 'hero_hours', 'text', 'Open Daily: 00:00 - 00:00', '2026-04-19 02:28:18'),
(106, 'dining', 'hero_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 02:28:18'),
(107, 'dining', 'intro_vertical', 'text', 'Lorem Menus', '2026-04-19 02:28:18'),
(108, 'dining', 'chef_title_html', 'html', 'Lorem <br/> <span class=\"text-primary italic\">Ipsum</span>', '2026-04-19 02:28:18'),
(109, 'dining', 'chef_body_html', 'html', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>', '2026-04-19 02:28:18'),
(110, 'dining', 'chef_signature', 'text', 'Lorem Ipsum', '2026-04-19 02:28:18'),
(111, 'dining', 'chef_main_img', 'image', 'assets/images/placeholders/placeholder-detail.svg', '2026-04-19 02:28:18'),
(112, 'dining', 'chef_circle_img', 'image', 'assets/images/placeholders/placeholder-gallery.svg', '2026-04-19 02:28:18'),
(113, 'dining', 'visual_title', 'text', 'Lorem Gallery', '2026-04-19 02:28:18'),
(114, 'dining', 'visual_link_href', 'text', '/gallery', '2026-04-19 02:28:18'),
(115, 'dining', 'menu_kicker', 'text', 'Lorem Menu', '2026-04-19 02:28:18'),
(116, 'dining', 'menu_heading', 'text', 'Lorem Ipsum', '2026-04-19 02:28:18'),
(117, 'dining', 'menu_quote', 'text', '\"Lorem ipsum dolor sit amet.\"', '2026-04-19 02:28:18'),
(118, 'dining', 'cta_bg', 'image', 'assets/images/placeholders/placeholder-room.svg', '2026-04-19 02:28:18'),
(119, 'dining', 'cta_title', 'text', 'Lorem Reservation', '2026-04-19 02:28:18'),
(120, 'dining', 'cta_body', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 02:28:18'),
(121, 'dining', 'cta_btn1', 'text', 'Contact Us', '2026-04-19 02:28:18'),
(122, 'dining', 'cta_btn1_href', 'text', '/contact', '2026-04-19 02:28:18'),
(123, 'dining', 'cta_btn2', 'text', 'Explore Rooms', '2026-04-19 02:28:18'),
(124, 'dining', 'sticky_kicker', 'text', 'Lorem', '2026-04-19 02:28:18'),
(125, 'dining', 'sticky_subtitle', 'text', 'Ipsum', '2026-04-19 02:28:18'),
(126, 'rooms', 'page_title', 'text', 'Rooms & Suites', '2026-04-19 02:28:18'),
(127, 'rooms', 'hero_title', 'html', 'Lorem Spaces <br/><span class=\"font-bold italic font-serif\">Dolor Sit</span>', '2026-04-19 02:28:18'),
(128, 'rooms', 'hero_subtitle', 'text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2026-04-19 02:28:18'),
(129, 'rooms', 'hero_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 02:28:18'),
(130, 'amenities', 'page_title', 'text', 'Amenities', '2026-04-19 02:28:18'),
(131, 'about', 'timeline_json', 'json', '[{\"year\":\"2021\",\"kind\":\"circle\",\"title\":\"Lorem Ipsum\",\"body\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\"},{\"year\":\"2022\",\"kind\":\"dot\",\"title\":\"Dolor Sit\",\"body\":\"Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\"},{\"year\":\"2023\",\"kind\":\"circle\",\"title\":\"Amet Elit\",\"body\":\"Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.\"},{\"year\":\"2024\",\"kind\":\"dot_primary\",\"title\":\"Tempor Incididunt\",\"body\":\"Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"}]', '2026-04-19 16:22:15'),
(132, 'about', 'team_json', 'json', '[\"assets/images/placeholders/placeholder-gallery.svg\",\"assets/images/placeholders/placeholder-detail.svg\",\"assets/images/placeholders/placeholder-room.svg\"]', '2026-04-19 16:22:15'),
(133, 'gallery', 'items_json', 'json', '[\"assets/images/placeholders/placeholder-gallery.svg\",\"assets/images/placeholders/placeholder-hero.svg\",\"assets/images/placeholders/placeholder-detail.svg\",\"assets/images/placeholders/placeholder-room.svg\",\"assets/images/placeholders/placeholder-gallery.svg\",\"assets/images/placeholders/placeholder-detail.svg\"]', '2026-04-19 02:28:18'),
(134, 'dining', 'masonry_json', 'json', '[{\"src\":\"assets/images/placeholders/placeholder-gallery.svg\",\"tag\":\"Lorem\",\"caption\":\"Lorem Ipsum\"},{\"src\":\"assets/images/placeholders/placeholder-detail.svg\",\"tag\":\"Dolor\",\"caption\":\"Dolor Sit\"},{\"src\":\"assets/images/placeholders/placeholder-room.svg\",\"tag\":\"\",\"caption\":\"\"},{\"src\":\"assets/images/placeholders/placeholder-hero.svg\",\"tag\":\"\",\"caption\":\"\"},{\"src\":\"assets/images/placeholders/placeholder-gallery.svg\",\"tag\":\"\",\"caption\":\"\"}]', '2026-04-19 02:28:18'),
(135, 'dining', 'menu_json', 'json', '[{\"name\":\"Lorem Ipsum I\",\"desc\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\",\"price\":\"$00\"},{\"name\":\"Dolor Sit II\",\"desc\":\"Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\",\"price\":\"$00\"},{\"name\":\"Amet Elit III\",\"desc\":\"Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.\",\"price\":\"$00\"},{\"name\":\"Tempor IV\",\"desc\":\"Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.\",\"price\":\"$00\"}]', '2026-04-19 02:28:18'),
(136, 'amenities', 'sections_json', 'json', '[{\"bg\":\"assets/images/placeholders/placeholder-hero.svg\",\"gradient\":\"linear-gradient(rgba(15, 15, 10, 0.3) 0%, rgba(15, 15, 10, 0.8) 100%)\",\"kicker\":\"01 / Lorem\",\"icon\":\"restaurant\",\"title_html\":\"Lorem <br/> <span class=\"font-bold text-outline\">Ipsum</span>\",\"body\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\",\"btn\":\"Learn More\",\"btn_href\":\"/dining\",\"layout\":\"bottom\"},{\"bg\":\"assets/images/placeholders/placeholder-detail.svg\",\"gradient\":\"linear-gradient(rgba(20, 15, 5, 0.5) 0%, rgba(0, 0, 0, 0.6) 100%)\",\"kicker\":\"02 / Dolor\",\"icon\":\"local_bar\",\"title_html\":\"Dolor <br/> <span class=\"font-bold text-outline\">Sit</span>\",\"body\":\"Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\",\"btn\":\"Learn More\",\"btn_href\":\"/amenities\",\"layout\":\"right\"},{\"bg\":\"assets/images/placeholders/placeholder-gallery.svg\",\"gradient\":\"linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.2) 100%)\",\"kicker\":\"03 / Amet\",\"icon\":\"pool\",\"title_html\":\"Amet <br/> <span class=\"font-bold text-outline\">Elit</span>\",\"body\":\"Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.\",\"btn\":\"Learn More\",\"btn_href\":\"/gallery\",\"layout\":\"top\"},{\"bg\":\"assets/images/placeholders/placeholder-room.svg\",\"gradient\":\"linear-gradient(to top, rgba(10, 10, 10, 0.9) 0%, rgba(10, 10, 10, 0.2) 100%)\",\"kicker\":\"04 / Tempor\",\"icon\":\"fitness_center\",\"title_html\":\"Tempor <span class=\"font-bold text-outline\">Incididunt</span>\",\"body\":\"Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"btn\":\"Learn More\",\"btn_href\":\"/rooms\",\"layout\":\"center\"}]', '2026-04-19 02:28:18');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `max_guests` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL COMMENT 'JSON array of image paths',
  `features` text DEFAULT NULL COMMENT 'JSON array of features',
  `amenities` text DEFAULT NULL COMMENT 'JSON array of amenities',
  `tags` text DEFAULT NULL COMMENT 'JSON array of tags',
  `included_items` text DEFAULT NULL COMMENT 'JSON array of included items',
  `good_to_know` text DEFAULT NULL COMMENT 'JSON object',
  `book_url` text DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `urgency_message` text DEFAULT NULL,
  `rating` int(1) DEFAULT 5,
  `rating_score` decimal(3,1) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `title`, `slug`, `price`, `room_type`, `max_guests`, `description`, `short_description`, `main_image`, `gallery_images`, `features`, `amenities`, `tags`, `included_items`, `good_to_know`, `book_url`, `original_price`, `urgency_message`, `rating`, `rating_score`, `location`, `size`, `is_featured`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Lorem Room One', 'lorem-room-one', 100.00, 'Standard', 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'assets/images/placeholders/placeholder-room.svg', '[\"assets/images/placeholders/placeholder-detail.svg\",\"assets/images/placeholders/placeholder-gallery.svg\"]', '[\"35 SQM\",\"Queen Bed\",\"City View\",\"Wi-Fi\"]', '[]', '[]', '[]', '{}', 'contact.php', NULL, '', 5, NULL, 'City View', '35 SQM', 1, 1, 10, '2026-04-19 02:28:18', '2026-04-19 02:28:18'),
(2, 'Lorem Room Two', 'lorem-room-two', 150.00, 'Suite', 3, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'assets/images/placeholders/placeholder-detail.svg', '[\"assets/images/placeholders/placeholder-room.svg\",\"assets/images/placeholders/placeholder-gallery.svg\"]', '[\"48 SQM\",\"King Bed\",\"Lounge Area\",\"Wi-Fi\"]', '[]', '[]', '[]', '{}', 'contact.php', NULL, '', 5, NULL, 'Upper Floor', '48 SQM', 1, 1, 20, '2026-04-19 02:28:18', '2026-04-19 02:28:18'),
(3, 'Lorem Room Three', 'lorem-room-three', 220.00, 'Premium', 4, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'assets/images/placeholders/placeholder-gallery.svg', '[\"assets/images/placeholders/placeholder-room.svg\",\"assets/images/placeholders/placeholder-detail.svg\"]', '[\"60 SQM\",\"Twin Lounge\",\"Corner View\",\"Wi-Fi\"]', '[]', '[]', '[]', '{}', 'contact.php', NULL, '', 5, NULL, 'Corner View', '60 SQM', 1, 1, 30, '2026-04-19 02:28:18', '2026-04-19 02:28:18');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'site_name', 'Lorem Ipsum Hotel', '2026-04-19 16:10:56'),
(2, 'site_tagline', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '2026-04-19 16:10:56'),
(3, 'room_detail_hero_badge', 'Lorem Collection', '2026-04-19 16:10:56'),
(4, 'currency_symbol', '$', '2026-04-19 16:10:56'),
(5, 'footer_tagline', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', '2026-04-19 02:28:18'),
(6, 'footer_address', '123 Lorem Avenue\r\nIpsum City, Countryd', '2026-04-19 16:10:56'),
(7, 'footer_phone', '+1 555 010 0000', '2026-04-19 16:10:56'),
(8, 'footer_email', 'hello@example.com', '2026-04-19 16:10:56'),
(9, 'footer_copyright', '© 2026 Lorem Ipsum Hotel. All rights reserved.', '2026-04-19 16:10:56'),
(10, 'contact_email', 'hello@example.com', '2026-04-19 16:10:56'),
(11, 'whatsapp_number', '', '2026-04-19 16:10:56'),
(12, 'whatsapp_link', '', '2026-04-19 16:10:56'),
(13, 'nav_suites_label', 'Suites', '2026-04-19 16:10:56'),
(14, 'nav_dining_label', 'Dining', '2026-04-19 16:10:56'),
(15, 'nav_experience_label', 'Amenities', '2026-04-19 16:10:56'),
(16, 'nav_events_label', 'Gallery', '2026-04-19 16:10:56'),
(17, 'nav_suites_href', '/rooms', '2026-04-19 16:10:56'),
(18, 'nav_dining_href', '/dining', '2026-04-19 16:10:56'),
(19, 'nav_experience_href', '/amenities', '2026-04-19 16:10:56'),
(20, 'nav_events_href', '/gallery', '2026-04-19 16:10:56'),
(21, 'nav_cta_label', 'Book Now', '2026-04-19 16:10:56'),
(22, 'nav_cta_href', '/rooms', '2026-04-19 16:10:56'),
(23, 'footer_privacy_href', '/privacy-policy', '2026-04-19 16:10:56'),
(24, 'footer_terms_href', '/terms-and-conditions', '2026-04-19 16:10:56'),
(25, 'social_media_json', '[]', '2026-04-19 16:10:56'),
(26, 'google_maps_api_key', '', '2026-04-19 16:10:56'),
(27, 'smtp_host', '', '2026-04-19 16:10:56'),
(28, 'smtp_port', '587', '2026-04-19 16:10:56'),
(29, 'smtp_username', '', '2026-04-19 16:10:56'),
(30, 'smtp_password', '', '2026-04-19 16:10:56'),
(31, 'smtp_encryption', 'tls', '2026-04-19 16:10:56'),
(32, 'smtp_from_email', '', '2026-04-19 16:10:56'),
(33, 'smtp_from_name', 'Lorem Ipsum Hotel', '2026-04-19 16:10:56'),
(34, 'cms_product_name', 'Hotel CMS', '2026-04-19 02:28:18'),
(35, 'theme_primary_color', '#411d13', '2026-04-19 16:10:56'),
(36, 'theme_primary_light_color', '#5a2a1f', '2026-04-19 16:10:56'),
(37, 'theme_background_light_color', '#efe8d6', '2026-04-19 16:10:56'),
(38, 'theme_background_dark_color', '#1a1210', '2026-04-19 16:10:56'),
(39, 'theme_champagne_color', '#f5ede0', '2026-04-19 16:10:56'),
(40, 'theme_text_main_color', '#363636', '2026-04-19 16:10:56'),
(41, 'theme_text_muted_color', '#5c5c5c', '2026-04-19 16:10:56'),
(42, 'theme_surface_light_color', '#ffffff', '2026-04-19 16:10:56'),
(43, 'theme_surface_dark_color', '#2a1f1c', '2026-04-19 16:10:56'),
(44, 'theme_surface_ink_color', '#2a1814', '2026-04-19 16:10:56'),
(45, 'theme_sand_darker_color', '#e3dcc8', '2026-04-19 16:10:56'),
(46, 'theme_display_font', 'Plus Jakarta Sans', '2026-04-19 16:10:56'),
(47, 'theme_serif_font', 'Playfair Display', '2026-04-19 16:10:56'),
(48, 'theme_body_font', 'Noto Sans', '2026-04-19 16:10:56'),
(49, 'booking_wrapper_id', 'booking-shell', '2026-04-19 16:10:56'),
(50, 'header_scripts', '', '2026-04-19 16:10:56'),
(51, 'body_scripts', '', '2026-04-19 16:10:56'),
(52, 'footer_scripts', '', '2026-04-19 16:10:56'),
(53, 'site_logo', '', '2026-04-19 16:10:56'),
(54, 'site_logo_light', '', '2026-04-19 16:10:56'),
(55, 'site_favicon', '', '2026-04-19 16:10:56'),
(56, 'page_active_about', '1', '2026-04-19 16:22:16'),
(57, 'page_active_rooms', '1', '2026-04-19 15:16:03'),
(58, 'page_active_contact', '1', '2026-04-19 15:16:03'),
(59, 'page_active_gallery', '1', '2026-04-19 15:16:03'),
(60, 'page_active_dining', '1', '2026-04-19 15:16:03'),
(61, 'page_active_amenities', '1', '2026-04-19 15:16:03'),
(62, 'page_active_hotel-policy', '1', '2026-04-19 15:16:03'),
(63, 'page_active_privacy-policy', '1', '2026-04-19 15:16:03'),
(64, 'page_active_terms-and-conditions', '1', '2026-04-19 15:16:03'),
(65, 'maintenance_mode', '0', '2026-04-19 16:10:56'),
(66, 'maintenance_title', 'Site under maintenance', '2026-04-19 16:10:56'),
(67, 'maintenance_message', 'We are making some updates right now. Please check back soon.', '2026-04-19 16:10:56'),
(68, 'maintenance_background', 'assets/images/placeholders/placeholder-hero.svg', '2026-04-19 16:10:56'),
(121, 'smartsupp_key', '', '2026-04-19 16:10:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_file_path` (`file_path`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_page_section` (`page`,`section_key`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

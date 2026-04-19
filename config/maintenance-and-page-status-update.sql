-- Maintenance mode + page status settings update
-- Safe to run on an existing database.
-- This only adds/updates site_settings keys required by recent CMS changes.

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('page_active_about', '1'),
('page_active_rooms', '1'),
('page_active_contact', '1'),
('page_active_gallery', '1'),
('page_active_dining', '1'),
('page_active_amenities', '1'),
('page_active_hotel-policy', '1'),
('page_active_privacy-policy', '1'),
('page_active_terms-and-conditions', '1'),
('maintenance_mode', '0'),
('maintenance_title', 'Site under maintenance'),
('maintenance_message', 'We are making some updates right now. Please check back soon.'),
('maintenance_background', 'assets/images/placeholders/placeholder-hero.svg')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

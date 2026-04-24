-- Optional patch for EXISTING databases (no new tables, no DROP).
-- Safe to run multiple times: uses INSERT IGNORE so existing rows are left unchanged.
-- After import, optionally open Admin → Homepage and Save once to refresh types/content.

-- Align brand story body with HTML storage (row usually already exists).
UPDATE `page_sections`
SET `content_type` = 'html', `updated_at` = NOW()
WHERE `page` = 'index' AND `section_key` = 'home_philosophy_body';

-- Missing homepage section rows (older installs before BW Plus homepage refresh).
INSERT IGNORE INTO `page_sections` (`page`, `section_key`, `content_type`, `content`, `updated_at`) VALUES
('index', 'hero_trust_badge', 'text', 'Travelers Choice 2026', NOW()),
('index', 'hero_show_stars', 'text', '1', NOW()),
('index', 'hero_title', 'html', 'Luxury on the Shores<br/><span class=\"italic text-surface\">of Oxbow Lake.</span>', NOW()),
('index', 'hero_subtitle', 'text', 'An international standard of hospitality in the heart of Bayelsa.', NOW()),
('index', 'hero_bg', 'image', 'assets/images/placeholders/placeholder-hero.svg', NOW()),
('index', 'home_booking_guarantee_line', 'text', 'Best Rate Guarantee', NOW()),
('index', 'home_philosophy_kicker', 'text', 'Our Heritage', NOW()),
('index', 'home_philosophy_title_html', 'html', 'Where Heritage Meets Hospitality', NOW()),
('index', 'home_philosophy_body', 'html', '<p>Nestled in the heart of Bayelsa State, our hotel blends rich heritage with modern hospitality. As part of the Best Western <span class=\"text-brand-red font-semibold\">Plus</span> collection, we uphold a legacy of excellence while delivering a distinctively Nigerian warmth.</p>', NOW()),
('index', 'home_philosophy_link_text', 'text', 'Explore the Story', NOW()),
('index', 'home_philosophy_link_href', 'text', '/about', NOW()),
('index', 'home_philosophy_main_img', 'image', 'assets/images/placeholders/placeholder-detail.svg', NOW()),
('index', 'home_rooms_kicker', 'text', 'Exquisite suites designed for the refined traveler', NOW()),
('index', 'home_rooms_title', 'text', 'Sanctuaries of Calm', NOW()),
('index', 'home_rooms_view_all_href', 'text', '/rooms', NOW()),
('index', 'home_dining_kicker', 'text', 'Gastronomy', NOW()),
('index', 'home_dining_heading_html', 'html', '<span class=\"italic\">Culinary Excellence</span>', NOW()),
('index', 'home_dining_venue1_title', 'text', 'Mama Oxbow', NOW()),
('index', 'home_dining_venue1_body', 'text', 'Authentic local delicacies crafted with a modern touch, overlooking the gentle ripples of the lake.', NOW()),
('index', 'home_dining_venue2_title', 'text', 'Red Lotus', NOW()),
('index', 'home_dining_venue2_body', 'text', 'An Asian-fusion journey where tradition meets contemporary culinary innovation.', NOW()),
('index', 'home_dining_image_top', 'image', 'assets/images/placeholders/placeholder-gallery.svg', NOW()),
('index', 'home_dining_image_bottom', 'image', 'assets/images/placeholders/placeholder-detail.svg', NOW()),
('index', 'home_facilities_title', 'text', 'Leisure & Wellness', NOW()),
('index', 'home_facilities_blurb', 'text', 'Designed to rejuvenate your senses and enhance your productivity.', NOW()),
('index', 'home_facilities_bento_json', 'json', '[{\"image\":\"assets/images/placeholders/placeholder-hero.svg\",\"title\":\"The Infinity Pool\",\"subtitle\":\"Open Daily • 6AM - 10PM\"},{\"image\":\"assets/images/placeholders/placeholder-detail.svg\",\"title\":\"Wellness Spa\",\"subtitle\":\"\"},{\"image\":\"assets/images/placeholders/placeholder-gallery.svg\",\"title\":\"Elite Gym\",\"subtitle\":\"\"},{\"image\":\"assets/images/placeholders/placeholder-room.svg\",\"title\":\"Akassa Hall\",\"subtitle\":\"Business & Events\"}]', NOW()),
('index', 'home_location_title', 'text', 'The Serenity of Oxbow Lake', NOW()),
('index', 'home_location_body', 'text', 'A peaceful retreat away from the city''s pulse, offering breathtaking views and tranquil mornings.', NOW()),
('index', 'home_location_bullets_json', 'json', '[\"5 min to Government House\",\"15 min to Airport\",\"Oxbow Lake waterfront\"]', NOW()),
('index', 'home_location_address', 'text', 'Oxbow Lake Rd, Yenagoa, Bayelsa', NOW()),
('index', 'home_location_map_image', 'image', 'assets/images/placeholders/placeholder-gallery.svg', NOW()),
('index', 'home_location_map_embed_url', 'text', '', NOW()),
('index', 'booking_widget_html', 'html', '', NOW());

-- Rooms listing page keys for redesigned /rooms (older installs).
INSERT IGNORE INTO `page_sections` (`page`, `section_key`, `content_type`, `content`, `updated_at`) VALUES
('rooms', 'hero_title', 'text', 'Rooms & Suites', NOW()),
('rooms', 'hero_kicker', 'text', 'Accommodations', NOW()),
('rooms', 'hero_subtitle', 'text', 'Sanctuaries of comfort on the shores of Oxbow Lake', NOW()),
('rooms', 'compare_label', 'text', 'Compare all rooms', NOW()),
('rooms', 'booking_checkin_label', 'text', 'Check-in', NOW()),
('rooms', 'booking_checkin_value', 'text', 'Dec 14, 2024', NOW()),
('rooms', 'booking_checkout_label', 'text', 'Check-out', NOW()),
('rooms', 'booking_checkout_value', 'text', 'Dec 18, 2024', NOW()),
('rooms', 'booking_guests_label', 'text', 'Guests', NOW()),
('rooms', 'booking_guests_value', 'text', '2 Adults, 1 Room', NOW()),
('rooms', 'booking_cta_label', 'text', 'Check Availability', NOW()),
('rooms', 'signature_badge', 'text', 'Signature Suite', NOW()),
('rooms', 'signature_kicker', 'text', 'The Pinnacle of Living', NOW()),
('rooms', 'amenities_reminder_title', 'text', 'All suites include:', NOW()),
('rooms', 'amenities_reminder_items_json', 'json', '["WIFI","BREAKFAST","TOILETRIES","TURNDOWN"]', NOW()),
('rooms', 'amenities_reminder_section_classes', 'text', 'bg-surface-container py-[54px]', NOW()),
('rooms', 'final_cta_section_classes', 'text', 'py-7 my-0 text-center bg-white', NOW()),
('rooms', 'final_cta_title', 'text', 'Need help choosing?', NOW()),
('rooms', 'final_cta_body', 'text', 'Our dedicated concierge is available 24/7 to help you select the perfect sanctuary for your stay in Yenagoa.', NOW()),
('rooms', 'final_cta_label', 'text', 'Contact Reservations', NOW()),
('rooms', 'final_cta_href', 'text', '/contact', NOW());

-- If hero_title was stored as HTML in legacy seeds, normalize to plain text for the new template.
UPDATE `page_sections`
SET `content_type` = 'text', `updated_at` = NOW()
WHERE `page` = 'rooms' AND `section_key` = 'hero_title' AND `content_type` = 'html';

-- Amenities page: bottom CTA copy (public page reads these; sections_json remains for modules).
INSERT IGNORE INTO `page_sections` (`page`, `section_key`, `content_type`, `content`, `updated_at`) VALUES
('amenities', 'cta_title', 'text', 'Ready to Experience Our Facilities?', NOW()),
('amenities', 'cta_btn_label', 'text', 'Book Your Stay', NOW()),
('amenities', 'cta_btn_href', 'text', '/contact', NOW()),
('amenities', 'services_kicker', 'text', 'Impeccable Care', NOW()),
('amenities', 'services_title', 'text', 'Signature Guest Services', NOW()),
('amenities', 'services_items_json', 'json', '[{"title":"24h Concierge","subtitle":"Dedicated to your every whim"},{"title":"Airport Transfer","subtitle":"Luxury chauffeur fleet"},{"title":"Laundry & Press","subtitle":"Same-day valet service"},{"title":"High-Speed WiFi","subtitle":"Gigabit fiber throughout"},{"title":"Secure Parking","subtitle":"24/7 guarded premises"},{"title":"Room Service","subtitle":"Global dining 24/7"}]', NOW());

-- Site settings used by the new header/footer and placeholders (INSERT IGNORE keeps custom values).
INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('site_brand_collection_line', 'Part of Best Western Plus Collection', NOW()),
('nav_story_label', 'Story', NOW()),
('nav_story_href', '/about', NOW()),
('footer_trust_line', '5-Star Luxury Resort', NOW()),
('footer_line_2', 'Designed for the world.', NOW()),
('footer_careers_href', '', NOW()),
('footer_press_href', '', NOW()),
('footer_sustainability_href', '', NOW()),
('placeholder_hero_image', 'assets/images/placeholders/placeholder-hero.svg', NOW()),
('placeholder_room_image', 'assets/images/placeholders/placeholder-room.svg', NOW()),
('placeholder_detail_image', 'assets/images/placeholders/placeholder-detail.svg', NOW()),
('placeholder_gallery_image', 'assets/images/placeholders/placeholder-gallery.svg', NOW());

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
('index', 'booking_widget_html', 'html', '', NOW());

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

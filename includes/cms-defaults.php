<?php
/**
 * Default structured content when DB sections are empty (first visit).
 * Used by public pages + admin editors for placeholder JSON.
 */

$heroPlaceholder = function_exists('cms_default_setting') ? (string) cms_default_setting('placeholder_hero_image', 'assets/images/placeholders/placeholder-hero.svg') : 'assets/images/placeholders/placeholder-hero.svg';
$roomPlaceholder = function_exists('cms_default_setting') ? (string) cms_default_setting('placeholder_room_image', 'assets/images/placeholders/placeholder-room.svg') : 'assets/images/placeholders/placeholder-room.svg';
$detailPlaceholder = function_exists('cms_default_setting') ? (string) cms_default_setting('placeholder_detail_image', 'assets/images/placeholders/placeholder-detail.svg') : 'assets/images/placeholders/placeholder-detail.svg';
$galleryPlaceholder = function_exists('cms_default_setting') ? (string) cms_default_setting('placeholder_gallery_image', 'assets/images/placeholders/placeholder-gallery.svg') : 'assets/images/placeholders/placeholder-gallery.svg';

return [
    'about_timeline' => [
        ['year' => '2021', 'kind' => 'circle', 'title' => 'Lorem Ipsum', 'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
        ['year' => '2022', 'kind' => 'dot', 'title' => 'Dolor Sit', 'body' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'],
        ['year' => '2023', 'kind' => 'circle', 'title' => 'Amet Elit', 'body' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.'],
        ['year' => '2024', 'kind' => 'dot_primary', 'title' => 'Tempor Incididunt', 'body' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'],
    ],
    'about_team' => [
        $galleryPlaceholder,
        $detailPlaceholder,
        $roomPlaceholder,
    ],
    'gallery_items' => [
        $galleryPlaceholder,
        $heroPlaceholder,
        $detailPlaceholder,
        $roomPlaceholder,
        $galleryPlaceholder,
        $detailPlaceholder,
    ],
    'dining_menu' => [
        ['name' => 'Lorem Ipsum I', 'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'price' => '$00'],
        ['name' => 'Dolor Sit II', 'desc' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'price' => '$00'],
        ['name' => 'Amet Elit III', 'desc' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'price' => '$00'],
        ['name' => 'Tempor IV', 'desc' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.', 'price' => '$00'],
    ],
    'dining_masonry' => [
        ['src' => $galleryPlaceholder, 'tag' => 'Lorem', 'caption' => 'Lorem Ipsum'],
        ['src' => $detailPlaceholder, 'tag' => 'Dolor', 'caption' => 'Dolor Sit'],
        ['src' => $roomPlaceholder, 'tag' => '', 'caption' => ''],
        ['src' => $heroPlaceholder, 'tag' => '', 'caption' => ''],
        ['src' => $galleryPlaceholder, 'tag' => '', 'caption' => ''],
    ],
    'amenities_sections' => [
        ['bg' => $heroPlaceholder, 'gradient' => 'linear-gradient(rgba(15, 15, 10, 0.3) 0%, rgba(15, 15, 10, 0.8) 100%)', 'kicker' => '01 / Lorem', 'icon' => 'restaurant', 'title_html' => "Lorem <br/> <span class=\"font-bold text-outline\">Ipsum</span>", 'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'btn' => 'Learn More', 'btn_href' => '/dining', 'layout' => 'bottom'],
        ['bg' => $detailPlaceholder, 'gradient' => 'linear-gradient(rgba(20, 15, 5, 0.5) 0%, rgba(0, 0, 0, 0.6) 100%)', 'kicker' => '02 / Dolor', 'icon' => 'local_bar', 'title_html' => "Dolor <br/> <span class=\"font-bold text-outline\">Sit</span>", 'body' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'btn' => 'Learn More', 'btn_href' => '/amenities', 'layout' => 'right'],
        ['bg' => $galleryPlaceholder, 'gradient' => 'linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.2) 100%)', 'kicker' => '03 / Amet', 'icon' => 'pool', 'title_html' => "Amet <br/> <span class=\"font-bold text-outline\">Elit</span>", 'body' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'btn' => 'Learn More', 'btn_href' => '/gallery', 'layout' => 'top'],
        ['bg' => $roomPlaceholder, 'gradient' => 'linear-gradient(to top, rgba(10, 10, 10, 0.9) 0%, rgba(10, 10, 10, 0.2) 100%)', 'kicker' => '04 / Tempor', 'icon' => 'fitness_center', 'title_html' => 'Tempor <span class="font-bold text-outline">Incididunt</span>', 'body' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'btn' => 'Learn More', 'btn_href' => '/rooms', 'layout' => 'center'],
    ],
];

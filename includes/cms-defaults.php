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
        [
            'bg' => $heroPlaceholder,
            'gradient' => 'linear-gradient(rgba(15, 15, 10, 0.3) 0%, rgba(15, 15, 10, 0.8) 100%)',
            'kicker' => 'The Sovereign Experience',
            'icon' => 'spa',
            'title_html' => 'Facilities <span class="italic">&amp;</span> Amenities',
            'body' => 'Everything you need for business, leisure, and wellness — curated for a calm, elevated stay.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $detailPlaceholder,
            'gradient' => '',
            'kicker' => 'Native Flavors',
            'icon' => 'restaurant',
            'title_html' => 'Dining <span class="italic">Experience</span>',
            'body' => 'Seasonal menus and local ingredients, served in spaces designed for lingering conversations.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $galleryPlaceholder,
            'gradient' => '',
            'kicker' => 'Oriental Mastery',
            'icon' => 'local_bar',
            'title_html' => 'Red <span class="italic">Lotus</span>',
            'body' => 'A refined Asian-inspired dining room for intimate dinners and celebratory evenings.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $heroPlaceholder,
            'gradient' => '',
            'kicker' => 'Rejuvenation',
            'icon' => 'spa',
            'title_html' => 'The Vitality <span class="italic">Sanctuary</span>',
            'body' => 'Slow down with treatments, steam, and stillness — a wellness rhythm that matches the pace of the lake.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $roomPlaceholder,
            'gradient' => '',
            'kicker' => 'Movement',
            'icon' => 'fitness_center',
            'title_html' => 'Elite <span class="italic">Fitness</span>',
            'body' => 'Strength, cardio, and recovery essentials so you can train, stretch, and reset on your schedule.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $detailPlaceholder,
            'gradient' => '',
            'kicker' => 'Aquatic Calm',
            'icon' => 'pool',
            'title_html' => 'Infinity <span class="italic">Pool</span>',
            'body' => 'Sunlit water and quiet corners — the kind of pool day that feels intentionally unhurried.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
        [
            'bg' => $galleryPlaceholder,
            'gradient' => '',
            'kicker' => 'Corporate Excellence',
            'icon' => 'meeting_room',
            'title_html' => 'Meetings <span class="italic">&amp; Events</span>',
            'body' => 'Host with confidence in versatile spaces supported by attentive service and modern AV.',
            'btn' => '',
            'btn_href' => '',
            'layout' => 'bottom',
            'gallery' => [],
        ],
    ],
];

-- One-time cleanup for retired footer settings keys.
-- Safe to run multiple times.

DELETE FROM `site_settings`
WHERE `setting_key` IN (
  'footer_trust_line',
  'footer_press_href'
);

<?php
/**
 * Best Western booking deep-link helper (official booking site URLs + SSOB attribution).
 */

if (!function_exists('site_bw_booking_parse_extra_query')) {
    /**
     * Parse editor paste like "currency=USD&length=2" (no leading "?").
     *
     * @return array<string, string>
     */
    function site_bw_booking_parse_extra_query(string $raw): array {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }
        // Reject ambiguous strings
        if (strpos($raw, '?') !== false) {
            return [];
        }
        $out = [];
        foreach (explode('&', $raw) as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }
            $parts = explode('=', $segment, 2);
            $key = isset($parts[0]) ? trim((string) $parts[0]) : '';
            $val = isset($parts[1]) ? trim((string) $parts[1]) : '';
            if ($key === '') {
                continue;
            }
            // BW uses camelCase (checkIn, rm1adults); allow sane characters only
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $key)) {
                continue;
            }
            $out[$key] = $val;
        }

        return $out;
    }
}

if (!function_exists('site_bw_booking_sort_params_bw_doc_order')) {
    /**
     * Sort query params per BW deep-link guide (after ssob): iata, dates, occupancy, multi-room rm*, featured roomType, rates, currency, etc.
     * Unknown keys keep a stable tail order.
     *
     * @param array<string, string> $merged
     * @return array<string, string>
     */
    function site_bw_booking_sort_params_bw_doc_order(array $merged): array {
        if ($merged === []) {
            return [];
        }
        /** @var list<string> $docOrderedKeys */
        $docOrderedKeys = [
            'iata',
            'checkIn',
            'checkOut',
            'rooms',
            'adults',
            'children',
        ];
        // Corporate / promo / rate (table order approx.; corpID appears in BW examples alongside corpId spelling)
        $docOrderedTail = ['roomType', 'corpId', 'corpID', 'promoCode', 'rateCode', 'currency', 'days', 'length'];

        $out = [];

        foreach ($docOrderedKeys as $k) {
            if (!array_key_exists($k, $merged)) {
                continue;
            }
            $out[$k] = $merged[$k];
            unset($merged[$k]);
        }

        /** @var list<string> $rmKeys keys like rm1adults */
        $rmKeys = [];
        foreach (array_keys($merged) as $mk) {
            if (preg_match('/^rm\d/i', $mk)) {
                $rmKeys[] = $mk;
            }
        }
        sort($rmKeys, SORT_NATURAL);

        foreach ($rmKeys as $mk) {
            $out[$mk] = $merged[$mk];
            unset($merged[$mk]);
        }

        foreach ($docOrderedTail as $k) {
            if (!array_key_exists($k, $merged)) {
                continue;
            }
            $out[$k] = $merged[$k];
            unset($merged[$k]);
        }

        foreach ($merged as $k => $v) {
            $out[(string) $k] = $v;
        }

        return $out;
    }
}

if (!function_exists('site_bw_booking_url')) {
    /**
     * Build BW booking URL with SSOB first, then merged query params.
     *
     * @param array<string, scalar> $extraParams Passed as additional query pairs (never overrides SSOB).
     */
    function site_bw_booking_url(array $extraParams = []): string {
        $defaultBase = '';
        $defaultSsob = '';
        if (function_exists('cms_default_setting')) {
            $defaultBase = cms_default_setting('bw_booking_base_url');
            $defaultSsob = cms_default_setting('bw_booking_ssob');
        }
        if ($defaultBase === '') {
            $defaultBase = 'https://www.bestwestern.com/en_US/book/hotel-rooms.75424.html';
        }
        if ($defaultSsob === '') {
            $defaultSsob = 'IN7542443G';
        }

        $baseRaw = '';
        if (function_exists('getSiteSetting')) {
            $baseRaw = trim((string) getSiteSetting('bw_booking_base_url', $defaultBase));
        }
        if ($baseRaw === '') {
            $baseRaw = $defaultBase;
        }

        $parsed = parse_url($baseRaw);
        if (!$parsed || empty($parsed['scheme']) || empty($parsed['host'])) {
            $parsed = parse_url($defaultBase);
        }

        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? 'www.bestwestern.com';
        $path = isset($parsed['path']) ? (string) $parsed['path'] : '';
        if ($path === '') {
            $path = '/';
        }
        $existingQuery = [];
        if (!empty($parsed['query'])) {
            parse_str((string) $parsed['query'], $existingQuery);
            if (!is_array($existingQuery)) {
                $existingQuery = [];
            }
        }
        $fragment = isset($parsed['fragment']) && (string) $parsed['fragment'] !== ''
            ? '#' . (string) $parsed['fragment']
            : '';

        $ssob = '';
        if (function_exists('getSiteSetting')) {
            $ssob = trim((string) getSiteSetting('bw_booking_ssob', $defaultSsob));
        }
        if ($ssob === '') {
            $ssob = $defaultSsob;
        }

        $extrasFromSetting = '';
        if (function_exists('getSiteSetting')) {
            $defExtra = (function_exists('cms_default_setting')) ? cms_default_setting('bw_booking_extra_query') : '';
            $extrasFromSetting = trim((string) getSiteSetting('bw_booking_extra_query', (string) $defExtra));
        }

        $fromSettingField = function_exists('site_bw_booking_parse_extra_query')
            ? site_bw_booking_parse_extra_query((string) $extrasFromSetting)
            : [];

        /** @var array<string, string> $merged */
        $merged = [];
        foreach ($existingQuery as $qk => $qv) {
            $qk = is_string($qk) ? trim($qk) : (string) $qk;
            if ($qk !== '' && $qk !== 'ssob') {
                $merged[$qk] = is_scalar($qv) ? (string) $qv : '';
            }
        }
        foreach ($fromSettingField as $qk => $qv) {
            $qk = (string) $qk;
            if ($qk !== '' && $qk !== 'ssob') {
                $merged[$qk] = is_scalar($qv) ? (string) $qv : '';
            }
        }

        foreach ($extraParams as $qk => $qv) {
            $qk = trim((string) $qk);
            if ($qk === '' || $qk === 'ssob') {
                continue;
            }
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $qk)) {
                continue;
            }
            $merged[$qk] = is_scalar($qv) ? (string) $qv : '';
        }

        // BW docs: after ssob, prefer parameters in the documented order (servers usually accept any order).
        /** @var array<string, string> $merged */
        $merged = site_bw_booking_sort_params_bw_doc_order($merged);

        /** @var array<string, string> $ordered SSOB always first */
        $ordered = [];
        if ($ssob !== '') {
            $ordered['ssob'] = $ssob;
        }
        foreach ($merged as $k => $v) {
            if ($k === 'ssob') {
                continue;
            }
            $ordered[$k] = $v;
        }

        $query = http_build_query($ordered, '', '&', PHP_QUERY_RFC3986);

        return $scheme . '://' . $host . $path . ($query !== '' ? '?' . $query : '') . $fragment;
    }
}

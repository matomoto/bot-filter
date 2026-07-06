<?php

// PHP Rules for Matomo Tracking Bot Filter
// PHP 8.1 and newer

/*
#################
USE WHAT YOU NEED
#################
*/

// global bool value
$mtm_bot_filter_bool = false;

// Get all Headers
$mtm_getallheaders_array = getallheaders();

// Get User Agent
$mtm_useragent = "";
if (array_key_exists('User-Agent', $mtm_getallheaders_array) === true) {
	$mtm_useragent = trim($_SERVER['HTTP_USER_AGENT']);
} else if (array_key_exists('HTTP_USER_AGENT', $_SERVER) === true) {
	$mtm_useragent = trim($mtm_getallheaders_array['User-Agent']);
}

// Empty and Headless Browser Filter
// case-sensitive
$mtm_headless_browser_array = [
	'HeadlessChrome',
	'PhantomJS',
	'Electron',
	'ApacheBench', // Load test
	'Siege/', // Load test
	'https://k6.io/', // Load test, Added full URL to handle case where UA can be fook6bar or fook6/
	'Radview', // Load test
	'Locust', // Load test
	'Cypress', // Testing Tool
	'-' // Placeholder
];
if (empty($mtm_useragent) === true) {
	$mtm_bot_filter_bool = true;
} else {
	foreach ($mtm_headless_browser_array as $headless_browser_name) {
		if (str_contains($mtm_useragent, $headless_browser_name) === true) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// User Agent Filter
// non-case-sensitive
$mtm_useragent_contains_array = [
"bot",
"crawler",
"spider",
"headless",
"compatible",
"proxy",
"bytedance",
"whatsapp",
"duckduck",
"chatgpt",
"skype",
"preview",
"telegram",
"networkingextension",
"blockchain",
"externalhit",
"bitcoin",
"crypto",
"curl",
"scrapy",
"splash",
"java",
"feed",
"fetcher",
"python",
"jakarta",
"okhttp",
"httpclient",
"jersey",
"perl",
"ruby",
"slurp",
"netvibes",
"zgrab",
"sogou",
"abonti",
"pixray",
"spinn3r",
"zmeu",
"farside"
];
if (empty($mtm_useragent) === false) {
	$mtm_useragent_lower = strtolower($mtm_useragent);
	foreach ($mtm_useragent_contains_array as $useragent_contains_name) {
		if (str_contains($mtm_useragent_lower, strtolower($useragent_contains_name)) === true) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// User Agent character filter
if (str_contains($mtm_useragent, '/') === false) {
	$mtm_bot_filter_bool = true;
}
if (str_contains($mtm_useragent, '.') === false) {
	$mtm_bot_filter_bool = true;
}

// Browser Version Chromium
$mtm_chromium_version_min = 150;
$mtm_sec_ch_ua_array = array();
if (array_key_exists('Sec-Ch-Ua', $mtm_getallheaders_array) === true) {
	$mtm_sec_ch_ua = $mtm_getallheaders_array['Sec-Ch-Ua'];
	$mtm_sec_ch_ua_explode = explode(',', $mtm_sec_ch_ua);
	foreach($mtm_sec_ch_ua_explode as $mtm_sec_ch_ua_value) {
		$mtm_sec_ch_ua_explode_sub = explode(';', $mtm_sec_ch_ua_value);
		$mtm_sec_ch_ua_array[trim($mtm_sec_ch_ua_explode_sub[0], "\x00..\x2F")] = $mtm_sec_ch_ua_explode_sub[1];
	}
	if (array_key_exists('Chromium', $mtm_sec_ch_ua_array) === true) {
		$mtm_chromium_version = str_replace(array('v', '=', '"'), '', $mtm_sec_ch_ua_array['Chromium']);
		$mtm_chromium_version_int = intval($mtm_chromium_version);
		if ($mtm_chromium_version_int < $mtm_chromium_version_min) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// Browser Version Firefox
$mtm_firefox_version_min = 150;
if (empty($mtm_useragent) === false) {
	$mtm_pattern = '/Firefox\/[0-9]+/';
	preg_match($mtm_pattern, $mtm_useragent, $mtm_matches);
	$mtm_firefox_version = str_replace('Firefox/', '', $mtm_matches[0]);
	$mtm_firefox_version_int = intval($mtm_firefox_version);
	if ($mtm_firefox_version_int < $mtm_firefox_version_min) {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept' exist
if (array_key_exists('Accept', $mtm_getallheaders_array) === false) {
	$mtm_bot_filter_bool = true;
}

// Header 'Accept' - text/html
if (array_key_exists('Accept', $mtm_getallheaders_array) === true) {
	if (str_contains(strtolower($mtm_getallheaders_array['Accept']), 'text/html') === false) {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept-Language' exist
if (array_key_exists('Accept-Language', $mtm_getallheaders_array) === false) {
	$mtm_bot_filter_bool = true;
}

// Header 'Accept-Language' not empty
if (array_key_exists('Accept-Language', $mtm_getallheaders_array) === true) {
	if (trim($mtm_getallheaders_array['Accept-Language']) == "") {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept-Language' not "undefined"
if (array_key_exists('Accept-Language', $mtm_getallheaders_array) === true) {
	if (trim($mtm_getallheaders_array['Accept-Language']) == "undefined") {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept-Language' - zh-CN
// No Referer
if (array_key_exists('Accept-Language', $mtm_getallheaders_array) === true) {
	if (str_contains(strtolower($mtm_getallheaders_array['Accept-Language']), 'zh-cn') === true) {
		if (array_key_exists('Referer', $mtm_getallheaders_array) === false) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// Referer - https://
if (array_key_exists('Referer', $mtm_getallheaders_array) === true) {
	if (str_starts_with(strtolower($mtm_getallheaders_array['Referer']), 'https://') === false) {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept-Encoding' - zstd,gzip,deflate,br
if (array_key_exists('Accept-Encoding', $mtm_getallheaders_array) === true) {
	if (str_contains(strtolower($mtm_getallheaders_array['Accept-Encoding']), 'gzip') === false) {
		$mtm_bot_filter_bool = true;
	}
	if (str_contains(strtolower($mtm_getallheaders_array['Accept-Encoding']), 'deflate') === false) {
		$mtm_bot_filter_bool = true;
	}
}

// Header 'Accept-Encoding' - zstd,gzip,deflate,br
// Opera, OPR/
if ((str_contains($mtm_useragent, 'Opera') === false) && (str_contains($mtm_useragent, 'OPR/') === false)) {
	if (array_key_exists('Accept-Encoding', $mtm_getallheaders_array) === true) {
		if (str_contains(strtolower($mtm_getallheaders_array['Accept-Encoding']), 'zstd') === false) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// Header 'Sec-Fetch-Dest' exist
// Header 'Sec-Fetch-Mode' exist
// Header 'Sec-Fetch-Site' exist
if ((array_key_exists('Sec-Fetch-Dest', $mtm_getallheaders_array) === false) || (array_key_exists('Sec-Fetch-Mode', $mtm_getallheaders_array) === false) || (array_key_exists('Sec-Fetch-Site', $mtm_getallheaders_array) === false)) {
	$mtm_bot_filter_bool = true;
}

// Header 'Sec-Fetch-Dest' - document
if (array_key_exists('Sec-Fetch-Dest', $mtm_getallheaders_array) === true) {
	if (strtolower($mtm_getallheaders_array['Sec-Fetch-Dest']) !== 'document') {
			$mtm_bot_filter_bool = true;
	}
}

// Header 'Sec-Ch-Ua' exist in Chromium
// Ch: Client Hint
// Ua: User Agent
if (array_key_exists('User-Agent', $mtm_getallheaders_array) === true) {
	if (str_contains(strtolower($mtm_getallheaders_array['User-Agent']), 'chrom') === true) {
		if (array_key_exists('Sec-Ch-Ua', $mtm_getallheaders_array) === false) {
			$mtm_bot_filter_bool = true;
		}
	}
}

// Header 'Sec-Ch-Ua' don't exist in Firefox
if (array_key_exists('Sec-Ch-Ua', $mtm_getallheaders_array) === true) {
	if (str_contains(strtolower($mtm_getallheaders_array['Sec-Ch-Ua']), 'chrom') === true) {
		if (array_key_exists('User-Agent', $mtm_getallheaders_array) === true) {
			if (str_contains(strtolower($mtm_getallheaders_array['User-Agent']), 'firefox') === true) {
				$mtm_bot_filter_bool = true;
			}
		}
	}
}

?>

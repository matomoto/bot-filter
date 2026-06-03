# bot-filter
## Matomo Tracking Bot Filter

More and more Bots are non-detectable with commonly strategies. Here are few rules to detect bots with PHP. This rules don't detect all bots. Maybe few humans are also filtered with this, when this humans used special or old browsers.

Every bot detecting needs a pattern recognition. Many bots are recognized with patterns.

## JavaScript Bot Filter
The simplest bot filter is JavaScript. Use the Matomo JavaScript Tracking code. The common bots don't have a JavaScript engine.

## Plugin Track Spam Prevention
Use the Plugin TrackSpamPrevention.

## Geolocation
Use the Matomo Geolocation function in the Dashboard with this:
- Location Database: `https://download.db-ip.com/free/dbip-city-lite-`[year]`-`[month]`.mmdb.gz`
- ISP Database: `https://download.db-ip.com/free/dbip-asn-lite-`[year]`-`[month]`.mmdb.gz`

And than check the statistic reports manually/visually to search for abnormallities by the Geolocation and Providers.    
Make a countercheck via: https://www.maxmind.com/en/geoip-demo    
- Make your own Bot IP collection and use it as a filter in the Dashboard "Global list of Excluded IPs".

## PHP Bot Filter
It is possible to use the Browser User Agent and the Headers to detect bot patterns.

Headers: `getallheaders()` array
User Agent: `getallheaders()` array key `'User-Agent'`
User Agent: `$_SERVER['HTTP_USER_AGENT']`

Search for typical bot keywords (needles) in the User Agent:
- Headless browser keywords
- typical bot keywords
- missing of typical characters like `/` and `.`

Check for Headers Keys:
- `'Accept'` must be exist
- `'Accept-Language'` must be exist
- `'Accept-Encoding'` must be exist
Specially to filter Direct view with language 'chinese':
- Direct view and `'Accept-Language'` = 'zh-cn'
Specially for `'Accept-Encoding'`:
- value `gzip` must be exist
- value `deflate` must be exist
Specially for Non-Opera browser:
- value `zstd` must be exist

Exclude old browsers with a check for Headers Keys:
- `'Sec-Fetch-Dest'` must be exist
- `'Sec-Fetch-Mode'` must be exist
- `'Sec-Fetch-Site'` must be exist
Same specially for Chromium based browsers:
- if (newer) Chromium: `'Sec-Ch-Ua'` must be exist
Same as a false check for Firefox:
- if Firefox: `'Sec-Ch-Ua'` don't be exist
Specially for `'Sec-Fetch-Dest'`:
- value must be `document`

## Use the filter rules
- Save your Matomo JavaScript Tracking Code in a `mtmcode.js` file.
- Include this file via PHP `include __DIR__ . '/mtmcode.js';` in your webpages.
- Use the PHP filter rules and the global value `$mtm_bot_filter_bool`.
```
if ($mtm_bot_filter_bool === false) {
  include __DIR__ . '/mtmcode.js';
}
```

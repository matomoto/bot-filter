# bot-filter
## Matomo Tracking Bot Filter

More and more Bots are non-detectable with commonly strategies. Here are few rules to detect bots with PHP. This rules don't detect all bots. Maybe few humans are also filtered with this, when this humans using special or old browsers.

Every bot detecting needs a pattern recognition. Many bots are recognized with patterns.

## JavaScript Bot Filter
The simplest bot filter is JavaScript. Use the Matomo JavaScript Tracking code. The common bots don't have a JavaScript engine.

## Plugin Track Spam Prevention
Use the Matomo Plugin TrackSpamPrevention.

## Geolocation / ISP / Provider - own IP Blacklist
Use the Matomo Geolocation function in the Dashboard with this databases:
- Location Database: `https://download.db-ip.com/free/dbip-city-lite-`[year]`-`[month]`.mmdb.gz`
- ISP Database: `https://download.db-ip.com/free/dbip-asn-lite-`[year]`-`[month]`.mmdb.gz`

Activate the Matomo Plugin "Provider".

And than check your reports manually/visually to search for abnormallities by the Geolocation and Providers.    
Make a countercheck by abnormal visits with the Visitor IP on: https://www.maxmind.com/en/geoip-demo    
- "Corporate" = bad
- "Cable/DSL" = good
    
- Make your own Bot IP Blacklist and save it in the Dashboard "Global list of Excluded IPs".

The modern bots used random User Agents and random IPs (from IP lists) for every visit.

Examples of Bot IP CIDRs:
```
Twitter
69.12.56.0/21

Sprious
66.146.232.0/22
66.146.238.0/23
141.164.84.0/24
149.20.240.0/21
152.44.96.0/22
152.44.104.0/22
172.96.89.0/24
192.171.84.0/22
199.250.188.0/23
216.41.232.0/22

LogicWeb
149.57.176.0/20

Dataport
141.91.18.128/27

Vodafone Germany Business
188.111.17.128/25
```
Don't hesitate. Companys that used non-detectable bots must be blocked completelly and learn: non-detectable bots are bad and the IP ranges are blacklisted and lost.

## PHP Bot Filter
It is possible to use the Browser User Agent and the Headers to detect bot patterns.

Headers: `getallheaders()` array

User Agent: `getallheaders()` array key `'User-Agent'`

User Agent: `$_SERVER['HTTP_USER_AGENT']`

Search for typical bot keywords (needles) in the User Agent:
- Headless browser keywords
- typical bot keywords
- missing of typical characters like `/` and `.`

Check for Header Keynames:
- `'Accept'` must be exist
- `'Accept-Language'` must be exist
- `'Accept-Encoding'` must be exist

Specially to filter Direct view with language 'chinese':
- Direct view with `'Accept-Language'` = 'zh-cn'

Specially for `'Accept-Encoding'`:
- value `gzip` must be exist
- value `deflate` must be exist

Specially for Non-Opera browser:
- value `zstd` must be exist

Exclude old browsers with a check for Header Keynames:
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

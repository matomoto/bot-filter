# bot-filter
## Matomo Tracking Bot Filter

More and more Bots are non-detectable with commonly strategies. Here are few rules to detect bots with PHP. This rules don't detect all bots. Maybe few humans are also filtered with this, when this humans used special or old browsers.

Every bot detecting needs a pattern recognition. Many bots are recognized with patterns.

## Use the filter rules
- Save your Matomo JavaScript Tracking Code in a `mtmcode.js` file.
- Include this file via PHP `include __DIR__ . '/mtmcode.js';` in your webpages.
- Use the PHP filter rules and the global value `$mtm_bot_filter_bool`.
```
if ($mtm_bot_filter_bool === false) {
  include __DIR__ . '/mtmcode.js';
}
```

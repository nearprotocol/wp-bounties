# WP Bounties

Import and display GitHub bounties within a WordPress site.

## Install

1. Copy contents of `functions.php` to your theme's `functions.php` file.
2. Change repository API URL in `wpb_get_bounties_data()` function (line 8 `functions.php`).
3. Enqueue Alpine.js in your theme.
4. Add to CSS: `[x-cloak] { display: none; }` (required by Alpine)
5. Copy `bounties()` JS function to your scripts.
6. Modify markup and add styles to suit your needs.

## Notes

1. The GitHub returns up to 100 issues by default. If you need to do more you will need to update `wpb_get_bounties_data()` to get all pages of data.
2. Title format for bounty issues should be `[issue title] | Bounty: [bounty value]`. We split the string with `| Bounty:`.

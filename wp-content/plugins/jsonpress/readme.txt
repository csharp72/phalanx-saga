=== JSONPress ===
Contributors: takien
Donate link: http://takien.com/donate
Tags: json,api,jsonp,rest,restful,phonegap,json-api,rest-api
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JSONPress - Allows you to request WordPress site via JSON/JSONP output.

== Description ==

JSONPress - Allows you to request WordPress site via JSON/JSONP output using standard WordPress query.

= Features =
* Access JSON via sub domain, ex. `api.example.com` (sub domain must be configured separately)
* Supports `JSONP` callback.
* Easy to debug output using `JSON pretty print` or `print_r`.
* You can `exclude columns/data` to be displayed in JSON output.
* You can include `custom fields` too.
* This plugin uses `standard WordPress query`, no SQL hack etc.
* Use `standard WordPress URL`, no need to remember new annoying URL.
* And many more features I don't tell here.

= Special =
Not only posts/page, you can also call some functions via URL.

Examples:

* `example.com/get/wp_list_categories` to displays list categories in JSON format
* `example.com/get/wp_list_pages` to displays list pages
* `example.com/get/wp_nav_menu` to displays menus

Hei, how about $args of those functions? 
* `example.com/get/wp_list_categories?args[orderby]=ID&args[exclude]=1,3,4&args[child_of]=2` etc. cool, right?

== Other notes ==
* Use subdomain API is recommended to ensure all links/permalinks are rewritten to API URL.

== Installation ==

There are many ways to install this plugin, e.g:

1. Upload compressed (zip) plugin using WordPress plugin uploader.
2. Install using plugin installer in WordPress dashboard.
3. Upload manually uncompressed plugin file using FTP.

== Frequently Asked Questions ==

none

== Screenshots ==

1. Default
2. Add debug=1 query to display JSON in pretty format
3. You can also use print_r=1 for debugging purpose
4. Use callback for JSONP access
5. Settings page


== Upgrade Notice ==

none

== Changelog ==

= 0.3 =
* /json endpoints now works accross site, (previously only on page or post)

= 0.2 =
* exclude_query now run after result is formatted
* function_exists check on `easy_get_image` library
* permalink/site_url changed only if API domain is configured
* fixed `json` endpoint, previously required to pass value json/1
* fix notice errors
* improved API access check
* fix 404 result

= 0.1 =
* First release
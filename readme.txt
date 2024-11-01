=== Webglobe Purge Cache ===
Contributors: webglobe-yegon
Tags: nginx, proxy, cache, purge, webglobe purge cache, webglobe cache, webglobe plugin, wordpress hosting
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatic cache purge when the content is updated. Works only with specialized hosting plans from Webglobe.


== Installation ==

1. Unzip Webglobe Purge Cache plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy


== Frequently Asked Questions ==

= What are the requirements? =
Hosting plan from Webglobe available on [Webglobe.sk](https://www.webglobe.sk/). 

= Do I have to set up the plugin after installation? =
No, all it takes is to activate the plugin. 

= How does this plugin work? =
After your publish or update a page or post this plugin creates a list or url's to purge.  For instance if the page is http://www.example.com/about/, the plugin creates a url of http://www.example.com/purge/about/.  After creating all the urls to purge, the plugin opens each of the urls. This cleans all cache files a provide fresh content on the page. 


== Changelog ==
= 1.1 =
* Added button to purge all cached content

= 1.0 =
* Initial Release.

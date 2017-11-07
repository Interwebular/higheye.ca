=== Plugin Name ===
Contributors: axiomcode
Donate link: http://axiomcode.com
Tags: post excerpt shortcode, page exceprt shortcode, custom post type excerpt shortcode
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will allow you to add post/page/custom post type excerpt anywhere on your page. Just specify the id. There are number of layout available.

== Description ==
This plugin will allow you to add post/page/custom post type excerpt anywhere on your page using shortcode. Just specify the id. There are number of layouts available. You can use the featured image or specify another image to be used.


[excerpt-everywhere id=23]

* word_count= 30 (integer)
* t= 4 (1-4) - the excerpt layout
* thumb_size = 300200 (300200/200/100/10050/50)
* src = full image url (if you want to use image other than the featured image)
* is_icon = 1 (1/0) whether use the image as <img> or as background
* type = page/post/custom post type
* hide_title = 1/0 - do not show title
* content = custom content

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `excerpts-everywhere.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add shortcodes to widgets or post editor
4. You can also add the shortcode in your theme
   <?php echo do_shortcode('[excerpt-everywhere id=23]') ?>

== Frequently Asked Questions ==

= The image size I set did not work =

For images in media that were uploaded prior to the installation of this plugin, you have to go though them one by one and click on Rebuild Thumbnails.


== Screenshots ==

1. demo 1

2. demo 2

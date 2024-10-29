=== A.T. Many themes ===
Contributors: alexey-tomorovich
Tags: mobile, multiple, themes, switch, landing
Requires at least: 4.3
Tested up to: 4.7.3
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin help you to change theme for mobile, specific pages, posts, categories. It can be useful for creating mobile application, landing page, A/B testing, etc.

== Description ==

This plugin help you to change theme for mobile, specific pages, posts, categories. 
It can be useful for creating mobile application, landing page, A/B testing and others pages which need to use another design.

How it works:

There are six levels for switching themes:

1. Mobile. You can add a new theme for mobiles applications. 
This level cannot be overridden by next levels.

2. Posts. You can add a new theme for each posts that you want. 
This levell can be overridden by mobile level.

3. Pages. You can add a new theme for any page. 
This levell can be overridden by mobile level.

4. Categories. You can add a new theme for any category. Also you have opportunity to extend a new theme for all posts of this category.
This level can be overridden by mobile, posts level.

5. Regular expression. If you know how write regular expression, you can make rule for a new theme. Plugin will be check url with your regular expression.
This level can be overridden by mobile, posts, pages, categories level.

6. Main theme. This means that new theme will extends for all pages of your website.
This level can be overridden by each previous level.


For working with plugin you have next interface.
Dropdowns which contain posts/categories/pages. 
Near them you see dropdowns with themes and button "plus". 
When you select any post/category/page and theme in them, you should click on plus.
That mean new theme will tied with choosing post/category/page and display on public site when user open this post/category/page.
You can add many rules. There are no limit to the number.

 

== Installation ==

Use WordPress' Add New Plugin feature, searching "A.T. Many themes", or download the archive and:

1. Unzip the archive on your computer  
2. Upload `at-many-themes` directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings -> A.T. Many themes and customize behaviour as needed

== Changelog ==

= 1.1.1 =
Fixed a bug with "Save&Continue" theme activation.
Fixed a bug with auto-deleting widgets.

= 1.1.0 =
Adding possibility changing theme for mobiles.


== Screenshots ==

1. Very simple interface to start working.
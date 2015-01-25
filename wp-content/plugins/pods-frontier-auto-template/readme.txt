=== Pods Frontier Auto Template ===
Contributors: shelob9, sc0ttkclark, pglewis, Desertsnowman
Tags:  content types, custom fields, custom post types, custom taxonomies, pods, post types
Donate link: http://podsfoundation.org/donate/
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 1.1.2
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatic front-end output of Pods Templates.

== Description ==
Pods Frontier Auto Display is a plugin that allows you to easily output Pods Templates for your Pods custom post types, extended posts and pages and taxonomies. With this plugin, Pods provides a complete solution for creating custom content types, adding fields to them and outputting the custom fields for your custom content types without writing any PHP code or modifying your theme file. Using WordPress as a CMS has never been easier, and requires only a basic knowledge of html and CSS.

Once it this plugin is activated, you will see a new tab "Pods Frontier Auto Templates" in the Pods editor for compatible post types. Simply set the name of the Pods Templates you'd like to use for single and archive view in that tab.

Requires [Pods 2.3.18](http://wordpress.org/plugins/pods/) or later.

Supports only post types, built-in and extended as well as custom taxonomy Pods.

For usage instructions, please see [this article on our site](http://pods.io/tutorials/creating-pods-plugins/using-pods-display/).

== Installation ==
1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin\'s name

== Frequently Asked Questions ==
= Where Do I Set The Names Of Pods Templates To Use =
Once the plugin is activated you should see a tab called "Frontier Auto Template Options" in the Pods editor for compatible post types.

= Can I Use The Same Template For Both Single and Archive View =
Yes, you can. Simply enter the same template name in both settings field.

= What If The Template Doesn't Exist? =
If the template name that you specify is not the name of a Pods Template in your site nothing happens. There will simply be no output. There will also be no error.

= Is There A Detailed Tutorial About How To Use This Plugin? =
Why, yes there is: [Right here.](http://pods.io/?p=182352)

= I'd Like To Make My Own Pods Plugin, How Can I Do That? =
Wow. That's awesome. Writing a Pods plugin is a great way to contribute to the community. We have a [whole tutorial series about creating Pods plugins](http://pods.io/?p=182353) and a [Pods plugin starter plugin](https://github.com/pods-framework/pods-extend) to help you get started.

= What If I Have Another Question? =
Ask it in the Pods [support forums](http://pods.io/forums/) or in #pods on irc.freenode.net, which is accessible via a web browser at: [http://pods.io/forums/chat/](http://pods.io/forums/chat/)

= Where Can I Learn More? =
[In this helpful tutorial](http://pods.io/?p=182352).

== Screenshots ==
1. Settings for a custom post type.
2. Settings for a custom taxonomy.
3. Using the same template for both views.


== Changelog ==
= 0.1.0 =
First WPORG release.
= 1.0.0 =
* Add option to replace post content with template instead of appending it.
* Improved context detection in Pods_PFAT_Frontend::front()
* Using pods_transient_set()/ pods_transient_get() for transient caching.
= 1.1.0 =
* Fix issue that caused error when @post_content was used in template.
* More options for template location and the ability to set template location with a drop-down.
* Optional template selection from drop-down menu. (Requires PFAT_TEMPLATE_SELECT_DROPDOWN constant to be defined as true ).
* Optionally act on the_excerpt as well as the_content.
* Added an admin nag if a archive template is set for a post type without archives.
* New filter: 'pods_pfat_auto_pods_override' to override settings and prevent API calls.
* New filter: 'pods_pfat_auto_pods' to change or edit settings after they are retrieved via the API.
* Output Pods Frontier script and style fields.

== Upgrade Notice ==
= 0.2.0 =
Now includes an option to replace post content with the template instead of appending it and more efficient code.
= 1.0.0 =
First WPORG release.
= 1.1.0 =
New options for where to place the template, as well as bug fixes and settings UI improvements.
= 1.1.1 =
Fixes for several issues and improves settings UI. For more information see: http://pods.io/?p=185097
= 1.1.2 =
Prevent admin nag about post types not having archive support for posts/pages/attachment
Corrected name of admin tag.
Added plugin icon to assets.

=== CUSTOM OPTIONS PLUS POST IN ===
Contributors: gqevu6bsiz
Donate link: http://gqevu6bsiz.chicappa.jp/please-donation/?utm_source=wporg&utm_medium=donate&utm_content=coppi&utm_campaign=1_4_1
Tags: admin, option, shortcode, post, custom, template
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 1.4.1
License: GPL2

This plugin is create to custom options in your WordPress. You can use in the Template and Shortcode.

== Description ==

This plugin is manage of custom options for site.

You can create the options easily.
And, you can use for Template.
`<?php echo get_coppi( 'example' ); ?>`.

And then, you can use to Shortcode for Post.
`[coppi key="example"]`

If you create a lot of options, You can manage the category for custom options.

== Installation ==

1. Upload the `custom-options-plus-post-in` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to `WP-Admin -> Settings -> Custom Option (coppi)` to configure the plugin.

= Usage =

1. For Template Tags: `<?php echo get_coppi('example'); ?>`.
2. For Shortcode: `[coppi key="example"]`.

== Frequently Asked Questions ==


== Screenshots ==

1. Settings Interface
2. Created Lists Interface
3. Memo
4. Usage Example for Post
5. Usage Example for Template
6. Display Example

== Changelog ==

= 1.4.1 =
* Fixed: Change to get the current user role.

= 1.4 =
* Updated: Specification changes of Multisite environment.
* Updated: Change to program of structure.

= 1.3.2.1 =
* Updated: Check the version compatible.

= 1.3.2 =
* Added: Changed to Ajax how to update for Update and Delete.
* Fixed bug: Changed the data submit of category.
* Fixed bug: Submit to process in the case empty of data.

= 1.3.1 =
* Fixed bug: Database Upgrade.

= 1.3 =
* Updated the translation.
* Enhancement: Manage in category.
* Enhancement: Bulk delete.
* Enhancement: Added to Memo for manage.

= 1.2.3 =
* Support SSL.
* Check to 3.6.

= 1.2.2 =
* Added a confirmation of Nonce field.
* Checked Compatibility.
* Added plugin information.

= 1.2.1 =
Create a table of only plugin activated.

= 1.2 =
I've changed the format for storing data.
contains information about the plugin author.

= 1.1.1 =
Update the date of creation date bug.

= 1.1 =
It is now possible to sort.

= 1.0.2 =
I've changed the readme.txt.

= 1.0.1 =
Fixed the translation.

= 1.0 =
This is the initial release.

== Upgrade Notice ==

= 1.0.1 =
Fixed the translation.

== 日本語でのご説明 ==

このプラグインは、オプションの値を追加できるようにするプラグインです。
作成したオプション値は、テンプレートで使用でき、
記事本文でショートコードとしても使用できます。
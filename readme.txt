=== Karlog-IT Simple SSL ===
Contributors: vhgkarlogit
Tags: Simple SSL, SSL, HTTPS, HTTP, Redirect
Tested up to: 5.5
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress plugin that redirects your site to HTTPS

== Description ==
## Simple SSL ##
With Simple SSL you can enable HTTPS redirect for your website that is running on a Apache-based webserver.
It is required to have a valid SSL certificate for your site that we do not provide.

## What does the plugin do? ##
It configures your .htaccess file which is a configuration file used by Apache.
We add the following setting that configures your website to redirect all traffic to HTTPS.
``
`RewriteEngine On`
`RewriteCond %{HTTPS} !=on`
`RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]`
``
We also automatically update your general settings to use HTTPS instead of HTTP.
You can easily enable and disable all this from the admin page.

== Installation ==
* Download the plugin and activate it on your plugins page.
* A new menu will appear in your dashboard named \"Simple SSL\".
* Go to the \"Simple SSL\" menu and enable the plugin.
* Simple as that!

== Frequently Asked Questions ==
= I cant access my website after i have enabled the plugin? =

This is because you don\'t have a valid SSL certificate required for your website.
The site now redirects all HTTP traffic to HTTPS, and it can\'t do that when no certificate is provided.

= How do i manually revert the plugin changes? =

* First, make sure you can access your site with either FTP or source control.
.htaccess
* Go to your project directory and open your .htaccess file.
* Remove all lines between \'# BEGIN HTTPS DIRECT\' and \'# END HTTPS DIRECT\'.
General Settings
* Go to your wp-config.php, also in your project directory.
* Add the following lines to the file (With your domain name).
 `define( \'WP_HOME\', \'http://{YOUR DOMAIN NAME}\' );`
 `define( \'WP_SITEURL\', \'http://{YOUR DOMAIN NAME}\' );`

== Screenshots ==
1. Admin Dashboard

== Changelog ==
= 1.1 =
* Fixed appending whitespace when updating .htaccess

= 1.0 =
* Initial version.
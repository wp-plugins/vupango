=== VuPango ===
Contributors: ydp
Donate link: http://tangointervention.org/
Tags: video, art, performance, activism
Requires at least: 3.0
Tested up to: 3.0.5
Stable tag: 1.0.2

VuPango allows for the setup of remote art installations involving multiple cameras

== Description ==

VuPango adds multiple live streams of video to your blog. Video can come from cell phones anywhere in the world, and are shown on a Google map. Ideal for artists, performers, or activists creating worldwide events. Join the revolution.

== Installation ==

1. Upload the `vupango` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where is my VuPango page? =

VuPango's page is treated exactly like any other page on your Wordpress site and can be found in the Pages section of the Admin Panel. If your theme supports it, it will also be listed in your navigation by default.

= The streams aren't displaying at the proper times, what's wrong? =

Make sure that you have entered your timezone in Wordpress' General Settings. It's important that you pick a city in your timezone so that DST can be calculated correctly.

= What is open signup? =

Open signup allows people to sign up through the VuPango page on your wordpress site.

= How can I totally delete VuPango without leaving a trace? =

In VuPango's settings, select 'Destroy data on deactivation' and then deactivate VuPango from the Plugins menu.

= Can I edit the camera stream data? =

Unfortunately, that is not possible in this version. To edit a stream, simply delete it and re-enter it as a new camera stream.

== Screenshots ==

1. This is an example of what VuPango looks like on the default theme.
2. This is what shows before an event. If you have open signup enabled, another box will appear below that will allow users to enter their own cameras.
3. This shows when an event has been completed.
4. This is the VuPango Event screen in the Admin Panel.
4. This is the VuPango Cameras Stream Settings screen in the Admin Panel.
4. This is the VuPango Settings screen in the Admin Panel.

== Changelog ==

= 1.0.2 =

* Fixed a bug where using bambuser would load test code

= 1.0.1 =

* Fixed a bug related to Wordpress's handling of the date functions
* Timezones are now fully supported

= 1.0 =
* This was the first stable version
* The admin logic was finalized
* The viewer-facing code was completed

= 0.5b =
* This was a semi-functioning version
* The entire admin system was fleshed out

= 0.1b =
* This was a non-functioning version
* The admin menus were completed

== Upgrade Notice ==

= 1.0.2 =
This update fixes a bug that would load test code instead of the proper bambuser accounts.

= 1.0.1 =
This update fixes a timing related bug. Upgrade immediately.

= 1.0 =
This is the first stable version.

== Services ==

For the most seamless installation, use qik. It's the least obtrusive of the three. At the time of this writing they offer unbranded streams (when the controls aren't visible).
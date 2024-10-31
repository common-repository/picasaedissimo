=== Picasaedissimo ===
Contributors: chaos0815
Tags: widget, picasa, images, sidebar, plugin
Requires at least: 2.0.2
Tested up to: 3.2.1
Stable tag: 1.7

Displays photos from a Picasa Webalbum RSS feed.

== Description ==

Picasaedissimo is a plugin to display the latest or random images from a selected Picasa Webalbum RSS feed in your sidebar.

This is a modified version of the Picasaed plugin by [Jon Link](http://taisteal.atomiclemur.com/picasaed/ "original Picasaed"), since his stopped working and it seems he stopped developing it.

For comments and suggestions drop by at [www.gofloripa.net](http://www.gofloripa.net/picasaedissimo/ "Picasaedissimo comments").

== Installation ==

1. Upload `plugin-name.php` and the `langauges` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Presentation -> Widgets and add the widget to your sidebar
1. Adjust position of the widget on the sidebar (optional)
1. Click 'Edit' next to configure the widget:
1.1. Enter the url of the Picasa RSS feed
1.1. Enter an optional widget title
1.1. Enter the number of images you want the widget to display
1.1. Check 'Randomize Images' if you, yes you guessed it, want the widget to display random images from the feed
1. Save your settings

== Frequently Asked Questions ==

= How can i adjust the look of the Widget? =

Picasaedissimo uses some basic inline styles to make it work right out of the box. However it can be easily adjusted through your CSS stylesheet.
Use the CSS-Class "picasaedissimo_image" to adjust the image appearance, and the "picasaedissimo" ID to adjust the ul and li tags.
e.g.

`.picasaedissimo_image {
  width: 14em !important;
  padding: 1px;
  border: solid 4px #fff;
}
#picasaedissimo ul li {text-align:center;}`

Notice the `!important` to override the inline width of the picasaedissimo_image class.

= My Widget does not show any images! What's wrong? =
 
Most likely the url you supplied contains an error or does not point to an valid Picasa Album feed.
Make sure the url points to a valid Picasa ALBUM feed and NOT to the Picasa account feed showing your albums.

= How can I avoid the image filenames beeing displayed while the images are loading? =

This can not be avoided. What you are seeing is the alternate text of the image for people not wanting or beeing able to see images.
You can, however, supply a caption for each image in Picasa. When a caption is found, Picasaedissimo will use that as the alternate text instead of the filename.

== Screenshots ==

1. Configuration of the widget.
2. Sample of widget in the sidebar.

== Changes ==
`V 1.2 Added i18n. Now including pt_BR, de_DE and en_US feel free to update them since i only whopped them up real quick.
V 1.3 Now using Snoopy instead of file_get_contents.
V 1.4 Better error handling
V 1.4.1 added italian language files (thanks to Gianni Diurno(http://gidibao.net/)
V 1.5 Added some debugging information (feed url) in HTML code. Fixed bug where images where not parsed when they had a different a/r.
V 1.6 some more error handling in the case we get a feed, but can't parse any pictures from it. added License information.`
V 1.7 Added inline styles so it looks acceptable even with no additional styles applied. Updated FAQs.
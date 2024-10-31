<?php
/*  Copyright 2008  Christian Wolf  (email : christian.wolf[at]gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin Name: Picasaedissimo
Plugin URI: http://www.gofloripa.net/picasedissimo/
Description: Displays pictures from a Picasa RSS feed.
Author: Christian Wolf
Version: 1.7
Author URI: http://www.gofloripa.net/
Remarks: Based heavily on Jon Links Picasaed found on http://taisteal.atomiclemur.com/picasaed/ 
*/

function widget_picasaedissimo($args){
	extract($args);
	$options 	= get_option('widget_picasaedissimo');
	$title 		= $options['title'];
	$feed 		= $options['feed'];
	$number 	= $options['number'];
	$randomize 	= $options['randomize'];

	require_once(ABSPATH.WPINC.'/class-snoopy.php');
	$snoopy = new Snoopy;
	if(@$snoopy->fetch($feed)){
		$feed_content = $snoopy->results;
	}
	else {
		echo $before_widget . $before_title . $title . $after_title . '<font color="#ff0000">';
		_e('feed error:', 'picasaedissimo');
		echo ' ' . $snoopy->error . '</font>' . $after_widget;
		return;
	}

	// get items
	preg_match_all("/<item.+?<\/item>/si",$feed_content,$items,PREG_SET_ORDER);

	$itemSize = count($items);

	if ($itemSize < 1) {
		echo $before_widget . $before_title . $title . $after_title . '<font color="#ff0000">';
		_e('no images found in feed.', 'picasaedissimo');
		echo '</font>' . $after_widget;
		return;
	}
	
	if($randomize) {
		shuffle($items);
	}

	if ($number < $itemSize) {
		//cut down to last n items
		$items = array_reverse(array_slice($items, -$number, $number));
	}

	$i = 0;
	foreach ($items as $item) {
		// parse data from each item
		preg_match("/<title>(.*)<\/title>/si",$item[0],$fotoTitle);
		preg_match("/media:thumbnail url=[\"']([^\"']+)[ a-z='0-9]*288'/si",$item[0],$fotoUrl);
		preg_match("/<link>(.*)<\/link>/si",$item[0],$fotoLink);

		// and store it
		$fotos[$i]['title'] = $fotoTitle[1];
		$fotos[$i]['url'] = $fotoUrl[1];
		$fotos[$i]['link'] = $fotoLink[1];
		$i++;
	}

	// check if $fotos has elements
	if (count($fotos) < 1) {
		echo $before_widget . $before_title . $title . $after_title . '<font color="#ff0000">';
		_e('no images found in feed.', 'picasaedissimo');
		//echo "\n\r<!-- $additionalMsg. -->\n\r";
		echo '</font>' . $after_widget;
		return;
	}

	// mount html
	$picasa_then = "\n\r<!-- Picasaedissimo Widget -->";
	$picasa_then .= "\n\r<!-- showing feed $feed -->\n\r";
	$picasa_then .= '<ul>'."\n\r";

	// TODO: check if $fotos has elements beforehand
	foreach($fotos as $foto) {
		$the_title = ($foto['title'])? $foto['title'] : 'Picasa Foto';
		$picasa_then .= "\t".'<li><a href="'.$foto['link'].'" title="'.$foto['title'].'"><img class="picasaedissimo_image" style="width: 100%;" src="'.$foto['url'].'" alt="'.$the_title.'" /></a></li>'."\n\r";
	}
	$picasa_then	.= '</ul>'."\n\r";

	// output html
	echo $before_widget . $before_title . $title . $after_title . $picasa_then . $after_widget;

}

//----control panel
function widget_picasaedissimo_control(){
	$options = $newoptions = get_option('widget_picasaedissimo');
	if($_POST['picasaedissimo_submit']){
		$newoptions['title']	= strip_tags(stripslashes($_POST['picasaedissimo_title']));
		$newoptions['feed']		= strip_tags(stripslashes($_POST['picasaedissimo_feed']));
		$newoptions['randomize'] 	= isset($_POST['picasaedissimo_randomize']);
		$newoptions['number'] 	= strip_tags(stripslashes($_POST['picasaedissimo_number']));
	}
	if($options != $newoptions){
		$options = $newoptions;
		update_option('widget_picasaedissimo', $options);
	}
	$title 		= wp_specialchars($options['title']);
	$feed 		= wp_specialchars($options['feed']);
	$number		= wp_specialchars($options['number']);
	$randomize	= $options['randomize'] ? 'checked="checked"' : '';

?>
<p>
	<label for="picasaedissimo_feed"><?php _e('Picasa RSS feed:', 'picasaedissimo'); ?></label>
	<input style="width: 100%;" id="picasaedissimo_feed" name="picasaedissimo_feed" type="text" value="<?php echo $feed; ?>" />
</p>
<p>
	<label for="picasaedissimo_title"><?php _e('Widget Title (optional):', 'picasaedissimo'); ?></label>
	<input style="width: 100%;" id="picasaedissimo_title" name="picasaedissimo_title" type="text" value="<?php echo $title; ?>" />
</p>
<p>
	<label for="picasaedissimo_number"><?php _e('Number of images to display:', 'picasaedissimo'); ?></label>
	<input style="width: 30%;" id="picasaedissimo_number" name="picasaedissimo_number" type="text" value="<?php echo $number; ?>" />
</p>	
<p>
	<label for="picasaedissimo_link"><?php _e('Randomize Images:', 'picasaedissimo'); ?></label>
	<input class="checkbox" type="checkbox" <?php echo $randomize; ?> id="picasaedissimo_link" name="picasaedissimo_randomize" />

</p>
<input type="hidden" id="picasaedissimo_submit" name="picasaedissimo_submit" value="1" />
<?php
}

function picasaedissimo_widget_init(){
	load_plugin_textdomain('picasaedissimo', 'wp-content/plugins/languages');
	register_widget_control('Picasaedissimo', 'widget_picasaedissimo_control', 485, 230);
	register_sidebar_widget('Picasaedissimo', 'widget_picasaedissimo');
}
add_action('init', 'picasaedissimo_widget_init');
?>
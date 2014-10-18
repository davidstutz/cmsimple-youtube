<?php
/* utf8-marker = äöüß */
/**
 * @file index.php
 * @brief Containing the functions used by the user.
 * 
 * @author David Stutz
 * @version 1.3.0
 * @license GPLv3
 * @package youtube
 * @see http://sourceforge.net/projects/cmsimpleyoutube/
 * 
 *  Copyright 2011 - 2014 David Stutz
 * 
 * 	This file is part of the youtube gallery plugin for CMSimple.
 *
 *  The plugin is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The plugin is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  See <http://www.gnu.org/licenses/>.
 */

if (!class_exists('Youtube')) require dirname(__FILE__) . "/youtube.php";

/**
 * Plugins main funciton to output videos in frontend.
 * 
 * @param <string> gallery
 * @return <string> output
 */
function youtube($gallery)  
{
	/* Globals. */
	global $pth,$plugin,$plugin_cf,$plugin_tx;
	$plugin = basename(dirname(__FILE__),"/");
	
	Youtube::check_dir();
	
	/* Initialize output. */
	$o = '';
	
	/* Check for gallery. */
	if (!Youtube_Gallery::gallery_exists($gallery))
	{
		return Youtube::$tx['Youtube gallery not found.'];
	}
    
	/* Get the galelry object and the videos. */
    $gallery = new Youtube_Gallery($gallery);
	$videos = $gallery->videos();
	
	/* Sort and print all videos. */
	usort($videos, "youtube_sort_asc"); //sort videos with function cmp(), so sort 'order' ascending
	
	/* Print out each video in a separate div with title and description. */
	foreach ($videos as $video)
	{
		/* Title. */
		if ($video->has_title())
		{
			$o .= '<div class="youtube-title">' . $video->title() . '</div>';
		}
		
		/* Video. */
		$o .= '<div class="youtube-video"><iframe title="' . $video->title() . '" width="' . $video->width() . '" height="' . $video->height() . '" src="http://www.youtube.com/embed/' . $video->yid() . '" frameborder="0"></iframe></div>';
		
		/* Description. */
		if ($video->has_description())
		{
			$o .= '<div class="youtube-description">' . HTML::decode_entities($video->description()) . '</div>';
		}
		
		/* Old youtube embed code. */
		//$o .= '<object width="480" height="390"><param name="movie" value="http://www.youtube.com/v/'.$ids_array[$i].'?fs=1&amp;hl=de_DE"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$ids_array[$i].'?fs=1&amp;hl=de_DE" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="390"></embed></object>';
	}
	
	/* Return output. */
	return $o;
}
?>
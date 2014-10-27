<?php
/* utf8-marker = äöüß */
/**
 * @file youtube.php
 * @brief Containing class Youtube, includes Youtube_Gallery, Youtube_Video and init the plugin.
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
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  See <http://www.gnu.org/licenses/>.
 */
/*! \mainpage CMSimple Youtube Plugin
 *
 * This plugin simplifies the creation and management of youtube vidoe galleries.
 *
 * This is  a generated documentation of the plugin.
 * 
 * \mainpage
 */
 
/* Require CSV. */
if (!class_exists('CSV', FALSE)) require_once dirname(__FILE__).'/helper/csv.php';

/* Require HTML. */
if (!class_exists('HTML', FALSE)) require_once dirname(__FILE__).'/helper/html.php';

/* Require Validation. */
if (!class_exists('Validation', FALSE)) require_once dirname(__FILE__).'/helper/validation.php';

/* Require image class. */
if (!class_exists('Youtube_Gallery', FALSE)) require_once dirname(__FILE__).'/youtube/gallery.php';
 
/* Require gallery class. */
if (!class_exists('Youtube_Video', FALSE)) require_once dirname(__FILE__).'/youtube/video.php';

Youtube::init();
 
if (!function_exists('page_url'))
{
	/**
	 * Detect root.
	 * 
	 * @return <string> root
	 */
	function page_url()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"] == "on")
		{
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}
 
/**
 * @class Youtube
 * 
 * Main youtube class.
 * Provides plugin's configuration, translation and all paths needed.
 * 
 * @author David Stutz
 * @since 1.1.0
 */
class Youtube {
	
	/**
	 * @static
	 * public
	 * Current version.
	 */
	const VERSION = '1.3.1';
	
	/**
	 * @static
	 * public
	 * Plugin config.
	 * 
	 * @var <array>
	 */
	public static $cf;
	
	/**
	 * P@static
	 * public
	 * lugin translation.
	 * 
	 * @var <array>
	 */
	public static $tx;

	/**
	 * @static
	 * public
	 * CSV path.
	 * 
	 * @var <string>
	 */
	public static $csv;
	
	/**
	 * @static
	 * public
	 * Path to images.
	 * 
	 * @var <string>
	 */
	public static $images;
	
	/**
	 * @public
	 * @static
	 * Get plugin's name.
	 * 
	 * @return <string> name
	 */
	public static function name()
	{
		return "Youtube Gallery Plugin";
	}
	
	/**
	 * @public
	 * @static
	 * Get plugin's release date.
	 * 
	 * @return <string> release date.
	 */
	public static function release_date() 
	{
	   return "27th October 2014";
	}
	
	/**
	 * @public
	 * @static
	 * Get plugin's author.
	 * 
	 * @return <string> author.
	 */
	public static function author()
	{
		return "David Stutz";
	}
	
	/**
	 * @public
	 * @static
	 * Get plugin's website.
	 * 
	 * @retrun <string> website link
	 */
	public static function website()
	{
		return '<a href="http://davidstutz.de/cmsimple/?Youtube" target="_blank">Project Webpage</a>';
	}
	
        /**
	 * @public
	 * @static
	 * Get plugin's GitHub repo.
	 * 
	 * @retrun <string> GitHub link
	 */
	public static function github()
	{
		return '<a href="https://github.com/davidstutz/cmsimple-youtube" target="_blank">GitHub Repository</a>';
	}
        
	/**
	 * @public
	 * @static
	 * Get plugin's description.
	 * 
	 * @return <string> description
	 */
	public static function description()
	{
		return 'This is a simple plugin to create youtube video galleries. You can define a title and descirption to every video and individually set the order of the videos in each gallery. You only need the Youtube ID of the videos.';
	}
	
	/**
	 * @public
	 * @static
	 * Init the plugin. Set all required variables needed.
	 * 
	 * @global pth
	 * @global plugin_tx
	 * @global plugin_cf
	 */
	public static function init()
	{
		/* Globals. */
		global $pth,$plugin,$plugin_cf,$plugin_tx;
		$plugin = basename(dirname(__FILE__),"/");
		
		Youtube::$cf = $plugin_cf[$plugin];
		Youtube::$tx = $plugin_tx[$plugin];
		Youtube::$csv = $pth['folder']['base'].Youtube::$cf['csv_filepath'].'/';
		Youtube::$images = $pth['folder']['plugins'] . $plugin . '/images/';
	}
	
	/**
	 * @public
	 * @static
	 * Get plugin's legal notes.
	 * 
	 * @return <string> legal
	 */
	public static function legal()
	{
		return 'This plugin is published under the GNU Public License version 3. See <a href="http://www.gnu.org/licenses/">Licenses</a> for more information.';
	}
	
	/**
	 * @public
	 * @static
	 * Checks gallery dir.
	 */
	public static function check_dir()
	{
		/* Globals. */
		global $pth,$plugin,$plugin_cf;
		$plugin = basename(dirname(__FILE__),"/");
		
		if (!is_dir(Youtube::$csv))
		{
			/* Recursively create folders. */
			mkdir(Youtube::$csv, 0777, TRUE);
		}
		
		/* Chmod. */
		chmod(Youtube::$csv, 0777);
		
		/* Open dir. */
		$dir = dir(Youtube::$csv);
		if (is_object($dir))
		{
			while (FALSE !== ($file = $dir->read()))
			{
				if (is_dir($file))
					continue;
				
				chmod(Youtube::$csv . $file, 0777);
			}
		}
	}
}

/**
 * Function to order videos by their ordering number. 
 * 
 * @param array a
 * @param array b
 * 
 * @return <int> order
 */
function youtube_sort_asc($a, $b)
{
    if ($a->position() == $b->position())
	{
        return 0;
    }
    return ($a->position() < $b->position()) ? -1 : 1;
}

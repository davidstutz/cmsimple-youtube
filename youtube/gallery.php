<?php
/* utf8-marker = äöüß */
/**
 * @file gallery.php
 * @brief Containing class Youtube_Gallery.
 * 
 * @author David Stutz
 * @version 1.3.0
 * @license GPLv3
 * @package youtube
 * @see http://sourceforge.net/projects/cmsimpleyoutube/
 * 
 *  Copyright 2011 - 2018 David Stutz
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

/** 
 * @class Youtube_Gallery
 * @public
 * 
 * Gallery class.
 * 
 * @author David Stutz
 * @since 1.3.0
 * @package youtube
 */
class Youtube_Gallery {
	
	/**
	 * @private
	 * Gallery name.
	 * 
	 * @var <string>
	 */
	private $_name;
	
	/**
	 * @private
	 * videos.
	 * 
	 * @var <array>
	 */
	private $_videos = FALSE;
	
	/**
	 * @private
	 * Constructor for a new category.
	 * 
	 * @param <string> name
	 * @return <object> category
	 */
	public function __construct($name)
	{
		$name = preg_replace("#[\t\n\r]#", '', str_replace(' ', '-', $name));
		
		/* Check for file. */
		if (!file_exists(Youtube::$csv . $name . '.csv'))
		{
			/* Open list CSV. */
			$list = fopen(Youtube::$csv . $name . '.csv', "w+");
			
			/* Created list? */
			if (FALSE !== $list) 
			{
				/* Close file. */
				fclose($list);
			}
		}
		else
		{
			$this->_name = $name;
		}
	}
	
	/**
	 * @private
	 * Get category name.
	 * 
	 * @return <string> name
	 */
	public function name()
	{
		return $this->_name;
	}
	
	/**
	 * @private
	 * @static
	 * Get all categories.
	 * 
	 * @return <array> categories
	 */
	public static function galleries()
	{
		$galleries = array();
		
		/* Open dir. */
		$dir = dir(Youtube::$csv);
		while (FALSE !== ($file = $dir->read()))
		{
			if (is_dir($file))
				continue;
			
			if (!preg_match('#.*\.csv$#', $file))
				continue;
			
			$galleries[] = new Youtube_Gallery(preg_replace('#\.csv$#', '', $file));
		}
		
		return $galleries;
	}
	 
	/**
	 * @public
	 * @static
	 * Checks whether category exists.
	 * 
	 * @param <string> name.
	 * @return <boolean> exists
	 */
	public static function gallery_exists($name)
	{
		return file_exists(Youtube::$csv . $name . '.csv');
	}
	 
	/**
	 * @public
	 * Check for video in this gallery.
	 * 
	 * @param <string> id
	 * @return <boolean> exist
	 */
	public function video_exists($id)
	{
		$videos = $this->videos();
		
		foreach ($videos as $video)
		{
			if ($video->id() == $id)
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	 
	/**
	 * @public
	 * Gets all videos of a gallery.
	 * 
	 * @uses CSV::parse
	 * @return <array> entries
	 */
	public function videos() 
	{
		$array = CSV::parse(Youtube::$csv . $this->_name . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
		
		$videos = array();
		foreach ($array as $content)
		{
			$videos[] = new Youtube_Video($content[0], $this);
		}
		
		return $videos;
	}
	
	/**
	 * @public
	 * Remove gallery.
	 * 
	 * @param <string> name
	 */
	public function remove()
	{
		/* Remove CSV file. */
		chmod(Youtube::$csv . $this->_name . '.csv', 0777);
		unlink(Youtube::$csv . $this->_name . '.csv');
	}
}
?>
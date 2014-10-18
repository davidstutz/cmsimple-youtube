<?php
/* utf8-marker = äöüß */
/**
 * @file video.php
 * @brief Containing class Youtube_Video.
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

/**
 * @class Youtube_Video
 * @public
 * 
 * Video class.
 * 
 * @author David Stutz
 * @since 1.3.0
 * @package youtube
 */
class Youtube_Video {
	
	/**
	 * @private
	 * ID.
	 * 
	 * @var <double>
	 */
	private $_id;
	
	/**
	 * @private
	 * Title.
	 * 
	 * @var <string>
	 */
	private $_title;
	
	/**
	 * @private
	 * Description.
	 * 
	 * @var <string>
	 */
	private $_description;
	
	/**
	 * @private
	 * Youtube id.
	 * 
	 * @var <string>
	 */
	private $_yid;
	
	/**
	 * @private
	 * State.
	 * 
	 * @var <double>
	 */
	private $_position;
    
    /**
     * @private
     * Height.
     */
    private $_height;
    
    /**
     * @private
     * WIdth.
     */
    private $_width;
    
	/**
	 * @private
	 * Gallery.
	 * 
	 * @var <object>
	 */
	private $_gallery;
	
	/**
	 * @public
	 * Constructs a new video.
	 * 
	 * @param <string> id
	 * @param <object> category
	 * @return <object> entry
	 */
	public function __construct($id, $gallery)
	{
		/* Get content of category. */
		$this->_gallery = $gallery;
		$this->_id = (double)$id;
		
		$array = CSV::parse(Youtube::$csv . $this->_gallery->name() . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
		foreach ($array as $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				$this->_yid = $content[1];
				$this->_title = $content[2];
				$this->_description = $content[3];
				$this->_position = $content[4];
                if (isset($content[5]))
                {
                    $this->_width = $content[5];
                }
                if (isset($content[6]))
                {
                    $this->_height = $content[6];
                }
			}
		}
	}
	
	/**
	 * @public
	 * Getter for ID.
	 * 
	 * @return <integer> id
	 */
	public function id()
	{
		return $this->_id;
	}
	
	/**
	 * @public
	 * Youtube ID.
	 * 
	 * @return <string> youtube id
	 */
	public function yid($yid = NULL)
	{
		if ($yid === NULL)
		{
			return $this->_yid;
		}
		else
		{
			$this->_yid = $yid;
		}
	}
	
	/**
	 * @public
	 * Detects whether video has a not empty title.
	 * 
	 * @return <boolean> has title
	 */
	public function has_title()
	{
		return !empty($this->_title);
	}
	
	/**
	 * @public
	 * Getter and setter for title.
	 * 
	 * @param <string> title
	 * @return <string> title
	 */
	public function title($title = NULL)
	{
		if ($title === NULL)
		{
			return $this->_title;
		}
		else
		{
			$this->_title = HTML::chars($title, ENT_QUOTES);
		}
	}
	
	/**
	 * @public
	 * Detects whether video has a not empty description.
	 * 
	 * @return <boolean> has description
	 */
	public function has_description()
	{
		$description = trim(HTML::decode_entities($this->_description));
		return !empty($description)
			AND !preg_match('#^<p></p>$#', '', $description);
	}
	
	/**
	 * @public
	 * Getter and setter for description.
	 * 
	 * @param <string> description
	 * @return <string> description
	 */
	public function description($description = NULL)
	{
		if ($description === NULL)
		{
			return HTML::decode_entities($this->_description);
		}
		else
		{
			$this->_description = preg_replace("#[\n\r\t]#", "", HTML::chars($description, ENT_QUOTES));
		}
	}
	
	/**
	 * @public
	 * Getter and setter for state.
	 * 
	 * @param <integer> state
	 * @return <integer> state
	 */
	public function position($position = NULL)
	{
		if ($position === NULL)
		{
			return $this->_position;
		}
		else
		{
			$this->_position = (double)$position;
		}
	}
	
    /**
     * @public
     * Getter and setter for width.
     * 
     * @param <integer> width
     * @return <integer> width
     */
    public function width($width = NULL)
    {
        if ($width === NULL)
        {
            return (!empty($this->_width) ? $this->_width : Youtube::$cf['video_default_width']);
        }
        else
        {
            $this->_width = (int)$width;
        }
    }
    
    /**
     * @public
     * Getter and setter for height.
     * 
     * @param <integer> height
     * @return <integer> height
     */
    public function height($height = NULL)
    {
        if ($height === NULL)
        {
            return (!empty($this->_height) ? $this->_height : Youtube::$cf['video_default_height']);
        }
        else
        {
            $this->_height = (int)$height;
        }
    }
    
	/**
	 * @public
	 * Getter for category.
	 * 
	 * @return <object> category
	 */
	public function gallery()
	{
		return  $this->_gallery;
	}
	
	/**
	 * @public
	 * Delete the entry.
	 */
	public function delete()
	{
		$array = CSV::parse(Youtube::$csv . $this->_gallery->name() . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
		
		foreach ($array as $key => $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				unset($array[$key]);
			}
		}
		
		CSV::write($array, Youtube::$csv . $this->_gallery->name() . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
	}
			
	/**
	 * @public
	 * Edit entry.
	 * 
	 * The changes can be made using the setters.
	 */
	public function save()
	{
		$entry = array(
			$this->_id,
			$this->_yid,
			$this->_title,
			$this->_description,
			$this->_position,
            $this->_width,
            $this->_height,
		);
		
		$array = CSV::parse(Youtube::$csv . $this->_gallery->name() . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
		
		foreach ($array as $key => $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				unset($array[$key]);
			}
		}
		
		$array[] = $entry;
		CSV::write($array, Youtube::$csv . $this->_gallery->name() . '.csv', Youtube::$cf['csv_delimiter'], Youtube::$cf['csv_enclosure']);
	}
}
?>
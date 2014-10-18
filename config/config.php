<?php
/* utf8-marker = äöüß */
/**
 * @file config.php
 * @brief Configuration file.
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
	 * Filepath storing CSV containing videos.
	 * @warning NOTE: Without '/' at the end! 
	 * @var
	 */
	$plugin_cf['youtube']['csv_filepath'] = "content/plugins/youtube";
	/** 
	 * Delimiter for CSV. 
	 * @var
	 */
	$plugin_cf['youtube']['csv_delimiter'] = "#";
	/**
	 * Enclosure for CSV.
	 * @var
	 */
	$plugin_cf['youtube']['csv_enclosure'] = "\"";
	/**
	 * Date format used in backend.
	 * @var
	 */
	$plugin_cf['youtube']['date_format'] = "d.m.Y";
    /**
     * Default width of a video.
     * @var
     */
    $plugin_cf['youtube']['video_default_width'] = "420";
    /**
     * Default height of a video.
     * @var
     */
    $plugin_cf['youtube']['video_default_height'] = "315";

?>
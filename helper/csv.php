<?php
/* utf8-marker = äöüß */
/**
 * @file csv.php
 * @brief CSV helper.
 * 
 * @author David Stutz
 * @version 1.3.0
 * @license GPLv3
 * @package youtube
 * @see http://sourceforge.net/projects/cmsimpleyoutube/
 * 
 *  This file is part of the youtube gallery plugin for CMSimple.
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
 * @class CSV
 * CSV helper.
 * 
 * @author David Stutz
 * @since 1.0.0
 */
class CSV {
	
	/**
	 * @public
	 * @static
	 * Parse CSV file. Will returned parsed CSV as array.
	 * 
	 * @param <string> filepath
	 * @param <string> delimiter
	 * @param <string> enclosure
	 * @return <array> parsed
	 */
	public static function parse($file, $delimiter, $enclosure)
	{
		$list = fopen($file, "r");
		
		$array = array();
		if ($list !== FALSE)
		{
			while (!feof($list))
			{
				$tmp = fgetcsv($list, 9999, $delimiter, $enclosure);
				if (!empty($tmp))
				{
					$array[] = $tmp;
				}
			}
		}
		
		return $array;
	}
	
	/**
	 * @public
	 * @static
	 * Write CSV file. Will write an array as CSV to the file.
	 * 
	 * @param <array> content
	 * @param <string> filepath
	 * @param <string> delimiter
	 * @param <string> enclosure
	 */
	public static function write($array, $file, $delimiter, $enclosure)
	{
		if(file_exists($file))
		{
			copy($file, $file.'.tmp');
		}
		
		$list = fopen($file, "w+");
		
		if ($list !== FALSE)
		{
			foreach ($array as $content)
			{
				if (!empty($content))
				{
					fputcsv($list, $content, $delimiter, $enclosure);
				}
			}
			
			@unlink($file.'.tmp');
			fclose($list);
		}
		else
		{
			copy($file.'.tmp', $file);
			@unlink($file.'.tmp');
		}
	}
}
?>
<?php
/* utf8-marker = äöüß */
/**
 * @file admin.php
 * @brief Plugin's backend.
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

if (!class_exists('Youtube')) require dirname(__FILE__) . "/youtube.php";

if (function_exists('XH_registerStandardPluginMenuItems'))
{
    XH_registerStandardPluginMenuItems(true);
}

if ((function_exists('XH_wantsPluginAdministration') AND XH_wantsPluginAdministration('youtube')) OR isset($youtube))
{
	/* Make CMSimple global saccessable. */
	global $sn,$pth,$plugin,$plugin_tx;
	$plugin = basename(dirname(__FILE__),"/");

	$f = $plugin;

	/* initvar() to support POST AND GET. */
	initvar('admin');
	$help = isset($_GET['help']) ? TRUE : FALSE;
	initvar('action');
	
	$o .= print_plugin_admin('ON');
	
	/* Plugin info. */
	if ($admin == '') 
	{
		$o .= '<p class="youtube-head"><b>' . Youtube::name() . '</b></p>'
				. '<p class="youtube-notice">'
                                    . 'Version: ' . Youtube::VERSION . '<br />'
                                . '</p>'
                                . '<p class="youtube-help">'
                                    . 'Released: ' . Youtube::release_date() . '<br />'
                                    . 'Author: ' . Youtube::author() . '<br />'
                                    . 'Website: ' . Youtube::website() . '<br />'
                                    . 'GitHub Repository/Releases: ' . Youtube::github() . '<br />'
                                    . Youtube::description() . '<br />'
                                    . Youtube::legal() . '<br />'
				. '</p>';
	}
	
	/* Show youtube menu. */
	if ($admin == 'plugin_main')
	{
		/* Check for youtube galleries dir. */
		Youtube::check_dir();
		
		/* Get all galleries. */
		$galleries = Youtube_Gallery::galleries();
		
		/* Output. */
		$o .= '<p class="youtube-head"><b>' . Youtube::$tx['title_' . $action] . '</b><span style="float: right;"><a href="' . page_url() . '&help"><img src="' . Youtube::$images . '/help.png" /></a></span></p>'
			. '<p>';
		
		/* Check for category. */
		$gallery = FALSE;
		$galleries = Youtube_Gallery::galleries();
		if (isset($_GET['gallery'])
			AND Youtube_Gallery::gallery_exists($_GET['gallery']))
		{
			$gallery = new Youtube_Gallery($_GET['gallery']);
		}
		elseif (!empty($galleries))
		{
			$gallery = $galleries[0];
		}
		else
		{
			$action = 'new';
			$o .= '<div class="youtube-error">' . Youtube::$tx["No galleries found. Create a new one."] . '</div>';
		}
		
		/* Get video. */
		$video = FALSE;
		if (isset($_GET['video'])
			AND is_object($gallery)
			AND $gallery->video_exists($_GET['video']))
		{
			$video = new Youtube_Video($_GET['video'], $gallery);
		}
		
		/* Delete video. */
		if (isset($_POST['delete']))
		{
			/* Add video. */
			$video->delete();
			
			$o .= '<div class="youtube-success">' . Youtube::$tx["Successfully deleted video."] . '</div>';
		}
		
		/* Show all galleries. */
		if ($action == 'plugin_text')
		{
			if ($help)
			{
				$o .= '<table class="youtube-help" width="100%">'
					. '<tr>'
					. '<td width="5%"><img src="' . Youtube::$images . 'gallery/delete.png" /></td>'
					. '<td>' . Youtube::$tx["Delete the selected gallery."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td width="10%"><img src="' . Youtube::$images . 'gallery/add.png" /></td>'
					. '<td width="90%">' . Youtube::$tx["Add a new gallery."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . Youtube::$images . 'video/add.png" /></td>'
					. '<td>' . Youtube::$tx["Add a new video to the selected gallery."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . Youtube::$images . 'video/edit.png" /></td>'
					. '<td>' . Youtube::$tx["Edit the selected video."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . Youtube::$images . 'video/delete.png" /></td>'
					. '<td>' . Youtube::$tx["Delete the selected video."] . '</td>'
					. '</tr>'
					. '</table>';
			}
			
			/* Menu. */
			$o .= '<table class="edit youtube-table">'
				. '<tr>'
				. '<td><b>' . Youtube::$tx["Gallery"] . ': </b>'
				. ' <select onChange="location.href=this.options[this.selectedIndex].value">'
				. '<option value="' . $sn . '?&youtube&admin=plugin_main&action=plugin_text&gallery=' . $gallery->name() . '">' . $gallery->name() . '</option>';
			
			foreach ($galleries as $g)
			{
				if ($g->name() == $gallery->name())
				{
					continue;
				}
				
				$o .= '<option value="' . $sn . '?&youtube&admin=plugin_main&action=plugin_text&gallery=' . $g->name() . '">' . $g->name() . '</option>';
			}
			
			$o .= '</select></td>'
				. '<td width="5%"><a class="pl_tooltip" href="' . $sn.'?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=remove">'
					. '<img src="' . Youtube::$images . 'gallery/delete.png" alt="' . Youtube::$tx["Remove gallery"] . '" />'
					. '<span>' . Youtube::$tx["Delete the selected gallery."] . '</span>'
				. '</a></td>'
				. '<td width="5%"><a class="pl_tooltip" href="' . $sn.'?&youtube&admin=plugin_main&action=new">'
					. '<img src="' . Youtube::$images . 'gallery/add.png" alt="' . Youtube::$tx["New gallery"] . '" />'
					. '<span>' . Youtube::$tx["Add a new gallery."] . '</span>'
				. '</a></td>'	
				. '</tr>'
				. '</table>';
			
			/* List videos of gallery. */
			$o .= '<table class="edit youtube-table">'
				. '<thead>'
				. '<td width="5%"><a class="pl_tooltip" href="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=add">'
					. '<img src="'.Youtube::$images.'video/add.png" />'
					. '<span>' . Youtube::$tx["Add a new video to the selected gallery."] . '</span>'
				. '</a></td>'
				. '<td width="5%"></td>' 
				. '<td>' . Youtube::$tx["Added"] . '</td>'
				. '<td>' . Youtube::$tx["YID"] . '</td>'
				. '<td>' . Youtube::$tx["Title"] . '</td>'
				. '<td>' . Youtube::$tx["Position"] . '</td>'
				. '</thead>';
			
			/* Get videos. */
			$videos = $gallery->videos();
			usort($videos, 'youtube_sort_asc');
			
			foreach ($videos as $video)
			{
				$o .= '<tr>'
					. '<td><a class="pl_tooltip" href="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=edit&video=' . $video->id() . '">'
						. '<img src="' . Youtube::$images . 'video/edit.png" />'
						. '<span>' . Youtube::$tx["Edit the selected video:"] . ' ' . $video->title() . '</span>'
					. '</a></td>'
					. '<td><a class="pl_tooltip" href="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=delete&video=' . $video->id() . '">'
						. '<img src="' . Youtube::$images . 'video/delete.png" />'
						. '<span>' . Youtube::$tx["Delete the selected video:"] . ' ' . $video->title() . '</span>'
					. '</a></td>'
					. '<td>' . date(Youtube::$cf['date_format'], $video->id()) . '</td>'
					. '<td>' . $video->yid() . '</td>'
					. '<td>' . $video->title() . '</td>'
					. '<td>' . $video->position() . '</td>'
					. '</tr>';
			}

			$o .= '</table>';
		}
		
		/* Create new gallery */
		if ($action == 'new')
		{
			/* POST? */
			if (isset($_POST['new']))
			{
				/* Add video. */
				if (!empty($_POST['name']))
				{
					$gallery = new Youtube_Gallery($_POST['name']);
					$o .= '<div class="youtube-success">' . Youtube::$tx["Successfully created new gallery."] . '</div>';
				}
				else
				{
					$o .= '<div class="youtube-error">' . Youtube::$tx["Fill a name."] . '</div>';
				}
			}

			$o .= '<div class="youtube-help">' . Youtube::$tx["The galleryname should not contain any whitespace or special characters."] . '</div>';	

			$o .= '<form action="' . $sn . '?&youtube&admin=plugin_main&action=new" method="POST">'
				. '<table class="edit youtube-table">'
				. '<tr>'
				. '<td>' . Youtube::$tx["Name"] . '</td>'
				. '<td><input type="text" name="name" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><button type="submit" name="new" class="youtube-submit submit">' . Youtube::$tx["Save"] . '</button></td>'
				. '</tr>'
				. '</table>'
				. '</form>';
		}
		
		/* Remove gallery. */
		if ($action == 'remove'
			AND $gallery !== FALSE)
		{
			/* POST? */
			if (isset($_POST['submit']))
			{
				/* Add video. */
				$gallery->remove();
				unset($gallery);
				
				$o .= '<div class="youtube-success">' . Youtube::$tx["Successfully deleted gallery."] . '</div>';
			}
			else
			{
				$o .= '<form action="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=remove" method="POST">'
					. '<div class="youtube-notice">' . Youtube::$tx["Are you sure you want to delete the gallery with all its videos?"] . '</div><button name="submit" type="remove" class="youtube-submit submit">' . Youtube::$tx["I'm sure."] . '</button>'
					. '</form>';
			}
		}
		
		/* Add new video to gallery. */
		if ($action == 'add')
		{
			/* POST? */
			if (isset($_POST['add']))
			{
                $validation = Validation::factory($_POST);
                $validation->rule('yid', 'not_empty', Youtube::$tx["Fill a youtube id."])
                        ->rule('title', 'not_empty', Youtube::$tx["Fill a title."])
                        ->rule('width', 'not_empty', Youtube::$tx["Fill a width."])
                        ->rule('height', 'not_empty', Youtube::$tx["Fill a height."])
                        ->rule('width', 'integer', Youtube::$tx["Width has to be an integer."])
                        ->rule('height', 'integer', Youtube::$tx["Height has to be an integer."]);
                
				if ($validation->check())
				{
					/* Add video. */
					$video = new Youtube_video(time(), $gallery);
					$video->yid($_POST['yid']);
					$video->title($_POST['title']);
					$video->description(stsl($_POST['description']));
					$video->position($_POST['position']);
                    $video->width($_POST['width']);
                    $video->height($_POST['height']);
					$video->save();
					
					$o .= '<div class="youtube-success">' . Youtube::$tx["Successfully saved changes."] . '</div>';
				}
				else
				{
					foreach ($validation->errors() as $error) 
                    {
                        $o .= '<div class="youtube-error">' . $error . '</div>';
                    }
				}
			}
			
			$o .= '<form action="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=add" method="POST">'
				. '<table class="edit youtube-table">'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Youtube ID"] . '</td>'
                        . '<td><input type="text" name="yid" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Position"] . '</td>'
                        . '<td><input type="text" name="position" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Width &times; Height"] . '</td>'
                        . '<td><input type="text" name="width" value="' . Youtube::$cf['video_default_width'] . '" />  &times; <input type="text" name="height" value="' . Youtube::$cf['video_default_height'] . '" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Title"] . '</td>'
                        . '<td><input type="text" name="title" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2">' . Youtube::$tx["Description"] . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2"><textarea class="youtube-editor" name="description"></textarea></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2"><button type="submit" name="add" class="youtube-submit submit">' . Youtube::$tx["Save"] . '</button></td>'
                    . '</tr>'
				. '</table>'
				. '</form>';
			
			/* Init editor for description. */
			if (function_exists('init_editor'))
			{
				init_editor(array('youtube-editor'));
			}
		}
		
		/* Edit video to gallery. */
		if ($action == 'edit'
			AND $video !== FALSE)
		{
			/* POST? */
			if (isset($_POST['edit']))
			{
                $validation = Validation::factory($_POST);
                $validation->rule('yid', 'not_empty', Youtube::$tx["Fill a youtube id."])
                        ->rule('title', 'not_empty', Youtube::$tx["Fill a title."])
                        ->rule('width', 'not_empty', Youtube::$tx["Fill a width."])
                        ->rule('height', 'not_empty', Youtube::$tx["Fill a height."])
                        ->rule('width', 'integer', Youtube::$tx["Width has to be an integer."])
                        ->rule('height', 'integer', Youtube::$tx["Height has to be an integer."]);
                        
				if ($validation->check())
				{
					$video->yid($_POST['yid']);
					$video->title($_POST['title']);
					$video->description(stsl($_POST['description']));
					$video->position($_POST['position']);
                    $video->width($_POST['width']);
                    $video->height($_POST['height']);
					$video->save();
					
					$o .= '<div class="youtube-success">' . Youtube::$tx["Successfully saved changes."] . '</div>';
				}
				else
				{
                    foreach ($validation->errors() as $error) 
                    {
                        $o .= '<div class="youtube-error">' . $error . '</div>';
                    }
				}
			}
			
			$o .= '<form action="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=edit&video=' . $video->id() . '" method="POST">'
				. '<table class="edit youtube-table">'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Youtube ID"] . '</td>'
                        . '<td><input type="text" name="yid" value="' . $video->yid() . '" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Position"] . '</td>'
                        . '<td><input type="text" name="position" value="' . $video->position() . '" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Width &times; Height"] . '</td>'
                        . '<td><input type="text" name="width" value="' . $video->width() . '" />  &times; <input type="text" name="height" value="' . $video->height() . '" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td>' . Youtube::$tx["Title"] . '</td>'
                        . '<td><input type="text" name="title" value="' . $video->title() . '" /></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2">' . Youtube::$tx["Description"] . '</td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2"><textarea class="youtube-editor" name="description">' . $video->description() . '</textarea></td>'
                    . '</tr>'
                    . '<tr>'
                        . '<td colspan="2"><button type="submit" name="edit" class="youtube-submit submit">' . Youtube::$tx["Save"] . '</button></td>'
                    . '</tr>'
				. '</table>'
				.'</form>';
			
			/* Init editor for description. */
			if (function_exists('init_editor'))
			{
				init_editor(array('youtube-editor'));
			}
		}
		
		/* Delete video. */
		if ($action == 'delete'
			AND $video !== FALSE)
		{
			$o .= '<form action="' . $sn . '?&youtube&admin=plugin_main&gallery=' . $gallery->name() . '&action=plugin_text&video=' . $video->id() . '" method="POST">'
				. '<div class="youtube-notice">' . Youtube::$tx["Are you sure you want to delete the video?"] . '</div><button name="delete" type="submit" class="youtube-submit submit">' . Youtube::$tx["I'm sure."] . '</button>'
				. '</form>';
		}

		$o .= '</p>';
	}
	
	if ($admin != 'plugin_main')
	{
		$hint = array(
			'mode_donotshowvarnames' => FALSE,
		);

		$o .= plugin_admin_common($action, $admin, $plugin, $hint);
	}
	
}

?>
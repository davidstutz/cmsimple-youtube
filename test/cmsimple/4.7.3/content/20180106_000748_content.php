<?php // utf8-marker = äöü
if(!defined('CMSIMPLE_VERSION') || preg_match('/content.php/i', $_SERVER['SCRIPT_NAME']))
{
	die('No direct access');
}
?>
<h1>Overview</h1>
<p>This installation of CMSimple demonstrates a couple of CMSimple plugins using the standard template:</p>
<ul>
<li><a href="http://davidstutz.de/projects/cmsimple-plugins/" target="_blank">CMSimple News</a>, <a href="https://github.com/davidstutz/cmsimple-news" target="_blank">GitHub</a>, <a href="http://davidstutz.de/cmsimpledemo/plugins/news/help/help_en.htm" target="_blank">Documentation</a></li>
<li><a href="http://davidstutz.de/projects/cmsimple-plugins/" target="_blank">CMSimple Pictures</a>, <a href="https://github.com/davidstutz/cmsimple-pictures" target="_blank">GitHub</a>, <a href="" target="_blank">Documentation</a></li>
<li><a href="http://davidstutz.de/projects/cmsimple-plugins/" target="_blank">CMSimple Youtube</a>, <a href="" target="_blank">GitHub</a>, <a href="https://github.com/davidstutz/cmsimple-youtube" target="_blank">Documentation</a></li>
</ul>
<p>See the following subpages:</p>
<h1>News</h1>
<p class="cmsimplecore_warning">All plugin calls are wrapped in three opening and closing braces!</p>
<p>The basic plugin call <code>{plugin:news('news', 5);}</code> (with three opening and closing braces, as detailed in the <a href="" target="_blank">documentation</a>) generates the following list of (up to five) news entries:</p>
{{{plugin:news('news', 5);}}}
<h2>Newscase</h2>
<p>A newscase can be called using <code>{plugin:newscase('News', 'news', '-5 years');}</code>. It only shows news entries from the last five years:</p>
{{{plugin:newscase('News', 'news', '-5 years');}}}
<h2>Newsticker</h2>
<p>The newsticker can be called using <code>{plugin:newsticker('news', 5);}</code>:</p>
{{{plugin:newsticker('news', 5);}}}
<h2>Newsscroller</h2>
<p>The newsscroller can be called using <code>{plugin:newsscroller('news', 5);}</code>:</p>
{{{plugin:newsscroller('news', 5);}}}
<h2>Newsslider</h2>
<p>The newsslider can be called using <code>{plugin:newsslider('news', 5, TRUE);}</code>:</p>
{{{plugin:newsslider('news', 5, TRUE);}}}
<h1>Pictures</h1>
<style type="text/css">
/* Bxslider driver. */
/* Important: No marign and padding on bxslider ul and li. */
.pictures-frontend .pictures-bxslider-driver {
    margin: 0 40px;
    width: 660px;
}
 
/* Main window containing the ul (not the controls and pager). */
.pictures-frontend .pictures-bxslider-driver .bx-window {
     
}
 
/* Main li. */
.pictures-frontend .pictures-bxslider-driver .bx-window li {
     
}
 
/* Previous control. */
.pictures-frontend .pictures-bxslider-driver .bx-prev {
    float: left;
    position: absolute;
    left: -40px;
    top: 60px;
}
 
/* Next control. */
.pictures-frontend .pictures-bxslider-driver .bx-next {
    float: right;
    position: absolute;
    right: -40px;
    top: 60px;
}
 
/* Pager div. */
.pictures-frontend .pictures-bxslider-driver .bx-pager {
    margin: .5em auto;
    text-align: center;
}
 
/* Anchors of the pager. */
.pictures-frontend .pictures-bxslider-driver .bx-pager .pager-link {
    margin: .5em;
}
 
/* Active pager link. */
.pictures-frontend .pictures-bxslider-driver .bx-pager .pager-active {
     
}
</style>
<div>{{{PLUGIN:pictures('gallery', 'table');}}}</div>
<div>{{{PLUGIN:pictures('gallery', 'coinslider');}}}</div>
<div>{{{PLUGIN:pictures('gallery', 'bxslider');}}}</div>
<div>{{{PLUGIN:pictures('gallery', 'bxslider4');}}}</div>
<h1>Youtube</h1>

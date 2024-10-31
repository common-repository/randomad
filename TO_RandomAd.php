<?php
/*
Plugin Name: RandomAd
Description: 広告をランダムで表示するプラグインです。
Version: 1.0.0
Author: tokuhausu
Author URI: http://gendaikko.wp.xdomain.jp
License: GPL2
*/
?>
<?php
/*  Copyright 2017 tokuhausu (email : tokuhausu@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    tokuhausu, Japan
*/
?>
<?php
	include_once(plugin_dir_path( __FILE__ ).'ShortCoad.php');
	require_once dirname(__FILE__).DIRECTORY_SEPARATOR."init.php";
	require_once dirname(__FILE__).DIRECTORY_SEPARATOR."widget.php";
	//Make Instance
	global $afb;
	$afb = new randad_PluginInit();
	//Register Activation Hook.
	register_activation_hook(__FILE__, array($afb, "randad_create_tables"));
	add_action( 'admin_menu', "randad_init_plugin_page");
	
	add_action( 'widgets_init', function() {
	     register_widget( 'RandomAdsWidget' );
	});
?>
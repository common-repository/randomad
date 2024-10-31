<?php
class randad_PluginInit{
	function randad_hasTable($table){
		global $wpdb;
	
		$sql = "SHOW TABLES LIKE '$table';";
		$table_search = $wpdb->get_row($sql); //「$wpdb->posts」テーブルがあるかどうか探す
		if( $wpdb->num_rows == 1 ){ //結果を判別して条件分岐
			 //テーブルがある場合の処理
 			//echo '1';
 			return true;
		} else {
 			//テーブルがない場合の処理
 			//echo 'none';
 			return false;
		}
	}
	function randad_create_cat_table() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'randomad_cat';
		if($this->randad_hasTable($table_name)){
			return;
		}
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
    			cat_id mediumint(9) NOT NULL AUTO_INCREMENT,
    			cat_name text NOT NULL,
    			UNIQUE KEY (cat_id)
  			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		 $wpdb->insert( $table_name, array('cat_id'=>0,'cat_name'=>'NOTSET'),array('%d','%s'));
	}
	function randad_create_ad_table() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'randomad_ad';
		if($this->randad_hasTable($table_name)){
			return;
		}
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
    			ad_id mediumint(9) NOT NULL AUTO_INCREMENT,
    			ad_name text NOT NULL,
    			cat_id int DEFAULT 0 NOT NULL,
   			tag text NOT NULL,
    			UNIQUE KEY ad_id (ad_id)
  			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	function randad_create_tables(){
		$this->randad_create_cat_table();
		$this->randad_create_ad_table();
		
	}
}
	function randad_init_plugin_page(){
		require "page.php";
		add_options_page(
         		'RandomAds', // page_title（オプションページのHTMLのタイトル）
          		'RandomAdsプラグインの管理画面', // menu_title（メニューで表示されるタイトル）
          		'administrator', // capability
         		'randomads-config', // menu_slug（URLのスラッグこの例だとoptions-general.php?page=hello-world）
          		'randad_display_plugin_config_page' // function
     		);
     		add_menu_page(
         		'RandomAds', // page_title
         		'RandomAds', // menu_title
          		'manage_options', // capability
          		'randomads', // menu_slug
          		'randad_display_plugin_page', // function
          		'', // icon_url
          		81 // position
     		);
     		add_submenu_page(
          		'randomads', // parent_slug
          		'広告を追加', // page_title
          		'広告を追加', // menu_title
          		'administrator', // capability
          		'randomads-add-newad', // menu_slug
          		'randad_display_plugin_add_newad' // function
     		);
     		add_submenu_page(
          		'randomads', // parent_slug
          		'広告一覧', // page_title
          		'広告一覧', // menu_title
          		'administrator', // capability
          		'randomads-ads', // menu_slug
          		'randad_display_plugin_ads' // function
     		);
     		add_submenu_page(
          		'randomads', // parent_slug
          		'カテゴリー', // page_title
          		'カテゴリー', // menu_title
          		'administrator', // capability
          		'randomads-category', // menu_slug
          		'randad_display_plugin_category' // function
     		);
	}
	function randad_display_plugin_config_page() {
		randad_plugin_config_page();
	}	
	function randad_display_plugin_page() {
		randad_plugin_page();
	}
	function randad_display_plugin_add_newad() {
		randad_plugin_add_newad();
	}
	function randad_display_plugin_ads() {
		randad_plugin_ads();
	}
	function randad_display_plugin_category() {
		randad_plugin_category();
	}
?>
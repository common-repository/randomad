<?php
function randad_getCategory(){

	global $wpdb;
	$wpdb->show_errors();
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_cat';
	// SQL
    	$sql = "SELECT cat_id,cat_name FROM ${table_name};";
    	// クエリ実行
    	$rows = $wpdb->get_results($sql);
    	return $rows;
}
function randad_getCategoryName($cat_id){
	global $wpdb;
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_cat';
	// SQL
    	$sql = $wpdb->prepare("SELECT cat_name FROM ${table_name} WHERE cat_id=%d",$cat_id);
    	// クエリ実行
    	$rows = $wpdb->get_results($sql);
    	foreach($rows as $cat_name){
    		return $cat_name;
    	}
    	
}
function randad_getAds(){
	global $wpdb;
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	// SQL
    	$sql = "SELECT * FROM ${table_name};";
    	// クエリ実行
    	$rows = $wpdb->get_results($sql);
    	return $rows;
}
function randad_getAdsOfCategory($cat_id){
	global $wpdb;
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	// SQL
    	$sql = $wpdb->prepare("SELECT * FROM ${table_name} WHERE cat_id=%d",$cat_id);
    	// クエリ実行
    	$rows = $wpdb->get_results($sql);
    	return $rows;
}
function randad_getAd($ad_id){
	global $wpdb;
	$wpdb->show_errors();

	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	// SQL
    	$sql = $wpdb->prepare("SELECT * FROM ${table_name} WHERE ad_id=%d",$ad_id);
    	// クエリ実行
    	$rows = $wpdb->get_results($sql);
    	foreach($rows as $value){
    		return $value;
    	}
    	
}
function randad_addAds($name,$cat_id,$tag){
	global $wpdb;
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	
	//無害化
	$name = sanitize_text_field($name);
	$cat_id = randad_catid_sanitizer($cat_id);
	$tag = randad_tag_sanitizer($tag);
	
	$wpdb->insert( 
	$table_name, 
	array( 
		'ad_name' => $name, 
		'cat_id' => $cat_id,
		'tag' => $tag
	), 
	array( 
		'%s', 
		'%d' ,
		'%s'
	) 
	);
}
function randad_addCategory($cat_name){
	global $wpdb;
	
	//無害化
	$cat_name = sanitize_text_field($cat_name);
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_cat';
	
	$wpdb->insert( 
	$table_name, 
	array( 
		'cat_name' => $cat_name,
	), 
	array( 
		'%s',
	) 
	);
}
function randad_deleteCategory($cat_id){
	global $wpdb;
	
	//無害化
	$cat_id = randad_catid_sanitizer($cat_id);
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_cat';
	
	$wpdb->delete( $table_name, array( 'cat_id' => $cat_id ) );
}
function randad_deleteAd($ad_id){
	global $wpdb;
	
	//無害化
	$ad_id = randad_adid_sanitizer($ad_id);
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	
	$wpdb->delete( $table_name, array( 'ad_id' => $ad_id ) );
}
function randad_updataCategory($cat_id,$cat_name){
	global $wpdb;
	
	//無害化
	$cat_name = sanitize_text_field($cat_name);
	$cat_id = randad_catid_sanitizer($cat_id);
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_cat';
	
	$wpdb->update( $table_name, array("cat_name" => $cat_name), array("cat_id" => $cat_id), array("%s"), array("%s"));
}
function randad_updataAd($ad_id,$ad_name,$cat_id,$tag){
	global $wpdb;
	
	//無害化
	$ad_name = sanitize_text_field($ad_name);
	$ad_id = randad_adid_sanitizer($ad_id);
	$cat_id = randad_catid_sanitizer($cat_id);
	$tag = randad_tag_sanitizer($tag);
	
	//テーブル名
	$table_name = $wpdb->prefix . 'randomad_ad';
	
	$wpdb->update( $table_name, array("ad_name" => $ad_name,"cat_id" => $cat_id,"tag" => $tag), array("ad_id" => $ad_id), array("%s","%d","%s"), array("%s"));
}

function randad_createAdCord($ads){
	$header = "<table style='border:none;'>";
	$body = "";
				foreach($ads as $ad){
					$ad_tag=str_replace ('\"','"',$ad->tag);
					$body = $body.
					"
					<tr>
						<td>{$ad_tag}</td>
					</tr>
					";
				}
	$footer = "</table>";
	$HTML = $header.$body.$footer;
	return $HTML;
}

function randad_load_styles($file_name) {
		$urlpath = plugins_url('style/'.$file_name.'.css', __FILE__);
    		wp_register_style($file_name, $urlpath);
      	wp_enqueue_style($file_name);
}

?>
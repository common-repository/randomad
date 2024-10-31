<?php
	//これはすべての入力で使用すべき
	function randad_nullbyte_sanitizer($arr) {
	    if (is_array($arr) ){
	    return array_map('randad_nullbyte_sanitizer', $arr);    
	    }
	    return str_replace("\0", "", $arr);
	}
	function randad_widget_num_sanitizer($arr){
		$arr = randad_nullbyte_sanitizer($arr);
		return $arr;
	}
	function randad_shortcoad_num_sanitizer($num){
		$num = intval($num);
		$num = absint($num);
		
		return $num;
	}
	function randad_shortcoad_cat_sanitizer($cat){
		$cat = intval($cat);
		
		return $cat;
	}
	function randad_tag_sanitizer($tag){
		$tag = stripslashes($tag);
		$tag = randad_nullbyte_sanitizer($tag);
		
		return $tag;
	}
	function randad_catid_sanitizer($cat_id){
		$cat_id = intval($cat_id);
		$cat_id = absint($cat_id);
		
		return $cat_id;
	}
		function randad_adid_sanitizer($ad_id){
		$ad_id = intval($ad_id);
		$ad_id = absint($ad_id);
		
		return $ad_id;
	}
	
?>
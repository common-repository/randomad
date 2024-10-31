<?php
	require_once 'functions.php';
	require_once 'function_sanitize.php';
	function randad_ShortCoad($atts) {
		extract(shortcode_atts(array('num' => 1,'cat'=>-1), $atts));
		$ads;
		
		//引数無害化
		$num = randad_shortcoad_num_sanitizer($num);
		$cat = randad_shortcoad_cat_sanitizer($cat);
		
		if($cat==-1){//カテゴリの指定なし
			$ads = randad_getAds();
		}else{//カテゴリの指定あり
			$ads = randad_getAdsOfCategory($cat);
			if(count($ads)==0){
				return "カテゴリが不正です。";
			}
		}
		
		if(count($ads)<$num){
			$num=count($ads);
		}
		if($num==0){
			return "表示する広告の最大数が0または数値以外になっています。";
		}
   	 	$select=array_rand($ads,$num);	
   	 	$show_ads = array();
   	 	if(is_array($select)){
	   	 	foreach($select as $sel){
	   	 		array_push($show_ads,$ads[$sel]);
	   	 	}	
	   	 }else{
	   	 	array_push($show_ads,$ads[$select]);
	   	 }
		$html = randad_createAdCord($show_ads);
		return $html;
	}
	add_shortcode('RandomAd', 'randad_ShortCoad');
?>
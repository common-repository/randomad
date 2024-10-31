<?php
require_once 'function_sanitize.php';
class RandomAdsWidget extends WP_Widget {
    public function __construct() {
        // 初期化処理（ウィジェットの各種設定）を行います。
       // 情報用の設定値
	    $widget_options = array(
	        'classname'                     => 'widget_randomads',
	        'description'                   => '設定された広告を条件に従ってランダムに表示',
	        'customize_selective_refresh'   => true,
	    );
	 
	    // 操作用の設定値
	    $control_options = array( 'width' => 400, 'height' => 350 );
	 
	    // 親クラスのコンストラクタに値を設定
	    parent::__construct( 'widget-template', 'ランダム広告', $widget_options, $control_options );
    }
 
    public function widget( $args, $instance ) {
       // ウィジェットの内容をWebページに出力（HTML表示）します。
       // ウィジェットのオプション取得
    	    $title = empty( $instance['title'] ) ? '' : $instance['title'];
	    $cat = ! empty( $instance['cat'] ) ? $instance['cat'] : '';
	    $num = ! empty( $instance['num'] ) ? $instance['num'] : '';
	 
	    echo $args['before_widget']; // ウィジェット開始タグ（<div>など）
	    if ( ! empty( $title ) ) {
	        // タイトルの値をタイトル開始/終了タグで囲んで出力
	        echo $args['before_title'] . $title . $args['after_title'];
	    }
	    if(!strcmp($cat,"all")){
	    	echo do_shortcode("[RandomAd num='$num']");
	    }else{
	    	echo do_shortcode("[RandomAd num='$num' cat='$cat']");
	    }
	    
	    echo $args['after_widget']; 
    }
 
    public function form( $instance ) {
        	// 管理画面のウィジェット設定フォームを出力します。
        	// デフォルトのオプション値
        	$defaults = array(
	     		'title' => '',
	     		'cat'  => '',
	     		'num'  => '0'
		);
	 
		// デフォルトのオプション値と現在のオプション値を結合
		$instance   = wp_parse_args( (array) $instance, $defaults );
	 
		// タイトル値の無害化（サニタイズ）
		$title  = sanitize_text_field( $instance['title'] );
		$num  = sanitize_text_field( $instance['num'] );
		
		$field_id_title=$this->get_field_id('title');
		$field_name_title=$this->get_field_name( 'title' );
		$field_id_cat=$this->get_field_id( 'cat' );
		$field_name_cat=$this->get_field_name( 'cat' );
		$field_id_num=$this->get_field_id( 'num' );
		$field_name_num=$this->get_field_name( 'num' );
		echo "
			<p>
				<label for='$field_id_title'>タイトル:</label>
				<br>
				<input type='text' id='$field_id_title' name='$field_name_title' value='$title' class='widefat' />
			</p>
			<p>
				<label for='$field_id_cat'>カテゴリー:</label><br>
				<select name='$field_name_cat' id='$field_id_cat'>
					<option value='all'>すべて</option>";
					$cat = randad_getCategory();
					foreach($cat as $value){
							echo "<option value={$value->cat_id}";selected( $instance['cat'], $value->cat_id );echo ">{$value->cat_name}</option>";
					}					
				echo "	
				</select>
			</p>
				<label for='$field_id_num'>表示する最大数:</label>
				<br>
				<input type='number' min='0' id='$field_id_num' name='$field_name_num' value='{$num}' />
			<p>
			
			</p>
		";
	}
 
    public function update( $new_instance, $old_instance ) {
        // ウィジェットオプションを安全な値で保存するためにデータ検証/無害化します。
        
		 // 一時的に以前のオプションを別変数に退避
		 $instance = $old_instance;
		 
		 // タイトル値を無害化（サニタイズ）
		 $instance['title']  = sanitize_text_field( $new_instance['title'] );
		 $instance['cat']   = randad_catid_sanitizer($new_instance['cat']);
		 $instance['num'] = randad_widget_num_sanitizer($new_instance['num']);
		 
		 return $instance;
    }
}
<?php
	require_once 'functions.php';
	require_once 'function_sanitize.php';
	function randad_plugin_config_page() {
		randad_load_styles("style-cat");
     		echo "
     			<div class='wrap'>
     				<h1>RandomAdsの設定</h1>
     				<p style='font-size:30px;'>現在設定可能な項目はありません。</p>
     			</div>
     		";
	}	
	function randad_plugin_page() {
		randad_load_styles("style-cat");
		$version = "1.0.0";
		echo '<div class="wrap">';
		echo "<p>RandomAd Version $version</p>";
     		echo '</div>';
	}
	function randad_plugin_add_newad() {
		randad_load_styles("style-cat");
		echo '<div class="wrap">';
		echo '<h2 style="text-align:center;font-size:30px">新規広告の追加</h2>';
		if(!empty($_POST)){
			randad_nullbyte_sanitizer($_POST);
			$ad_name = sanitize_text_field($_POST['ad_name']);
			$category = randad_nullbyte_sanitizer($_POST['category']);
			$tag = randad_tag_sanitizer($_POST['tag']);
			randad_addAds($ad_name,$category,$tag);
			echo "<h3 style='background:#ffffff;padding:5px;'>広告を保存しました</h3>";
		}
     		echo '<form method="post" action="admin.php?page=randomads-add-newad"> ';
     		echo '
     		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label>名前<label></th>
				<td><input type="text" size="50" id="ad_name" name="ad_name" autocomplete="off" required></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>カテゴリー<label></th>
				<td>
					<select name="category">';
					$cat = randad_getCategory();
					foreach($cat as $value){
						echo "<option value={$value->cat_id}>{$value->cat_name}</option>";
					}					
				echo '	
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>HTMLタグ<label></th>
				<td>
					<textarea id="tag" name="tag" cols="200" rows="5" maxlength="2048" placeholder="ここに広告のHTMLタグを入力する" required></textarea>
				</td>
			</tr>
		</table>';
     		submit_button();
     		echo '</form>';
     		echo '</div>';
	}
	function randad_plugin_ads() {
		randad_load_styles("style-ads");
		if(!empty($_POST)){
			randad_nullbyte_sanitizer($_POST);
			if(array_key_exists("edit", $_POST)){
				$sanitized_ad_id = randad_adid_sanitizer($_POST[ad_id]);
				randad_edit_ad($sanitized_ad_id);
				return;
				echo "<h3 style='background:#ffffff;padding:5px;'>広告を編集します。</h3>";
			}else if(array_key_exists("delete", $_POST)){
				$sanitized_ad_id = randad_adid_sanitizer($_POST[ad_id]);
				randad_deleteAd($sanitized_ad_id);
				echo "<h3 style='background:#ffffff;padding:5px;'>広告を削除しました。</h3>";
			}else if(array_key_exists("updata", $_POST)){
				$sanitized_ad_name = sanitize_text_field($_POST[ad_name]);
				$sanitized_tag = randad_tag_sanitizer($_POST[tag]);
				$sanitized_ad_id = randad_adid_sanitizer($_POST[updata]);
				$sanitized_cat_id = randad_catid_sanitizer($_POST[category]);
				randad_updataAd($sanitized_ad_id,$sanitized_ad_name,$sanitized_cat_id,$sanitized_tag);
				echo "<h3 style='background:#ffffff;padding:5px;'>広告を編集しました。</h3>";
			}
		}
		
		echo "
			<div class='wrap'>
				<h2 style='text-align:center;font-size:30px'>広告一覧</h2>
				<p><a class='page-title-action' href='admin.php?page=randomads-add-newad'>新規追加</a></p>
				<div class='main'>
					<div class='ads-list'>
						<table class='ads_list_table' cellspacing='0' cellpadding='10' width='100%'>
							<thead>
								<tr>
									<td><input type='checkbox'></input></td>
									<td>名前</td>
									<td>ID</td>
									<td>HTMLタグ</td>
									<td>カテゴリー</td>
									<td>操作</td>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td><input type='checkbox'></input></td>
									<td>名前</td>
									<td width='150'>ID</td>
									<td>HTMLタグ</td>
									<td>カテゴリー</td>
									<td>操作</td>
								</tr>
							</tfoot>
							<tbody>";
								$ads = randad_getAds();
								
								$count = 1;
								foreach($ads as $ad){
									$count++;
									$color = $count%2;
									
									//30文字でカット
									$html_tag = mb_strimwidth($ad->tag, 0, 30);
									
									$cat_name=randad_getCategoryName($ad->cat_id);
									echo "
										<tr class='ad_list_col_$color'>
											<td></td>
											<td>$ad->ad_name</td>
											<td width='150'>$ad->ad_id</td>
											<td>"; echo htmlspecialchars($html_tag); echo "</td>
											<td>{$cat_name->cat_name}</td>
											<td>
												<form method='POST' action='admin.php?page=randomads-ads' name='action_edit_$count'><input type='hidden' name='edit' value='' /><input type='hidden' name='ad_name' value='{$ad->ad_name}' /><input type='hidden' name='ad_id' value='{$ad->ad_id}' /><a href='javascript:document.action_edit_$count.submit();' class='edit_button'>編集</a></form>
												<form method='POST' action='admin.php?page=randomads-ads' name='action_del_$count'><input type='hidden' name='delete' value='' /><input type='hidden' name='ad_id' value='{$ad->ad_id}' /><a href='javascript:document.action_del_$count.submit();' class='delete_button'>削除</a></form>
											</td>
										</tr>
									";
								}
							echo "
							</tbody>
						</table>
					</div>
				</div>
			</div>
		";
	}
	function randad_edit_ad($ad_id){
		$ad=randad_getAd($ad_id);
		echo "
			<div class='Edit_area'>
				<h3 style='text-align:left;font-size:20px;font-weight:100;'>広告を編集</h2>
				<form method='POST' action='admin.php?page=randomads-ads' name='updata_ad'>
					<input type='hidden' name='updata' value='$ad_id' autocomplete='off' required/>
					<table class='form-table'>
						<tr valign='top'>
							<th scope='row'><label>名前<label></th>
							<td><input type='text' size='50' id='ad_name' name='ad_name' value='{$ad->ad_name}' autocomplete='off' required></td>
						</tr>
						<tr valign='top'>
							<th scope='row'><label>カテゴリー<label></th>
							<td>
								<select name='category'>";
								$cat = randad_getCategory();
								foreach($cat as $value){
									if(!strcmp($value->cat_id,$ad->cat_id)){
										echo "<option value={$value->cat_id} selected>{$value->cat_name}</option>";
									}else{
										echo "<option value={$value->cat_id}>{$value->cat_name}</option>";
									}
								}					
							echo "	
								</select>
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'><label>HTMLタグ<label></th>
							<td>
								<textarea id='tag' name='tag' cols='200' rows='5' maxlength='2048' placeholder='ここに広告のHTMLタグを入力する' required>{$ad->tag}</textarea>
							</td>
						</tr>
					</table>";
					submit_button();
					echo "
				</form>
			</div>
		";
	}
	function randad_plugin_category() {
		randad_load_styles("style-cat");
		$edit_cat_name = "";
		$sanitized_cat_id = randad_catid_sanitizer($_POST[cat_id]);
		if(!empty($_POST)){
			randad_nullbyte_sanitizer($_POST);
			if(array_key_exists("add_cat_name", $_POST)){
				$sanitized_add_cat_name = sanitize_text_field($_POST['add_cat_name']);
				randad_addCategory($sanitized_add_cat_name);
				echo "<h3 style='background:#ffffff;padding:5px;'>カテゴリーを追加しました</h3>";
			}else if(array_key_exists("edit_cat_name", $_POST)){
				$sanitized_edit_cat_name = sanitize_text_field($_POST[edit_cat_name]);
				$sanitized_edit_cat_id = randad_catid_sanitizer($_POST[edit_cat_id]);
				randad_updataCategory($sanitized_edit_cat_id,$sanitized_edit_cat_name);
				echo "<h3 style='background:#ffffff;padding:5px;'>カテゴリーを編集しました</h3>";
			}else if(array_key_exists("delete", $_POST)){
				$sanitized_cat_id = randad_catid_sanitizer($_POST[cat_id]);
				randad_deleteCategory($sanitized_cat_id);
				echo "<h3 style='background:#ffffff;padding:5px;'>カテゴリーを削除しました</h3>";
			}else if(array_key_exists("edit", $_POST)){
				echo "<h3 style='background:#ffffff;padding:5px;'>カテゴリーを編集します。</h3>";
				$sanitized_cat_name = sanitize_text_field($_POST[cat_name]);
				$edit_cat_name=$sanitized_cat_name;
			}
		}
		$category = randad_getCategory();
		echo '
			<div class="wrap">
				<h2 style="text-align:center;font-size:30px">カテゴリー</h2>
				<div class="main">
					<div class="add_category">
						<form method="post" action="admin.php?page=randomads-category"> 
							<table class="add_category" cellpadding="10" style="">
								<tr><th><p style="text-align:left;font-size:15px">新規追加</p></th></tr>
								<tr valign="top">
									<th scope="row"><label>名前<label></th>';
									if(!strcmp($edit_cat_name,"")){
										echo "<td><input type='text' size=70 placeholder='カテゴリーの名前' name='add_cat_name' autocomplete='off' required></form></td>";
									}else{
										echo "<td><input type='text' size=70 placeholder='カテゴリーの名前' value='$edit_cat_name' name='edit_cat_name' autocomplete='off' required><input type='hidden' name='edit_cat_id' value='{$sanitized_cat_id}'></form></td>";
									}echo '
									
								</tr>
								<tr valign="top"  align="right">
									<th></th>
									<td>';
										if(!strcmp($edit_cat_name,"")){
											submit_button("カテゴリーを追加"); 
										}else{
											submit_button("カテゴリーを保存"); 
										}
										echo '
									</td>
								</tr>
							</table>
						</form>
					</div>
					<div class="category_list">
						<table class="category_list_table" cellspacing="0" cellpadding="10" width="100%">
							<thead>
								<tr>
									<td><input type="checkbox"></input></td>
									<td>名前</td>
									<td>ID</td>
									<td>操作</td>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td><input type="checkbox"></input></td>
									<td>名前</td>
									<td width="150">ID</td>
									<td>操作</td>
								</tr>
							</tfoot>
							<tbody>';
							$color_num = 0;
							$count = 0;
							foreach($category as $value){
								echo "
								<tr valign='top' class='category_col_{$color_num}'>
									<td></td>
									<td scope='row' class='category_list_name'>{$value->cat_name}</td>
									<td>{$value->cat_id}</td>
									<td>";
										if(strcmp($value->cat_name,"NOTSET")){
											echo "
												<form method='POST' action='admin.php?page=randomads-category' name='action_edit_$count'><input type='hidden' name='edit' value='' /><input type='hidden' name='cat_name' value='{$value->cat_name}' /><input type='hidden' name='cat_id' value='{$value->cat_id}' /><a href='javascript:document.action_edit_$count.submit();' class='edit_button'>編集</a></form>
												<form method='POST' action='admin.php?page=randomads-category' name='action_del_$count'><input type='hidden' name='delete' value='' /><input type='hidden' name='cat_id' value='{$value->cat_id}' /><a href='javascript:document.action_del_$count.submit();' class='delete_button'>削除</a></form>
											";
										}
									echo "
									</td>
								</tr>
							";
							if($color_num==0){
								$color_num=1;
							}else{
								$color_num=0;
							}
							$count++;
							}
					echo '	</tbody>
						</table>
					</div>	
			</div>
		';
	}
	?>
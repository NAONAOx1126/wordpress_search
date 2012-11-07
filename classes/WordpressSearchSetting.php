<?php
/*
 * Copyright (C) 2012 NetLife Inc. All Rights Reserved.
 * http://www.netlife-web.com/
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * 複数サイトから記事を検索するプラグインの設定用クラス
 *
 * @package WordpressSearchSetting
 * @author Naohisa Minagawa
 * @version 1.0
 */
class WordpressSearchSetting {
	/**
	 * 設定を初期化するメソッド
	 * admin_menuにフックさせる。
	 * @return void
	 */
	public static function init(){
		global $menu;
		add_menu_page(
			__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Database Setting", WordpressSearchPlugin::getProjectCode()), 
			__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Database Setting", WordpressSearchPlugin::getProjectCode()), 
			"administrator", 
			"wordpress_search_menu", 
			array( "WordpressSearchSetting", 'execute' ), 
			WordpressSearchPlugin::getBaseUrl()."menu_icon.png", 
			99 
		);
	}
	
	/**
	 * 設定画面の制御を行うメソッドです。
	 */
	public static function execute(){
		self::displaySetting();
	}

	/**
	 * 設定画面の表示を行う。
	 * @return void
	 */
	public static function displaySetting(){
		if(isset($_POST['submit'])){
			if(!empty($_POST["prefix"])){
				update_option("wordpress_search_prefix", $_POST["prefix"]);
			}else{
				update_option("wordpress_search_prefix", array());
			}
			$caution = __("Saved Changes", WordpressSearchPlugin::getProjectCode());
		}
		$prefix = get_option("wordpress_search_prefix");
		if(!is_array($prefix)){
			$prefix = array();
		}
		
		// 設定変更ページを登録する。
		echo "<div class=\"wrap\">";
		echo "<h2>".__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Database Setting", WordpressSearchPlugin::getProjectCode())."</h2>";
		echo "<h3>".__("Wordpress DB Prefix", WordpressSearchPlugin::getProjectCode())."</h3>";
		echo "<form method=\"post\" action=\"".$_SERVER["REQUEST_URI"]."\">";
		echo "<table class=\"form-table\"><tbody>";
		foreach($prefix as $index => $pre){
			if(!empty($pre)){
				echo "<tr><td>".($index + 1)."</td><td><input type=\"text\" name=\"prefix[]\" value=\"".$pre."\" size=\"54\" /></td></tr>";
			}
		}
		echo "<tr><td>".(count($prefix) + 1)."</td><td><input type=\"text\" name=\"prefix[]\" value=\"\" size=\"54\" /></td></tr>";
		echo "</tbody></table>";
		if(!empty($caution)){
			echo "<p class=\"caution\">".$caution."</p>";
		}
		echo "<p class=\"submit\"><input type=\"submit\" name=\"submit\" value=\"".__("Save Changes", WordpressSearchPlugin::getProjectCode())."\" /></p>";
		echo "</form></div>";
	}
}
?>
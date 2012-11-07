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
class WordpressSearchFormSetting {
	/**
	 * 設定を初期化するメソッド
	 * admin_menuにフックさせる。
	 * @return void
	 */
	public static function init(){
		global $menu;
		add_submenu_page(
			"wordpress_search_menu", 
			__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Form Setting", WordpressSearchPlugin::getProjectCode()), 
			__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Form Setting", WordpressSearchPlugin::getProjectCode()), 
			"administrator", 
			"wordpress_search_form_menu", 
			array( "WordpressSearchFormSetting", 'execute' ) 
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
			$saveForms = array();
			foreach($_POST["forms"] as $index => $form){
				if(!empty($form["form_code"])){
					$saveForms[$form["form_code"]] = $form;
				}
			}
			
			if(!empty($saveForms)){
				update_option("wordpress_search_forms", $saveForms);
			}else{
				update_option("wordpress_search_forms", array());
			}
			$caution = __("Saved Changes", WordpressSearchPlugin::getProjectCode());
		}
		$forms = get_option("wordpress_search_forms");
		if(!is_array($forms)){
			$forms = array();
		}
		
		// 設定変更ページを登録する。
		echo "<div class=\"wrap\">";
		echo "<h2>".__("Wordpress Search", WordpressSearchPlugin::getProjectCode())." ".__("Form Setting", WordpressSearchPlugin::getProjectCode())."</h2>";
		echo "<form method=\"post\" action=\"".$_SERVER["REQUEST_URI"]."\">";
		foreach($forms as $form_code => $form){
			echo "<h3>".__("Form", WordpressSearchPlugin::getProjectCode())."(".($form_code).")</h3>";
			echo "<table class=\"form-table\"><tbody>";
			// フォームコードの検索条件を設定
			echo "<tr><td>".__("Form Code", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][form_code]\" value=\"".$form["form_code"]."\" size=\"20\" /></td></tr>";
			// 選択しない場合のテキストを設定
			echo "<tr><td>".__("No Select", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][no_select]\" value=\"".$form["no_select"]."\" size=\"20\" /></td></tr>";
			// キーワードの検索条件を設定
			echo "<tr><td><input type=\"checkbox\" name=\"forms[".$form_code."][keyword]\" value=\"1\"".(($form["keyword"] == "1")?" checked":"")." /></td><td>".__("Use Keyword", WordpressSearchPlugin::getProjectCode())."</td>";
			echo "<td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][keyword_title]\" value=\"".$form["keyword_title"]."\" size=\"20\" /></td></tr>";
			// オプションキーワードを設定
			echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option1_title]\" value=\"".$form["option1_title"]."\" size=\"20\" /></td>";
			echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option1_values]\" value=\"".$form["option1_values"]."\" size=\"50\" /></td></tr>";
			echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option2_title]\" value=\"".$form["option2_title"]."\" size=\"20\" /></td>";
			echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option2_values]\" value=\"".$form["option2_values"]."\" size=\"50\" /></td></tr>";
			echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option3_title]\" value=\"".$form["option3_title"]."\" size=\"20\" /></td>";
			echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$form_code."][option3_values]\" value=\"".$form["option3_values"]."\" size=\"50\" /></td></tr>";
			echo "</tbody></table>";
		}
		$index = count($forms);
		echo "<h3>".__("Form", WordpressSearchPlugin::getProjectCode())."</h3>";
		echo "<table class=\"form-table\"><tbody>";
		// フォームコードの検索条件を設定
		echo "<tr><td>".__("Form Code", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][form_code]\" value=\"\" size=\"20\" /></td></tr>";
		// 選択しない場合のテキストを設定
		echo "<tr><td>".__("No Select", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][no_select]\" value=\"\" size=\"20\" /></td></tr>";
		// キーワードの検索条件を設定
		echo "<tr><td><input type=\"checkbox\" name=\"forms[".$index."][keyword]\" value=\"1\" /></td><td>".__("Use Keyword", WordpressSearchPlugin::getProjectCode())."</td>";
		echo "<td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][keyword_title]\" value=\"\" size=\"20\" /></td></tr>";
		// オプションキーワードを設定
		echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option1_title]\" value=\"\" size=\"20\" /></td>";
		echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option1_values]\" value=\"\" size=\"50\" /></td></tr>";
		echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option2_title]\" value=\"\" size=\"20\" /></td>";
		echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option2_values]\" value=\"\" size=\"50\" /></td></tr>";
		echo "<tr><td>".__("Title", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option3_title]\" value=\"\" size=\"20\" /></td>";
		echo "<td>".__("Selection", WordpressSearchPlugin::getProjectCode())."</td><td><input type=\"text\" name=\"forms[".$index."][option3_values]\" value=\"\" size=\"50\" /></td></tr>";
		echo "</tbody></table>";
		if(!empty($caution)){
			echo "<p class=\"caution\">".$caution."</p>";
		}
		echo "<p class=\"submit\"><input type=\"submit\" name=\"submit\" value=\"".__("Save Changes", WordpressSearchPlugin::getProjectCode())."\" /></p>";
		echo "</form></div>";
	}
}
?>
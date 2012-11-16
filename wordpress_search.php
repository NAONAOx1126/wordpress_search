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

/*
Plugin Name: Wordpress Search Plugin
Description: Search from multiple wordpress sites.
Version: 0.0.1
Author: Naohisa Minagawa
Author URI: http://www.netlife-web.com/
License: Apache License 2.0
Text Domain: wordpress_search
*/

// メモリ使用制限を調整
ini_set('memory_limit', '128M');

load_plugin_textdomain(WordpressSearchPlugin::getProjectCode(), false, WordpressSearchPlugin::getProjectCode().'/languages');		

class WordpressSearchPlugin{
	public static function getBaseDir(){
		return WP_PLUGIN_DIR."/".WordpressSearchPlugin::getProjectCode()."/";
	}

	public static function getBaseUrl(){
		return WP_PLUGIN_URL."/".WordpressSearchPlugin::getProjectCode()."/";
	}
	
	public static function getProjectCode(){
		return "wordpress_search";
	}
	
	public static function startup(){
		$mainClass = "WordpressSearch";
		
		require_once(dirname(__FILE__)."/classes/".$mainClass.".php");
		
		// 初期化処理用のアクションを登録する。
		add_action( 'init', array( $mainClass, "init" ) );
		
		// 初期化処理用のアクションを登録する。
		add_action( 'admin_init', array( $mainClass, "execute" ) );

		// ウィジェット登録用のアクションを登録する
		add_action( 'widgets_init', array( $mainClass."Widget", 'register' ) );
		add_action( 'widgets_init', array( $mainClass."ResultWidget", 'register' ) );
		
		// インストール時の処理を登録
		register_activation_hook( __FILE__, array( $mainClass, "install" ) );
		
		// アンインストール時の処理を登録
		register_deactivation_hook( __FILE__, array( $mainClass, "uninstall" ) );
		
    	// キーワードが配列で渡ってきた場合は、空白区切りでまとめる。
    	if(is_array($_POST["keyword"])){
    		$_POST["keyword"] = implode(" ", $_POST["keyword"]);
    	}
    	if(is_array($_POST["option_keyword"])){
    		$_POST["option_keyword"] = implode(" ", $_POST["option_keyword"]);
    	}
    	
		// 記事の登録日が分割されてきた場合は一つにまとめる。
		if(!empty($_POST["start_y"]) && !empty($_POST["start_m"]) && !empty($_POST["start_d"])){
			$publishDate = strtotime($_POST["start_y"]."-".$_POST["start_m"]."-".$_POST["start_d"]);
			$_POST["start"] = date("Y-m-d", $publishDate);
		}
		if(!empty($_POST["start"])){
			$publishDate = strtotime($_POST["start"]);
			$_POST["start_y"] = date("Y", $publishDate);
			$_POST["start_m"] = date("m", $publishDate);
			$_POST["start_d"] = date("d", $publishDate);
		}
		if(!empty($_POST["end_y"]) && !empty($_POST["end_m"]) && !empty($_POST["end_d"])){
			$publishDate = strtotime($_POST["end_y"]."-".$_POST["end_m"]."-".$_POST["end_d"]);
			$_POST["end"] = date("Y-m-d", $publishDate);
		}
		if(!empty($_POST["end"])){
			$publishDate = strtotime($_POST["end"]);
			$_POST["end_y"] = date("Y", $publishDate);
			$_POST["end_m"] = date("m", $publishDate);
			$_POST["end_d"] = date("d", $publishDate);
		}
	}
}

load_plugin_textdomain(WordpressSearchPlugin::getProjectCode(), false, WordpressSearchPlugin::getProjectCode().'/languages');		

// プラグイン処理を開始
WordpressSearchPlugin::startup();
?>
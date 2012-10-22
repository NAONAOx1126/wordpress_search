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
Plugin Name: Wordpress Converter for HTML Plugin
Description: This plugin is convert helper for HTML to Wordpress Template.
Version: 0.0.1
Author: Naohisa Minagawa
Author URI: http://www.netlife-web.com/
License: Apache License 2.0
*/

// メモリ使用制限を調整
ini_set('memory_limit', '128M');

// このプラグインのルートディレクトリ
define("WORDPRESS_SEARCH_BASE_DIR", WP_PLUGIN_DIR."/wordpress_search");

// このプラグインのルートURL
define("WORDPRESS_SEARCH_BASE_URL", WP_PLUGIN_URL."/wordpress_search");

// メインクラス名
define("WORDPRESS_SEARCH_PLUGIN_NAME", __("Wordpress Search Plugin"));

// メインクラス名
define("WORDPRESS_SEARCH_MAIN_CLASS", "WordpressSearch");

require_once(dirname(__FILE__)."/classes/".WORDPRESS_CONVERT_MAIN_CLASS.".php");

// 初期化処理用のアクションを登録する。
add_action( 'init', array( WORDPRESS_CONVERT_MAIN_CLASS, "init" ) );

// 初期化処理用のアクションを登録する。
add_action( 'admin_init', array( WORDPRESS_CONVERT_MAIN_CLASS, "execute" ) );

// インストール時の処理を登録
register_activation_hook( __FILE__, array( WORDPRESS_CONVERT_MAIN_CLASS, "install" ) );

// アンインストール時の処理を登録
register_deactivation_hook( __FILE__, array( WORDPRESS_CONVERT_MAIN_CLASS, "uninstall" ) );
?>
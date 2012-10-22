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

require_once(dirname(__FILE__)."/WordpressSearchSetting.php");
require_once(dirname(__FILE__)."/WordpressSearchSettingGeneral.php");

/**
 * 複数DB間でWordpressの記事の検索を行うプラグインのメインクラス
 *
 * @package WordpressSearch
 * @author Naohisa Minagawa
 * @version 1.0
 */
class WordpressSearch {
	/**
	 * Initial
	 * @return void
	 */
	public static function init(){
		// 環境のバージョンチェック
		if( version_compare( PHP_VERSION, '5.3.0', '<' ) )
			trigger_error( __("PHP 5.3 or later is required for this plugin."), E_USER_ERROR );
		
		// 初期化処理
		add_action( 'admin_menu', array( "WordpressSearchSetting", 'init' ) );
	}
	
	/**
	 * 変換処理を必要に応じて実行する。
	 */
	public static function execute(){
	}

	function install(){
		// インストール時の処理
	}

	function uninstall(){
		// アンインストール時の処理
	}


}
?>
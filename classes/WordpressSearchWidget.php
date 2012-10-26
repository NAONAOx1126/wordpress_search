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
 * 複数サイトから記事を検索するプラグインのウィジェット用クラス
 *
 * @package WordpressSearchWidget
 * @author Naohisa Minagawa
 * @version 1.0
 */
class WordpressSearchWidget extends WP_Widget {
	/**
	 * ウィジェット登録処理
	 */
	static function register(){
		return register_widget("WordpressSearchWidget");
	}
	
	/**
	 * コンストラクタ
	 */
	function __construct() {
    	parent::__construct(
			"wp_search_all", 
    		__("Wordpress Search Plugin", WordpressSearchPlugin::getProjectCode())
    	);
    }
    
    /**
     * フロントの表示処理
     */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo "<li id=\"wordpress_search\">";
        if ( $title )
        	echo "<h2 class=\"widgettitle\">".$title."</h2>";
        echo '<p><form action=""<input type="text" name="keyword" value="" /><input type="submit" value="'.__("Search").'" /></p>';
        echo "</li>";
    }
    
    /**
     * 管理側の登録処理
     */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['body'] = trim($new_instance['body']);
        return $instance;
    }
    
    /**
     * 管理側の表示処理
     */
    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>">
          <?php __('Search Title', WordpressSearchPlugin::getProjectCode()); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php
    }
}
?>

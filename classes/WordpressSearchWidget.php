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
			"wp_search_form", 
    		__("Wordpress Search Form", WordpressSearchPlugin::getProjectCode())
    	);
    }
    
    /**
     * フロントの表示処理
     */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $type = apply_filters( 'widget_type', $instance['type'] );
        if($type != ""){
	        $forms = get_option("wordpress_search_forms");
        	$form = $forms[$type];
	        echo "<li id=\"wordpress_search\">";
	        if ( $title )
	        	echo "<h2 class=\"widgettitle\">".$title."</h2>";
	        echo '<form action="'.$_SERVER["REQUEST_URI"].'" method="POST">';
			echo '<dl class="search-form">';
	        if($form["keyword"] == "1"){
	        	if(!empty($form["keyword_title"])){
	        		echo '<dt class="search-keyword">'.$form["keyword_title"].'</dt>';
	        	}
				echo '<dd class="search-keyword"><input type="text" name="keyword" value="'.$_POST["keyword"].'" /></dd>';
	        }
        	$option_keywords_temp = explode(" ", str_replace("　", " ", $_POST["option_keyword"]));
        	$option_keywords = array();
        	foreach($option_keywords_temp as $k){
        		if(!empty($k)){
	        		$option_keywords[] = $k;
        		}
        	}
        	$selectionValues = array();
	        for($i = 1; $i <= 3; $i ++){
		        if(!empty($form["option".$i."_values"])){
		        	if(!empty($form["option".$i."_title"])){
		        		echo '<dt class="search-option'.$i.'">'.$form["option".$i."_title"].'</dt>';
		        	}
					echo '<dd class="search-option'.$i.'">';
					echo '<select name="option_keyword[]">';
					if(!empty($form["no_select"])){
						echo '<option value="">'.$form["no_select"].'</option>';
					}
					$values = explode(",", $form["option".$i."_values"]);
					$selectionValues = array_merge($selectionValues, $values);
					foreach($values as $value){
						echo '<option value="'.$value.'"'.(in_array($value, $option_keywords)?" selected":"").'>'.$value.'</option>';
					}
					echo '</select></dd>';
		        }
	        }
	        $newOptionKeywords = array();
	        foreach($option_keywords as $option_keyword){
	        	if(!in_array($option_keyword, $selectionValues)){
	        		$newOptionKeywords[] = $option_keyword;
	        	}
	        }
			echo '<dt class="search-date">'.__("Publish Date").'</dt>';
			echo '<dd class="search-date">';
			echo '<input type="text" id="search_date_start_year" name="start_y" value="'.$_POST["start_y"].'" />年';
			echo '<input type="text" id="search_date_start_month" name="start_m" value="'.$_POST["start_m"].'" />月';
			echo '<input type="text" id="search_date_start_day" name="start_d" value="'.$_POST["start_d"].'" />日';
			echo '〜<input type="text" id="search_date_end_year" name="end_y" value="'.$_POST["end_y"].'" />年';
			echo '<input type="text" id="search_date_end_month" name="end_m" value="'.$_POST["end_m"].'" />月';
			echo '<input type="text" id="search_date_end_day" name="end_d" value="'.$_POST["end_d"].'" />日';
			echo '</dd>';
			echo '<input type="hidden" name="option_keyword[]" value="'.implode(" ", $newOptionKeywords).'" />';
			echo '</dd><div class="buttons"><input type="submit" name="search" value="'.__("Search").'" /></dd></dl></form>';
	        echo "</li>";
        }
    }
    
    /**
     * 管理側の登録処理
     */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['type'] = trim($new_instance['type']);
        return $instance;
    }
    
    /**
     * 管理側の表示処理
     */
    function form($instance) {
        $title = esc_attr($instance['title']);
        $forms = get_option("wordpress_search_forms");
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>">
          <?php __('Search Title', WordpressSearchPlugin::getProjectCode()); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
          <label for="<?php echo $this->get_field_id('type'); ?>">
          <?php __('Search Type', WordpressSearchPlugin::getProjectCode()); ?>
          </label>
         </p><p>
          <select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
          <?php foreach($forms as $index => $form): ?>
          <option value="<?php echo $index ?>"><?php echo $index ?></option> 
          <?php endforeach; ?>
          </select>
        </p>
        <?php
    }
}
?>

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
class WordpressSearchResultWidget extends WP_Widget {
	/**
	 * ウィジェット登録処理
	 */
	static function register(){
		return register_widget("WordpressSearchResultWidget");
	}
	
	/**
	 * コンストラクタ
	 */
	function __construct() {
    	parent::__construct(
			"wp_search_result", 
    		__("Wordpress Search Result", WordpressSearchPlugin::getProjectCode())
    	);
    }
    
    /**
     * フロントの表示処理
     */
    function widget($args, $instance) {
        global $wpdb;
        extract( $args );
        
        foreach($_POST as $key => $value){
        	$_GET[$key] = $value;
        }
        $_POST = $_GET;
        
    	// キーワードを分割し、配列に設定
    	$keywords_temp = explode(" ", str_replace("　", " ", $_POST["keyword"]));
    	$option_keywords_temp = explode(" ", str_replace("　", " ", $_POST["option_keyword"]));
    	$keywords = array("keyword" => array(), "option_keyword" => array());
    	foreach($keywords_temp as $k){
    		if(!empty($k)){
        		$keywords["keyword"][] = $k;
    		}
    	}
    	foreach($option_keywords_temp as $k){
    		if(!empty($k)){
        		$keywords["option_keyword"][] = $k;
    		}
    	}
    	
        // タイトルの表示
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo "<li id=\"wordpress_search\">";
        if ( $title )
        	echo "<h2 class=\"widgettitle\">".$title;
        	if(!empty($keywords["option_keyword"])){
        		echo "(".implode(" ", $keywords["option_keyword"]).")";
        	}
        	echo "</h2>";
        	
        echo '<p>';
        
        // 検索処理の実行
        if(isset($_POST["search"])){
        	
        	// 取得先DBのリストを取得
        	$prefix = get_option("wordpress_search_prefix");
        	
        	// 検索を個別に実行
        	$result = array();
        	foreach($prefix as $p){
        		// サイトのURLを取得する
        		$options = $wpdb->get_results("SELECT option_value FROM ".$p."options WHERE option_name = 'siteurl'");
        		$sql = "SELECT * FROM ".$p."posts WHERE post_status = 'publish'";
        		
        		// キーワードを検索条件に設定する。
        		foreach($keywords as $keyword_type => $keyword_list){
        			$keyword_target = $_POST[$keyword_type."_target"];
	        		if(is_array($keyword_list) && !empty($keywords)){
	        			foreach($keyword_list as $keyword){
		        			$termIds = array();
		        			$taxonomyIds = array();
		        			$postIds = array();
	        				if(empty($keyword_target) || $keyword_target == "tag"){
			        			// 記事のタグを取得する。
			        			$terms = $wpdb->get_results($wpdb->prepare("SELECT term_id FROM ".$p."terms WHERE name = %s", $keyword));
			        			foreach($terms as $term){
			        				$termIds[] = $term->term_id;
			        			}
			        			if(!empty($termIds)){
				        			$taxonomys = $wpdb->get_results("SELECT term_taxonomy_id FROM ".$p."term_taxonomy WHERE taxonomy = 'post_tag' AND term_id IN (".implode(", ", $termIds).")");
				        			foreach($taxonomys as $taxonomy){
				        				$taxonomyIds[] = $taxonomy->term_taxonomy_id;
				        			}
				        			if(!empty($taxonomyIds)){
					        			$posts = $wpdb->get_results("SELECT object_id FROM ".$p."term_relationships WHERE term_taxonomy_id IN (".implode(", ", $taxonomyIds).")");
					        			foreach($posts as $post){
					        				$postIds[] = $post->object_id;
					        			}
				        			}
			        			}
	        				}
		        			
		        			$sql .= " AND (";
	        				if($keyword_target != "body" && $keyword_target != "tag"){
			        			$sql .= $wpdb->prepare("post_title LIKE %s", "%".$keyword."%");
		        			}
	        				if(empty($keyword_target) || $keyword_target == "body"){
		        				if($keyword_target != "body" && $keyword_target != "tag"){
	        						$sql .= " OR ";
	        					}
			        			$sql .= $wpdb->prepare("post_content LIKE %s", "%".$keyword."%");
		        			}
		        			if(!empty($postIds)){
	        					if($keyword_target != "tag"){
	        						$sql .= " OR ";
	        					}
			        			$sql .= $wpdb->prepare("ID IN (".implode(", ", $postIds).")");
		        			}
		        			$sql .= ")";
	        			}
	        		}
        		}
        		
        		// 記事の登録日を検索条件とする。
    			if(!empty($_POST["start"])){
    				$sql .= " AND post_date >= '".date("Y-m-d H:i:s", strtotime(date("Y-m-d 00:00:00", strtotime($_POST["start"]))))."'";
    			}
    			if(!empty($_POST["end"])){
    				$sql .= " AND post_date <= '".date("Y-m-d H:i:s", strtotime(date("Y-m-d 23:59:59", strtotime($_POST["end"]))))."'";
    			}
    			
    			// 検索クエリを実行し、結果を取得
        		$articles = $wpdb->get_results($sql);
        		foreach($articles as $article){
        			$result[] = array("time" => $article->post_date, "title" => $article->post_title, "url" => $options[0]->option_value."?p=".$article->ID);
        		}
        	}
        	
        	// 検索結果を日付昇順に並べ替え
        	usort($result, function($a, $b){
        		return (strtotime($a["time"]) < strtotime($b["time"]));
        	});
        	
        	// 結果リストの出力
        	echo "<dl class=\"search-result\">";
        	foreach($result as $item){
        		echo "<dt>".date("Y年m月d日", strtotime($item["time"]))."</dt><dd><a href=\"".$item["url"]."\">".$item["title"]."</a></dd>";
        	}
        	echo "</dl>";
        }
        
        echo "</p></li>";
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

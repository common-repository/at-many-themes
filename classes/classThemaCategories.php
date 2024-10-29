<?php
require_once('classThemaEssence.php');
require_once('views/classViewCategories.php');

class ThemaCategories extends ThemaEssence{
	private $thema_categories;
	private $new_categories = array();
	private $new_cat_themes = array();
	private $for_all = array();
	private $thema_category_view;
	
	function __construct($category_data = array()){
		$this->new_categories = (isset($category_data['new_categories']) && $category_data['new_categories']) ? $category_data['new_categories'] : array();
		$this->new_cat_themes = (isset($category_data['new_cat_themes']) && $category_data['new_cat_themes']) ? $category_data['new_cat_themes'] : array();
		$this->for_all = (isset($category_data['for_all']) && $category_data['for_all']) ? $category_data['for_all'] : array();
		$this->thema_category_view = new ThemaCategoriesView();
	}
	
	public function setup_me(){
		if(is_admin()){
			if($this->new_categories && $this->new_cat_themes){
				$this->update_thema();
			}
			$this->thema_category_view->add_admin_scripts();
			$this->thema_category_view->thema_themes = $this->get_thema_themes();
			$this->thema_category_view->thema_categories = $this->get_thema_categories();
		}
	}
	
	public function show_me(){
		$this->thema_category_view->show_admin_scripts($this->ret_rule_categories());	
	}	
		
	public function ret_thema(){
		$path = $_SERVER['REQUEST_URI'];
		$categ = get_category_by_path($path, false);
		$categoryid = $categ ? $categ->cat_ID : "";
		
		$post_categories = array();
		$postid = url_to_postid( $path );
		if($postid){
			$post_categories = wp_get_post_categories($postid);
		}	

		if($categoryid || $post_categories){
			global $wpdb;
			$sql = "SELECT * FROM " . $wpdb->prefix . "thema_category_settings WHERE category_id = %d OR (for_all = 1 AND category_id IN ('".implode("','", $post_categories)."') );";
			$res_thema = $wpdb->get_row($wpdb->prepare($sql, $categoryid));
			if($res_thema){
				return array(
					$res_thema->category_theme_template,
					$res_thema->category_theme_stylesheet
				);
			}
		}
		return array();
	}
	
	private function ret_rule_categories(){
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . "thema_category_settings";
		$res_themas = $wpdb->get_results($sql);
		return $res_themas;
	}
	
	private function get_thema_categories(){
		if($this->thema_categories){
			return $this->thema_categories;
		}
		$args = array(
			'type'			=> 'post',
			'child_of'		=> 0,
			'parent'        => '',
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 0,
			'hierarchical'	=> 1,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> 'category',
			'pad_counts'	=> false 

		); 
		$this->thema_categories = get_categories( $args );
		return $this->thema_categories;
	}
	
	private function update_thema(){
		global $wpdb;
		
		$query = "TRUNCATE " . $wpdb->prefix . "thema_category_settings;";
		$wpdb->query( $query );
		
		$insert_values = array();
		$insert_types = array();
		for($i = 0; $i < sizeof($this->new_categories); $i++){
			if($this->new_categories[$i] && $this->new_cat_themes[$i]){
				$this->new_cat_themes[$i] = explode("---", $this->new_cat_themes[$i]);
				$for_all_posts = isset($this->for_all[$i]) ? $this->for_all[$i] : 0;
				array_push($insert_values, $this->new_categories[$i], $for_all_posts, $this->new_cat_themes[$i][0], $this->new_cat_themes[$i][1]);
				$insert_types[] = "('%d', '%d', '%s', '%s')";
			}
		}
		if($insert_values){
			$query = "INSERT INTO " . $wpdb->prefix . "thema_category_settings (category_id, for_all, category_theme_template, category_theme_stylesheet) VALUES ";
			$query .= implode(', ', $insert_types);
			$wpdb->query( $wpdb->prepare("$query ", $insert_values));
		}
		
	}
}

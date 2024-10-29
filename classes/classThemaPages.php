<?php
require_once('classThemaEssence.php');
require_once('views/classViewPages.php');

class ThemaPages extends ThemaEssence{
	private $thema_pages;
	private $new_pages = array();
	private $new_page_themes = array();
	private $thema_page_view;
	
	function __construct($page_data = array()){
		$this->new_pages = (isset($page_data['new_pages']) && $page_data['new_pages']) ? $page_data['new_pages'] : array();
		$this->new_page_themes = (isset($page_data['new_page_themes']) && $page_data['new_page_themes']) ? $page_data['new_page_themes'] : array();
		$this->thema_page_view = new ThemaPagesView();
	}
	
	public function setup_me(){
		if(is_admin()){
			if($this->new_pages && $this->new_page_themes){
				$this->update_thema();
			}
			$this->thema_page_view->add_admin_scripts();
			$this->thema_page_view->thema_themes = $this->get_thema_themes();
			$this->thema_page_view->thema_pages = $this->get_thema_pages();
		}
	}
	
	public function show_me(){
		$this->thema_page_view->show_admin_scripts($this->ret_rule_pages());	
	}	
		
	public function ret_thema(){
		$path = $_SERVER['REQUEST_URI'];
		$pageid = url_to_postid( $path );

		if($pageid){
			global $wpdb;
			$sql = "SELECT * FROM " . $wpdb->prefix . "thema_page_settings WHERE page_id = %d;";
			$res_thema = $wpdb->get_row($wpdb->prepare($sql, $pageid));
			if($res_thema){
				return array(
					$res_thema->page_theme_template,
					$res_thema->page_theme_stylesheet
				);
			}
		}
		return array();
	}
	
	private function ret_rule_pages(){
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . "thema_page_settings";
		$res_themas = $wpdb->get_results($sql);
		return $res_themas;
	}
	
	private function get_thema_pages(){
		if($this->thema_pages){
			return $this->thema_pages;
		}
		$args = array(
			'sort_order' => 'desc',
			'sort_column' => 'post_date',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => 0,
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		); 
		$this->thema_pages = get_pages($args); 
		return $this->thema_pages;
	}
	
	private function update_thema(){
		global $wpdb;
		
		$query = "TRUNCATE " . $wpdb->prefix . "thema_page_settings;";
		$wpdb->query( $query );
		
		$insert_values = array();
		$insert_types = array();
		for($i = 0; $i < sizeof($this->new_pages); $i++){
			if($this->new_pages[$i] && $this->new_page_themes[$i]){
				$this->new_page_themes[$i] = explode("---", $this->new_page_themes[$i]);
				array_push($insert_values, $this->new_pages[$i], $this->new_page_themes[$i][0], $this->new_page_themes[$i][1]);
				$insert_types[] = "('%d', '%s', '%s')";
			}
		}
		if($insert_values){
			$query = "INSERT INTO " . $wpdb->prefix . "thema_page_settings (page_id, page_theme_template, page_theme_stylesheet) VALUES ";
			$query .= implode(', ', $insert_types);
			$wpdb->query( $wpdb->prepare("$query ", $insert_values));
		}
		
	}
}

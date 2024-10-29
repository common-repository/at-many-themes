<?php
require_once('classThemaEssence.php');
require_once('views/classViewPosts.php');

class ThemaPosts extends ThemaEssence{
	private $thema_posts;
	private $new_posts = array();
	private $new_themes = array();
	private $thema_post_view;
	
	function __construct($post_data = array()){
		$this->new_posts = (isset($post_data['new_posts']) && $post_data['new_posts']) ? $post_data['new_posts'] : array();
		$this->new_themes = (isset($post_data['new_themes']) && $post_data['new_themes']) ? $post_data['new_themes'] : array();
		$this->thema_post_view = new ThemaPostsView();
	}
	
	public function setup_me(){
		if(is_admin()){
			if($this->new_posts && $this->new_themes){
				$this->update_thema();
			}
			$this->thema_post_view->add_admin_scripts();
			$this->thema_post_view->thema_themes = $this->get_thema_themes();
			$this->thema_post_view->thema_posts = $this->get_thema_posts();
		}
	}
	
	public function show_me(){
		$this->thema_post_view->show_admin_scripts($this->ret_rule_posts());	
	}	
		
	public function ret_thema(){
		$path = $_SERVER['REQUEST_URI'];
		$postid = url_to_postid( $path );

		if($postid){
			global $wpdb;
			$sql = "SELECT * FROM " . $wpdb->prefix . "thema_post_settings WHERE post_id = %d;";
			$res_thema = $wpdb->get_row($wpdb->prepare($sql, $postid));
			if($res_thema){
				return array(
					$res_thema->post_theme_template,
					$res_thema->post_theme_stylesheet
				);
			}
		}
		return array();
	}
	
	private function ret_rule_posts(){
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . "thema_post_settings";
		$res_themas = $wpdb->get_results($sql);
		return $res_themas;
	}
	
	private function get_thema_posts(){
		if($this->thema_posts){
			return $this->thema_posts;
		}
		$args = array(
			'posts_per_page'   => 500,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$this->thema_posts = get_posts( $args );
		return $this->thema_posts;
	}
	
	private function update_thema(){
		global $wpdb;
		
		$query = "TRUNCATE " . $wpdb->prefix . "thema_post_settings;";
		$wpdb->query( $query );
		
		$insert_values = array();
		$insert_types = array();
		for($i = 0; $i < sizeof($this->new_posts); $i++){
			if($this->new_posts[$i] && $this->new_themes[$i]){
				$this->new_themes[$i] = explode("---", $this->new_themes[$i]);
				array_push($insert_values, $this->new_posts[$i], $this->new_themes[$i][0], $this->new_themes[$i][1]);
				$insert_types[] = "('%d', '%s', '%s')";
			}
		}
		if($insert_values){
			$query = "INSERT INTO " . $wpdb->prefix . "thema_post_settings (post_id, post_theme_template, post_theme_stylesheet) VALUES ";
			$query .= implode(', ', $insert_types);
			$wpdb->query( $wpdb->prepare("$query ", $insert_values));
		}
		
	}
}


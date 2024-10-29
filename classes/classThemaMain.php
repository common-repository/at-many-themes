<?php
require_once('classThemaEssence.php');
require_once('views/classViewMain.php');

class ThemaMain extends ThemaEssence{
	private $new_def_thema;
	
	function __construct($post_data = array()){
		$this->new_def_thema = !empty($post_data['new_def_thema']) ? $post_data['new_def_thema'] : "";
		$this->thema_main_view = new ThemaMainView();
		$this->set_default_theme();
		add_action('customize_save', array(&$this, 'set_after_switch_theme'));
	}

	public function set_after_switch_theme()
	{
		if ( is_admin() && !empty($_REQUEST['theme'])) {
			$this->new_def_thema = $_REQUEST['theme'] . '---' . $_REQUEST['theme'];
			$this->update_thema();
		}
	}
	
	public function setup_me(){
		if(is_admin()){
			if($this->new_def_thema){
				$this->update_thema();
			}
			$this->thema_main_view->thema_themes = $this->get_thema_themes();
		}
	}
	
	public function show_me(){
		$this->thema_main_view->show_admin_scripts(implode("---", $this->ret_thema()));	
	}
	
	public function ret_thema(){
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . "thema_main";
		$res_thema = $wpdb->get_row($sql);
		if($res_thema){
			return array(
				$res_thema->main_theme_template,
				$res_thema->main_theme_stylesheet
			);
		}
		return array();
	}

	private function set_default_theme(){
		if (
			is_admin()
			&& isset( $_REQUEST['action'] )
			&& ($_REQUEST['action'] == "activate")
			&& isset ( $_REQUEST['stylesheet'] )
		) {
			$theme = wp_get_theme($_REQUEST['stylesheet']);
			$this->new_def_thema = $theme->template . "---" . $theme->stylesheet;
		}
	}

	private function update_thema(){
		global $wpdb;
		
		$query = "TRUNCATE " . $wpdb->prefix . "thema_main;";
		$wpdb->query( $query );
		
		$insert_values = explode("---", $this->new_def_thema);
		$query = "
			INSERT INTO " . $wpdb->prefix . "thema_main 
				( main_theme_template, main_theme_stylesheet ) 
			VALUES 
				( '%s', '%s' )
			";
		$wpdb->query( $wpdb->prepare("$query ", $insert_values));
	}
	
}

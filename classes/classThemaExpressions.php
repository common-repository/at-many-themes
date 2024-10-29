<?php
require_once('classThemaEssence.php');
require_once('views/classViewExpressions.php');

class ThemaExpressions extends ThemaEssence{
	private $new_expressions = array();
	private $new_expression_themes = array();
	private $thema_expression_view;
	private $expressions = array();
	
	function __construct($expression_data = array()){
		$this->new_expressions = (isset($expression_data['new_expressions']) && $expression_data['new_expressions']) ? $expression_data['new_expressions'] : array();
		$this->new_expression_themes = (isset($expression_data['new_expression_themes']) && $expression_data['new_expression_themes']) ? $expression_data['new_expression_themes'] : array();
		$this->thema_expression_view = new ThemaExpressionsView();
	}
	
	public function setup_me(){
		if(is_admin()){
			if($this->new_expressions && $this->new_expression_themes){
				$this->update_thema();
			}
			$this->thema_expression_view->add_admin_scripts();
			$this->thema_expression_view->thema_themes = $this->get_thema_themes();
		}
	}
	
	public function show_me(){
		$this->thema_expression_view->show_admin_scripts($this->ret_rule_expressions());	
	}	
		
	public function ret_thema(){
		$path = $_SERVER['REQUEST_URI'];
		$expressions = $this->ret_rule_expressions();
		if($expressions){
			foreach($expressions as $expression){
				if(preg_match("/" . $expression->thema_expression . "/", $path)){
					return array(
						$expression->expression_theme_template,
						$expression->expression_theme_stylesheet
					);
				}
			}
		}
		return array();
	}
	
	private function ret_rule_expressions(){
		global $wpdb;
		if($this->expressions){
			return $this->expressions;
		}
		$sql = "SELECT * FROM " . $wpdb->prefix . "thema_expressions_settings";
		$this->expressions = $wpdb->get_results($sql);
		return $this->expressions;
	}
	
	private function update_thema(){
		global $wpdb;
		
		$query = "TRUNCATE " . $wpdb->prefix . "thema_expressions_settings;";
		$wpdb->query( $query );
		
		$insert_values = array();
		$insert_types = array();
		for($i = 0; $i < sizeof($this->new_expressions); $i++){
			if($this->new_expressions[$i] && $this->new_expression_themes[$i]){
				$this->new_expression_themes[$i] = explode("---", $this->new_expression_themes[$i]);
				array_push($insert_values, $this->new_expressions[$i], $this->new_expression_themes[$i][0], $this->new_expression_themes[$i][1]);
				$insert_types[] = "('%s', '%s', '%s')";
			}
		}
		if($insert_values){
			$query = "INSERT INTO " . $wpdb->prefix . "thema_expressions_settings (thema_expression, expression_theme_template, expression_theme_stylesheet) VALUES ";
			$query .= implode(', ', $insert_types);
			$wpdb->query( $wpdb->prepare("$query ", $insert_values));
		}
		
	}
	
}

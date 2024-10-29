<?php
abstract class ThemaEssence{
	protected $thema_themes;
	
	public abstract function setup_me();
	public abstract function show_me();
	public abstract function ret_thema();

	/*
 	* Return all available themes in wordpress site.
 	*/
	protected function get_thema_themes(){
		if($this->thema_themes){
			return $this->thema_themes;
		}
		$this->thema_themes = wp_get_themes();
		return $this->thema_themes;
	}
}
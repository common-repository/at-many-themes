<?php
class ThemaPagesView{
	const THEMA_F		= 'at-many-themes';
	const ADMIN_JS		= '/js/thema_page_script.js';
	
	public $thema_themes = array();
	public $thema_pages = array();
	
	
	public function add_admin_scripts(){
		add_action( 'admin_enqueue_scripts', array(&$this, 'thema_script_js'), 1 );
	}
	
	public function thema_script_js(){
		wp_enqueue_script(
			'thema_page_script',
			plugins_url( self::THEMA_F . self::ADMIN_JS ),
			array('jquery'),
			false
		);
		wp_localize_script( 'thema_page_script', 'path_to', plugins_url(self::THEMA_F));
	}
	
	public function show_admin_scripts($rule_pages = array()){?>
		<div class='wrap'>
			<p>Select theme for using on pages.</p>
			<div id="thema_pages">
				<div id="new_page">
					<?php $this->show_copy_space(); ?>
				</div>
				<?php foreach($rule_pages as $page_r):?>
					<?php $this->show_copy_space($page_r->page_id, $page_r->page_theme_template . "---" . $page_r->page_theme_stylesheet, 'minus'); ?>
				<?php endforeach; ?>
			</div>			
		</div>
	<?php
	}
	
	public function show_copy_space($page_val = 0, $theme_val = '', $img_type = "plus"){ ?>
		<div class="copy_space">
			<?php $this->show_thema_pages($page_val);?>
			&nbsp;
			<?php $this->show_thema_themes($theme_val);?>
			&nbsp;
			<img src="<?=plugins_url(self::THEMA_F . '/images/'. $img_type .'.png'); ?>" class="<?=$img_type; ?>_btn" />
		</div>
<?php
	}
	
	private function show_thema_pages($page_val){
		echo "<select name='new_pages[]' class='page_selector'>";
		if(!$page_val){
			echo "<option selected value='0' >Choose page</option>";
		}
		foreach($this->thema_pages as $page){
			$selected = ($page_val == $page->ID) ? "selected" : "";
			echo "<option {$selected} value='{$page->ID}'>",$page->post_title,"</option>";
		}
		echo "</select>";
	}
	
	private function show_thema_themes($theme_val){
		echo "<select name='new_page_themes[]' class='theme_selector'>";
		if(!$theme_val){
			echo "<option selected value='0' >Choose theme</option>";
		}
		foreach($this->thema_themes as $theme){
			$theme_pref = $theme->template . "---" . $theme->stylesheet;
			$selected = ($theme_val == $theme_pref) ? "selected" : "";
			echo "<option {$selected} value='{$theme_pref}'>", $theme->get( 'Name' ) ,"</option>";
		}
		echo "</select>";
	}
}

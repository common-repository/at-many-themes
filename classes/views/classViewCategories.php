<?php
class ThemaCategoriesView{
	const THEMA_F		= 'at-many-themes';
	const ADMIN_JS		= '/js/thema_category_script.js';
	
	public $thema_themes = array();
	public $thema_categories = array();
	
	private $ch_box_num = 0;
	
	
	public function add_admin_scripts(){
		add_action( 'admin_enqueue_scripts', array(&$this, 'thema_script_js'), 1 );
	}
	
	public function thema_script_js(){
		wp_enqueue_script(
			'thema_category_script',
			plugins_url( self::THEMA_F . self::ADMIN_JS ),
			array('jquery'),
			false
		);
		wp_localize_script( 'thema_category_script', 'path_to', plugins_url(self::THEMA_F));
	}
	
	public function show_admin_scripts($rule_categories = array()){?>
		<div class='wrap'>
			<p>Select theme for using on categories.</p>
			<div id="thema_categories">
				<div id="new_category">
					<?php $this->show_copy_space(); ?>
				</div>
				<?php foreach($rule_categories as $category_r):?>
					<?php 
						$this->show_copy_space(
							$category_r->category_id, 
							$category_r->category_theme_template . "---" . $category_r->category_theme_stylesheet, 
							'minus', 
							$category_r->for_all
						); 
						?>
				<?php endforeach; ?>
			</div>			
		</div>
	<?php
	}
	
	public function show_copy_space($category_val = 0, $theme_val = '', $img_type = "plus", $chb_val = 0){ ?>
		<div class="copy_space">
			<?php $this->show_thema_categories($category_val);?>
			&nbsp;
			<?php $this->show_thema_themes($theme_val);?>
			&nbsp;
			<?php $this->show_checkbox_posts($chb_val);?>
			&nbsp;
			<img src="<?=plugins_url(self::THEMA_F . '/images/'. $img_type .'.png'); ?>" class="<?=$img_type; ?>_btn" />
		</div>
<?php
	}
	
	private function show_thema_categories($category_val){
		echo "<select name='new_categories[]' class='category_selector'>";
		if(!$category_val){
			echo "<option selected value='0' >Choose category</option>";
		}
		foreach($this->thema_categories as $category){
			$selected = ($category_val == $category->cat_ID) ? "selected" : "";
			echo "<option {$selected} value='{$category->cat_ID}'>",$category->cat_name,"</option>";
		}
		echo "</select>";
	}
	
	private function show_thema_themes($theme_val){
		echo "<select name='new_cat_themes[]' class='theme_selector'>";
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
	
	private function show_checkbox_posts($chb_val){
		$checked = ($chb_val) ? "checked" : "";
		echo "<label>Extend for posts of this category? ";
		echo "<input class='category_checkbox' type='checkbox' $checked name='for_all[$this->ch_box_num]' value='1' />";
		echo "</label>";
		$this->ch_box_num++;
	}
}
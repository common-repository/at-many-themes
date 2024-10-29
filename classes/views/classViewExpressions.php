<?php
class ThemaExpressionsView{
	const THEMA_F		= 'at-many-themes';
	const ADMIN_JS		= '/js/thema_expression_script.js';
	
	public $thema_themes = array();
	public $thema_expressions = array();
	
	
	public function add_admin_scripts(){
		add_action( 'admin_enqueue_scripts', array(&$this, 'thema_script_js'), 1 );
	}
	
	public function thema_script_js(){
		wp_enqueue_script(
			'thema_expression_script',
			plugins_url( self::THEMA_F . self::ADMIN_JS ),
			array('jquery'),
			false
		);
		wp_localize_script( 'thema_expression_script', 'path_to', plugins_url(self::THEMA_F));
	}
	
	public function show_admin_scripts($rule_expressions = array()){?>
		<div class='wrap'>
			<p>
				<b>Attention! For pro only!</b>
				<br />Add rule for the expressions. 
				<br />Delimeter is '<b>/</b>'. 
				<br />Ex. <b>\/hello-.+</b>
			</p>
			<div id="thema_expressions">
				<div id="new_expression">
					<?php $this->show_copy_space(); ?>
				</div>
				<?php foreach($rule_expressions as $expression_r):?>
					<?php $this->show_copy_space($expression_r->thema_expression, $expression_r->expression_theme_template . "---" . $expression_r->expression_theme_stylesheet, 'minus'); ?>
				<?php endforeach; ?>
			</div>			
		</div>
	<?php
	}
	
	public function show_copy_space($expression_val = '', $theme_val = '', $img_type = "plus"){ ?>
		<div class="copy_space">
			<input value="<?php echo $expression_val?>" name="new_expressions[]" type="text" class="expression_selector" />
			&nbsp;
			<?php $this->show_thema_themes($theme_val);?>
			&nbsp;
			<img src="<?=plugins_url(self::THEMA_F . '/images/'. $img_type .'.png'); ?>" class="<?=$img_type; ?>_btn" />
		</div>
<?php
	}
		
	private function show_thema_themes($theme_val){
		echo "<select name='new_expression_themes[]' class='theme_selector'>";
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

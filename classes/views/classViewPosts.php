<?php
class ThemaPostsView{
	const THEMA_F		= 'at-many-themes';
	const ADMIN_JS		= '/js/thema_post_script.js';
	
	public $thema_themes = array();
	public $thema_posts = array();
	
	
	public function add_admin_scripts(){
		add_action( 'admin_enqueue_scripts', array(&$this, 'thema_script_js'), 1 );
	}
	
	public function thema_script_js(){
		wp_enqueue_script(
			'thema_post_script',
			plugins_url( self::THEMA_F . self::ADMIN_JS ),
			array('jquery'),
			false
		);
		wp_localize_script( 'thema_post_script', 'path_to', plugins_url(self::THEMA_F));
	}
	
	public function show_admin_scripts($rule_posts = array()){?>
		<div class='wrap'>
			<p>Select theme for using on posts.</p>
			<div id="thema_posts">
				<div id="new_post">
					<?php $this->show_copy_space(); ?>
				</div>
				<?php foreach($rule_posts as $post_r):?>
					<?php $this->show_copy_space($post_r->post_id, $post_r->post_theme_template . "---" . $post_r->post_theme_stylesheet, 'minus'); ?>
				<?php endforeach; ?>
			</div>			
		</div>
	<?php
	}
	
	public function show_copy_space($post_val = 0, $theme_val = '', $img_type = "plus"){ ?>
		<div class="copy_space">
			<?php $this->show_thema_posts($post_val);?>
			&nbsp;
			<?php $this->show_thema_themes($theme_val);?>
			&nbsp;
			<img src="<?=plugins_url(self::THEMA_F . '/images/'. $img_type .'.png'); ?>" class="<?=$img_type; ?>_btn" />
		</div>
<?php
	}
	
	private function show_thema_posts($post_val){
		echo "<select name='new_posts[]' class='post_selector'>";
		if(!$post_val){
			echo "<option selected value='0' >Choose post</option>";
		}
		foreach($this->thema_posts as $post){
			$selected = ($post_val == $post->ID) ? "selected" : "";
			echo "<option {$selected} value='{$post->ID}'>",$post->post_title,"</option>";
		}
		echo "</select>";
	}
	
	private function show_thema_themes($theme_val){
		echo "<select name='new_themes[]' class='theme_selector'>";
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

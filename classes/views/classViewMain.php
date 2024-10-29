<?php

class ThemaMainView{
	public $thema_themes;
	
	public function show_admin_scripts($theme_val = ""){ ?>
		<div class='wrap'>
			<p>Select theme for using default.</p>
			<select name='new_def_thema'>
				<?php foreach($this->thema_themes as $theme): ?>
					<?php 
						$theme_pref = $theme->template . "---" . $theme->stylesheet;
						$selected = ($theme_val == $theme_pref) ? "selected" : "";
					?>
					<option <?php echo $selected; ?> value='<?php echo $theme_pref;?>'>
						<?php echo $theme->get( 'Name' ); ?>
					</option>
				<?php endforeach ?>
			</select>
		</div>
	<?php	
	}
}
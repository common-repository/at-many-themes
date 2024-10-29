<?php

class ThemaMobileView{
	public $thema_themes;

	public function show_admin_scripts($theme_val = ""){ ?>
		<div class='wrap'>
			<p>Select theme for using on mobile devaices.</p>
			<select name='new_mobile_thema'>
				<option value="">Select theme for mobile devices</option>
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
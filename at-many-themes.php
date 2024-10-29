<?php
/**
 * Plugin Name: A.T. Many themes
 * Description: This plugin help you to change theme for specific pages, posts, categories. It can be useful for creating landing page, A/B testing and others pages which need to use another design.
 * Version: 1.1.1
 * Author: Alexey Tomorovich
 * License: GPLv2 or later
 */

/*  
Copyright 2015  Alexey Tomorovich  (email: attanlogan@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('installs/installerThema.php');
register_activation_hook( __FILE__, array('InstallerThema', 'install_thema')) ;
register_deactivation_hook( __FILE__, array('InstallerThema', 'uninstall_thema') );

require_once('classes/classThemaMain.php');
require_once('classes/classThemaExpressions.php');
require_once('classes/classThemaCategories.php');
require_once('classes/classThemaPosts.php');
require_once('classes/classThemaPages.php');
require_once('classes/classThemaMobile.php');


class ATManyThemes
{
    const PAGE_TITLE = 'A.T. many themes. Overview';
    const MENU_TITLE = 'A.T. many themes';
    const THEMA_F = 'at-many-themes';
    const ADMIN_CSS = '/css/themastyle.css';

    private $essences = array();

    function __construct()
    {
        /*
         * Adding thema's classes settings in object for further working.
         * show_* Position and comparing hierarchy
         * */
        $this->essences['show_1'] = new ThemaMobile($_POST);
        $this->essences['show_2'] = new ThemaPages($_POST);
        $this->essences['show_3'] = new ThemaPosts($_POST);
        $this->essences['show_4'] = new ThemaCategories($_POST);
        $this->essences['show_5'] = new ThemaExpressions($_POST);
        $this->essences['show_6'] = new ThemaMain($_POST);

        if (is_admin()) {
            add_action('admin_menu', array(&$this, 'cd_switch_theme_add_pages'));
            add_action('admin_enqueue_scripts', array(&$this, 'thema_scripts'), 1);
            add_filter("plugin_action_links_" . plugin_basename(__FILE__), array(&$this, "thema_plugin_actions"));
            /*
             * Save post data in each thema's essences settings
             * */
            $this->setup_themes();
        }
        add_action('setup_theme', array(&$this, 'cd_switch_theme'), 1);
    }

    public function thema_plugin_actions($actions)
    {
        array_unshift($actions,
            "<a href=\"" . menu_page_url('at_theme_settings', false) . "\">" . __("Settings") . "</a>");
        return $actions;
    }

    /*
     * Comparing each thema's essences validation for showing rightly theme.
     */
    public function cd_switch_theme()
    {
        $show_essences = $this->essences;
        ksort($show_essences);
        foreach ($show_essences as $essence) {
            // return array of theme template, stylesheet
            if ($ret_thema = $essence->ret_thema()) {
                list($template, $stylesheet) = $ret_thema;
                break;
            }
        }
        $new_theme = wp_get_theme($template);
        if ($new_theme->exists()) {
            add_filter( 'template', function() use ($template) { return $template; } );
            add_filter( 'stylesheet', function() use ($stylesheet) { return $stylesheet; } );
        } else {
            $def_theme = wp_get_theme();
            add_filter( 'template', function() use ($def_theme) { return $def_theme->template; } );
            add_filter( 'stylesheet', function() use ($def_theme) { return $def_theme->stylesheet; } );
        }
    }

    public function cd_switch_theme_add_pages()
    {
        add_options_page(
            self::PAGE_TITLE,
            self::MENU_TITLE,
            'read',
            'at_theme_settings',
            array(&$this, 'at_theme_settings')
        );
    }

    /*
     * Viewing all theme's essences settings
     */
    public function at_theme_settings()
    { ?>
        <h2>A.T. many themes. Plugin Settings</h2>
        <?php
        $show_essences = $this->essences;
        ksort($show_essences);
        ?>
        <form method="post">
            <?php foreach ($show_essences as $essence) { ?>
                <div class='thema_type'>
					<?php
						// Showing essence settings
						$essence->show_me();
					?>
				</div>
                <?php
            }
            ?>
            <input type="hidden" name="update_thema" value="1" />
            <?php submit_button(); ?>
        </form>
        <?php
    }

    public function thema_scripts()
    {
        wp_enqueue_style(
            'themastyle',
            plugins_url(self::THEMA_F . self::ADMIN_CSS)
        );
    }

    /*
     *  Save post data in each thema's essences settings
     **/
    private function setup_themes()
    {
        foreach ($this->essences as $essence) {
            $essence->setup_me();
        }
    }

}

new ATManyThemes();
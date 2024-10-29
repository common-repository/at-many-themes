<?php
require_once('classThemaEssence.php');
require_once('views/classViewMobile.php');

/*
 * Class for set up default theme for mobiles.
 * */
class ThemaMobile extends ThemaEssence
{
    // const for theme template option
    const MOBILE_TEMPLATE = "mobile_theme_template";
    // const for theme stylesheet option
    const MOBILE_STYLESHEET = "mobile_theme_stylesheet";

    private $new_mobile_thema;
    private $thema_mobile_view;

    function __construct($post_data = array())
    {
        $this->new_mobile_thema = !empty($post_data['new_mobile_thema']) ? $post_data['new_mobile_thema'] : "";
        // Creating view for settings
        $this->thema_mobile_view = new ThemaMobileView();
    }

    /**
     *  Set up theme mobile settings
     */
    public function setup_me()
    {
        if (is_admin()) {
            if (!empty($_POST['update_thema'])) {
                if ($this->new_mobile_thema) {
                    $this->update_thema();
                } else {
                    $this->delete_thema();
                }
            }
            $this->thema_mobile_view->thema_themes = $this->get_thema_themes();
        }
    }

    /**
     *  Shows mobile settings
     */
    public function show_me()
    {
        $this->thema_mobile_view->show_admin_scripts(implode("---", $this->ret_thema_array()));
    }

    /**
     * Return mobile theme definition(template, stylesheet)
     * @return array
     */
    public function ret_thema_array()
    {
        if (($template = get_option(self::MOBILE_TEMPLATE)) && ($stylesheet = get_option(self::MOBILE_STYLESHEET))) {
            return array(
                $template,
                $stylesheet
            );
        }
        return array();
    }

    /**
     * Return mobile theme definition(template, stylesheet) if mobile.
     * @return array
     */
    public function ret_thema()
    {
        if (!$this->is_mobile()) {
            return array();
        }
        return $this->ret_thema_array();
    }


    /**
     * Define if mobile
     * @return bool
     */
    private function is_mobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
            $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4));
    }


    /**
     * Updating wordpress options, which storing theme definition(template, stylesheet)
     * @return bool
     */
    private function update_thema()
    {
        if (!$this->new_mobile_thema) {
            return false;
        }
        $insert_values = explode("---", $this->new_mobile_thema);
        if ($this->ret_thema_array()) {
            update_option(self::MOBILE_TEMPLATE, $insert_values[0]);
            update_option(self::MOBILE_STYLESHEET, $insert_values[1]);
        } else {
            add_option(self::MOBILE_TEMPLATE, $insert_values[0]);
            add_option(self::MOBILE_STYLESHEET, $insert_values[1]);
        }
        return true;
    }

    /**
     * Deleting wordpress options, which storing theme definition(template, stylesheet)
     * @return bool
     */
    private function delete_thema()
    {
        delete_option(self::MOBILE_TEMPLATE);
        delete_option(self::MOBILE_STYLESHEET);
    }

}

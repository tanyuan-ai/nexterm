<?php
if(!defined('ABSPATH'))exit;define('NBU_T_VER','1.28.0');
require_once get_template_directory().'/inc/i18n.php';
function nbu_t_setup(){add_theme_support('title-tag');add_theme_support('post-thumbnails');add_theme_support('automatic-feed-links');add_theme_support('html5',array('search-form','comment-form','comment-list','gallery','caption','style','script'));add_theme_support('responsive-embeds');add_theme_support('align-wide');add_theme_support('wp-block-styles');add_theme_support('custom-logo',array('height'=>48,'width'=>192,'flex-height'=>true,'flex-width'=>true));register_nav_menus(array('primary'=>nbu_t_t('顶部导航菜单','Primary top navigation')));}add_action('after_setup_theme','nbu_t_setup');
function nbu_t_hide_crt_on_pages(){if(is_404() || is_login_page()){wp_dequeue_script('nbu-terminal-crt');}}add_action('wp_enqueue_scripts','nbu_t_hide_crt_on_pages',999);
function is_login_page(){return in_array($GLOBALS['pagenow'],array('wp-login.php','wp-register.php'));}
function nbu_t_assets(){wp_enqueue_style('nbu-terminal',get_stylesheet_uri(),array(),NBU_T_VER);wp_enqueue_script('nbu-terminal',get_template_directory_uri().'/assets/js/app.js',array(),NBU_T_VER,true);wp_enqueue_script('nbu-terminal-crt',get_template_directory_uri().'/assets/js/crt-companion.js',array(),NBU_T_VER,true);if(is_singular() && comments_open() && get_option('thread_comments')){wp_enqueue_script('comment-reply');}}add_action('wp_enqueue_scripts','nbu_t_assets');
function nbu_t_menu_fallback(){echo '<ul class="topbar-menu">';echo '<li class="topbar-item current-menu-item"><a href="'.esc_url(home_url('/')).'" class="topbar-link">'.esc_html(nbu_t_t('首页','Home')).'</a></li>';echo '<li class="topbar-item"><a href="'.esc_url(home_url('/#projects')).'" class="topbar-link">'.esc_html(nbu_t_t('文章','Articles')).'</a></li>';echo '</ul>';}

/* system nav menu: give every link the topbar-link class + depth class for submenu styling */
function nbu_t_nav_menu_link_atts($atts,$item,$args,$depth){
if(isset($args->theme_location) && $args->theme_location==='primary'){
$atts['class']=trim(($atts['class']??'').' topbar-link topbar-link--d'.$depth);
}
return $atts;
}add_filter('nav_menu_link_attributes','nbu_t_nav_menu_link_atts',10,4);
function nbu_t_nav_menu_item_classes($classes,$item,$args,$depth){
if(isset($args->theme_location) && $args->theme_location==='primary'){$classes[]='topbar-item';}
return $classes;
}add_filter('nav_menu_css_class','nbu_t_nav_menu_item_classes',10,4);
function nbu_t_login_style(){wp_enqueue_style('nbu-terminal-login',get_template_directory_uri().'/assets/css/login.css',array(),NBU_T_VER);}add_action('login_enqueue_scripts','nbu_t_login_style');
function nbu_t_login_brand($url){return home_url('/');}add_filter('login_headerurl','nbu_t_login_brand');
function nbu_t_login_title($title){return get_bloginfo('name').' — '.nbu_t_t('登录','Sign in');}add_filter('login_headertext','nbu_t_login_title');

/* custom controls: base classes are required explicitly so the definitions
   work in any context (customize manager loads them later on web requests) */
if(!class_exists('NBU_T_Range_Control')){
require_once ABSPATH.WPINC.'/class-wp-customize-control.php';
class NBU_T_Range_Control extends WP_Customize_Control {
    public $type='nbu_t_range';
    public $input_attrs=array();
    public function render_content(){
        $min=isset($this->input_attrs['min'])?$this->input_attrs['min']:0;
        $max=isset($this->input_attrs['max'])?$this->input_attrs['max']:100;
        $step=isset($this->input_attrs['step'])?$this->input_attrs['step']:1;
        ?>
        <label>
            <?php if(!empty($this->label)):?><span class="customize-control-title"><?php echo esc_html($this->label);?></span><?php endif;?>
            <?php if(!empty($this->description)):?><span class="description customize-control-description"><?php echo esc_html($this->description);?></span><?php endif;?>
            <input type="range" min="<?php echo esc_attr($min);?>" max="<?php echo esc_attr($max);?>" step="<?php echo esc_attr($step);?>" value="<?php echo esc_attr($this->value());?>" style="width:100%" <?php $this->link();?> oninput="this.nextElementSibling.textContent=this.value">
            <output style="display:block;text-align:right;font-size:11px;color:#777;"><?php echo esc_html($this->value());?></output>
        </label>
        <?php
    }
}
}

function nbu_t_customize($c){
$s=nbu_t_strings();
$is_zh=nbu_t_is_zh();

/* ---------- panel: theme root group ---------- */
$c->add_panel('nbu_t_panel',array('title'=>'Nexterm','priority'=>10,'description'=>$is_zh?'Nexterm 主题全部设置项。按区域分组：顶部导航 / 外观 / CRT 显示器 / 状态 LED / 页脚。':'All Nexterm theme settings, grouped by area: topbar / appearance / CRT companion / status LED / footer.'));

/* =================== 1. 顶部导航 Topbar =================== */
$c->add_section('nbu_t_topbar',array('panel'=>'nbu_t_panel','title'=>$s['sec_topbar'],'priority'=>10));
$c->add_setting('nbu_t_logo_text',array('default'=>get_bloginfo('name'),'sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'));
$c->add_control('nbu_t_logo_text',array('section'=>'nbu_t_topbar','label'=>$s['logo_text'],'description'=>$s['logo_text_d'],'type'=>'text'));
$c->add_setting('nbu_t_logo_color_mode',array('default'=>'auto','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_logo_color_mode',array('section'=>'nbu_t_topbar','label'=>$s['logo_color_mode'],'type'=>'select','choices'=>array('auto'=>$s['logo_color_auto'],'custom'=>$s['logo_color_custom'])));
$c->add_setting('nbu_t_logo_color',array('default'=>'#64ffda','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_logo_color',array('section'=>'nbu_t_topbar','label'=>$s['logo_color_custom'],'description'=>$s['logo_color_custom_d'])));
$c->add_setting('nbu_t_logo_fx',array('default'=>'none','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_logo_fx',array('section'=>'nbu_t_topbar','label'=>$s['logo_fx'],'type'=>'select','choices'=>array('none'=>$s['logo_fx_none'],'glow'=>$s['logo_fx_glow'],'blink'=>$s['logo_fx_blink'],'glowblink'=>$s['logo_fx_glowblink'])));
$c->add_setting('nbu_t_logo_size',array('default'=>24,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_logo_size',array('section'=>'nbu_t_topbar','label'=>$s['logo_size'],'input_attrs'=>array('min'=>12,'max'=>48,'step'=>1))));
$c->add_setting('nbu_t_menu_align',array('default'=>'center','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_menu_align',array('section'=>'nbu_t_topbar','label'=>$s['menu_align'],'description'=>$s['menu_align_d'],'type'=>'select','choices'=>array('left'=>$s['menu_left'],'center'=>$s['menu_center'],'right'=>$s['menu_right'])));

/* =================== 1b. 菜单选中样式 Menu active style =================== */
$c->add_section('nbu_t_menu_style',array('panel'=>'nbu_t_panel','title'=>$s['menu_active_style'],'priority'=>11));
$c->add_setting('nbu_t_menu_active_style',array('default'=>'underline','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_menu_active_style',array('section'=>'nbu_t_menu_style','label'=>$s['menu_active_style'],'description'=>$s['menu_active_style_d'],'type'=>'select','choices'=>array('underline'=>$s['menu_style_underline'],'outline'=>$s['menu_style_outline'])));
$c->add_setting('nbu_t_menu_active_thickness',array('default'=>2,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_menu_active_thickness',array('section'=>'nbu_t_menu_style','label'=>$s['menu_style_thickness'],'description'=>$s['menu_style_thickness_d'],'input_attrs'=>array('min'=>1,'max'=>8,'step'=>1))));
$c->add_setting('nbu_t_menu_active_color_mode',array('default'=>'auto','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_menu_active_color_mode',array('section'=>'nbu_t_menu_style','label'=>$s['menu_style_color_mode'],'type'=>'select','choices'=>array('auto'=>$s['menu_style_color_auto'],'custom'=>$s['menu_style_color_custom'])));
$c->add_setting('nbu_t_menu_active_color',array('default'=>'#7c83ff','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_menu_active_color',array('section'=>'nbu_t_menu_style','label'=>$s['menu_style_color_custom'],'active_callback'=>function(){return get_theme_mod('nbu_t_menu_active_color_mode','auto')==='custom';})));

/* =================== 2. 外观 Appearance =================== */
$c->add_section('nbu_t_look',array('panel'=>'nbu_t_panel','title'=>$s['sec_appearance'],'priority'=>20));
$c->add_setting('nbu_t_palette',array('default'=>'nexterm','sanitize_callback'=>'sanitize_key'));
$c->add_control('nbu_t_palette',array('section'=>'nbu_t_look','label'=>$s['palette'],'description'=>$s['palette_d'],'type'=>'select','choices'=>array('nexterm'=>$s['pal_nexterm'],'nord'=>$s['pal_nord'],'dracula'=>$s['pal_dracula'],'monokai'=>$s['pal_monokai'],'hackerblue'=>$s['pal_hackerblue'],'hackergreen'=>$s['pal_hackergreen'],'flexoki'=>$s['pal_flexoki'],'light'=>$s['pal_light'])));
$c->add_setting('nbu_t_radius',array('default'=>'16','sanitize_callback'=>'absint'));
$c->add_control('nbu_t_radius',array('section'=>'nbu_t_look','label'=>$s['radius'],'type'=>'select','choices'=>array('10'=>'10px','14'=>'14px','16'=>'16px','20'=>'20px')));
$c->add_setting('nbu_t_density',array('default'=>'comfortable','sanitize_callback'=>'sanitize_key'));
$c->add_control('nbu_t_density',array('section'=>'nbu_t_look','label'=>$s['density'],'type'=>'select','choices'=>array('compact'=>$s['density_compact'],'comfortable'=>$s['density_comfy'],'spacious'=>$s['density_spacious'])));

/* =================== 3. CRT — 屏幕内容（表情/文字/图片 互斥显示） =================== */
$c->add_section('nbu_t_crt_content',array('panel'=>'nbu_t_panel','title'=>$s['sec_crt_content'],'priority'=>30));
$c->add_setting('nbu_t_crt_content',array('default'=>'face','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_crt_content',array('section'=>'nbu_t_crt_content','label'=>$s['crt_content'],'description'=>$s['crt_content_d'],'type'=>'select','choices'=>array('face'=>$s['crt_face'],'text'=>$s['crt_text'])));

/* text mode only — textarea so kaomoji / multi-line input is comfortable */
$c->add_setting('nbu_t_crt_text',array('default'=>'HELLO WORLD','sanitize_callback'=>'sanitize_textarea_field','transport'=>'postMessage'));
$c->add_control('nbu_t_crt_text',array('section'=>'nbu_t_crt_content','label'=>$s['crt_text_label'],'description'=>$s['crt_text_label_d'],'type'=>'textarea','active_callback'=>function(){return get_theme_mod('nbu_t_crt_content','face')==='text';}));
$c->add_setting('nbu_t_crt_text_fx',array('default'=>'typewriter','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_crt_text_fx',array('section'=>'nbu_t_crt_content','label'=>$s['crt_text_fx'],'type'=>'select','choices'=>array('typewriter'=>$s['fx_typewriter'],'scramble'=>$s['fx_scramble'],'static'=>$s['fx_static']),'active_callback'=>function(){return get_theme_mod('nbu_t_crt_content','face')==='text';}));
$c->add_setting('nbu_t_crt_text_size',array('default'=>100,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_text_size',array('section'=>'nbu_t_crt_content','label'=>$s['crt_text_size'],'description'=>$s['crt_text_size_d'],'input_attrs'=>array('min'=>20,'max'=>300,'step'=>5),'active_callback'=>function(){return get_theme_mod('nbu_t_crt_content','face')==='text';})));

/* =================== 4. CRT — 纹理效果 =================== */
$c->add_section('nbu_t_crt_texture',array('panel'=>'nbu_t_panel','title'=>$s['sec_crt_texture'],'priority'=>40));
$c->add_setting('nbu_t_crt_mode',array('default'=>'scanline','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_crt_mode',array('section'=>'nbu_t_crt_texture','label'=>$s['crt_mode'],'description'=>$s['crt_mode_d'],'type'=>'select','choices'=>array('scanline'=>$s['crt_scanline'],'pixel'=>$s['crt_pixel'])));
$c->add_setting('nbu_t_crt_scanlines',array('default'=>35,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_scanlines',array('section'=>'nbu_t_crt_texture','label'=>$s['scan_density'],'description'=>$s['scan_density_d'],'input_attrs'=>array('min'=>0,'max'=>100,'step'=>1))));
$c->add_setting('nbu_t_crt_scanline_thickness',array('default'=>2,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_scanline_thickness',array('section'=>'nbu_t_crt_texture','label'=>$s['scan_thickness'],'input_attrs'=>array('min'=>1,'max'=>6,'step'=>1))));
$c->add_setting('nbu_t_crt_scan_alpha',array('default'=>35,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_scan_alpha',array('section'=>'nbu_t_crt_texture','label'=>$s['scan_alpha'],'description'=>$s['scan_alpha_d'],'input_attrs'=>array('min'=>0,'max'=>100,'step'=>5))));
/* pixel-mode only settings */
$c->add_setting('nbu_t_crt_pixel_size',array('default'=>50,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_pixel_size',array('section'=>'nbu_t_crt_texture','label'=>$s['pixel_density'],'description'=>$s['pixel_density_d'],'input_attrs'=>array('min'=>0,'max'=>100,'step'=>1),'active_callback'=>function(){return get_theme_mod('nbu_t_crt_mode','scanline')==='pixel';})));
$c->add_setting('nbu_t_crt_pixel_thickness',array('default'=>1,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_pixel_thickness',array('section'=>'nbu_t_crt_texture','label'=>$s['pixel_thickness'],'input_attrs'=>array('min'=>1,'max'=>6,'step'=>1),'active_callback'=>function(){return get_theme_mod('nbu_t_crt_mode','scanline')==='pixel';})));
$c->add_setting('nbu_t_crt_pixel_alpha',array('default'=>35,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_pixel_alpha',array('section'=>'nbu_t_crt_texture','label'=>$s['pixel_alpha'],'description'=>$s['pixel_alpha_d'],'input_attrs'=>array('min'=>0,'max'=>100,'step'=>5),'active_callback'=>function(){return get_theme_mod('nbu_t_crt_mode','scanline')==='pixel';})));

/* =================== 5. CRT — 色泽与辉光 =================== */
$c->add_section('nbu_t_crt_glow',array('panel'=>'nbu_t_panel','title'=>$s['sec_crt_glow'],'priority'=>50));
$c->add_setting('nbu_t_crt_color',array('default'=>'#00ff41','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_crt_color',array('section'=>'nbu_t_crt_glow','label'=>$s['crt_color'])));
$c->add_setting('nbu_t_crt_case_color',array('default'=>'#26272b','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_crt_case_color',array('section'=>'nbu_t_crt_glow','label'=>$s['crt_case_color'])));
$c->add_setting('nbu_t_crt_ledon_color',array('default'=>'#2ee06a','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_crt_ledon_color',array('section'=>'nbu_t_crt_glow','label'=>$s['crt_ledon_color'])));
$c->add_setting('nbu_t_crt_glow',array('default'=>50,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_glow',array('section'=>'nbu_t_crt_glow','label'=>$s['glow'],'input_attrs'=>array('min'=>0,'max'=>150,'step'=>5))));
$c->add_setting('nbu_t_crt_edge_glow',array('default'=>60,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_edge_glow',array('section'=>'nbu_t_crt_glow','label'=>$s['edge_glow'],'input_attrs'=>array('min'=>0,'max'=>150,'step'=>5))));

/* =================== 6. CRT — 表情设置（仅表情模式显示） =================== */
$c->add_section('nbu_t_crt_face',array('panel'=>'nbu_t_panel','title'=>$s['sec_crt_face'],'priority'=>60));
$face_only=function(){return get_theme_mod('nbu_t_crt_content','face')==='face';};
$c->add_setting('nbu_t_crt_eye_width',array('default'=>40,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_eye_width',array('section'=>'nbu_t_crt_face','label'=>$s['eye_width'],'description'=>$s['eye_width_d'],'input_attrs'=>array('min'=>5,'max'=>80,'step'=>1))));
$c->add_setting('nbu_t_crt_eye_height',array('default'=>40,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_eye_height',array('section'=>'nbu_t_crt_face','label'=>$s['eye_height'],'description'=>$s['eye_height_d'],'input_attrs'=>array('min'=>5,'max'=>80,'step'=>1))));
$c->add_setting('nbu_t_crt_eye_radius',array('default'=>50,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_crt_eye_radius',array('section'=>'nbu_t_crt_face','label'=>$s['eye_radius'],'description'=>$s['eye_radius_d'],'input_attrs'=>array('min'=>0,'max'=>50,'step'=>1))));
/* the whole face section hides when content != face */
$c->get_section('nbu_t_crt_face')->active_callback=$face_only;

/* =================== 7. 顶部状态 LED =================== */
$c->add_section('nbu_t_led',array('panel'=>'nbu_t_panel','title'=>$s['sec_led'],'priority'=>70));
$c->add_setting('nbu_t_led_text',array('default'=>'SYSTEM READY','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'));
$c->add_control('nbu_t_led_text',array('section'=>'nbu_t_led','label'=>$s['led_text'],'description'=>$s['led_text_d'],'type'=>'text'));
$c->add_setting('nbu_t_led_text_fx',array('default'=>'scramble','sanitize_callback'=>'sanitize_key','transport'=>'postMessage'));
$c->add_control('nbu_t_led_text_fx',array('section'=>'nbu_t_led','label'=>$s['led_fx'],'description'=>$s['fx_scramble_d'],'type'=>'select','choices'=>array('scramble'=>$s['fx_scramble'],'typewriter'=>$s['fx_typewriter'],'static'=>$s['fx_static'])));
$c->add_setting('nbu_t_led_color',array('default'=>'#51d6a7','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage'));
$c->add_control(new WP_Customize_Color_Control($c,'nbu_t_led_color',array('section'=>'nbu_t_led','label'=>$s['led_color'])));
$c->add_setting('nbu_t_led_brightness',array('default'=>100,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_led_brightness',array('section'=>'nbu_t_led','label'=>$s['led_brightness'],'input_attrs'=>array('min'=>10,'max'=>200,'step'=>5))));
$c->add_setting('nbu_t_led_speed',array('default'=>26,'sanitize_callback'=>'absint','transport'=>'postMessage'));
$c->add_control(new NBU_T_Range_Control($c,'nbu_t_led_speed',array('section'=>'nbu_t_led','label'=>$s['led_speed'],'description'=>$s['led_speed_d'],'input_attrs'=>array('min'=>5,'max'=>60,'step'=>1))));

/* =================== 8. 页脚（三段，均为 文字 + 可选链接；右段可选时区/纯文字） =================== */
$c->add_section('nbu_t_footer',array('panel'=>'nbu_t_panel','title'=>$s['sec_footer'],'priority'=>80));
$c->add_setting('nbu_t_footer_left',array('default'=>'← '.nbu_t_t('返回首页','Back to Home'),'sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_left',array('section'=>'nbu_t_footer','label'=>$s['footer_left'],'description'=>$s['footer_left_d'],'type'=>'text'));
$c->add_setting('nbu_t_footer_left_url',array('default'=>'','sanitize_callback'=>'esc_url_raw','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_left_url',array('section'=>'nbu_t_footer','label'=>$s['footer_link'],'description'=>$s['footer_link_d'],'type'=>'url','input_attrs'=>array('placeholder'=>'https://')));
$c->add_setting('nbu_t_footer_center',array('default'=>'','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_center',array('section'=>'nbu_t_footer','label'=>$s['footer_center'],'description'=>$s['footer_center_d'],'type'=>'text'));
$c->add_setting('nbu_t_footer_center_url',array('default'=>'','sanitize_callback'=>'esc_url_raw','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_center_url',array('section'=>'nbu_t_footer','label'=>$s['footer_link'],'description'=>$s['footer_link_d'],'type'=>'url','input_attrs'=>array('placeholder'=>'https://')));
$c->add_setting('nbu_t_footer_right',array('default'=>'CST / --','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_right',array('section'=>'nbu_t_footer','label'=>$s['footer_right'],'description'=>$s['footer_right_d'],'type'=>'text'));
$c->add_setting('nbu_t_footer_tz',array('default'=>'Asia/Shanghai','sanitize_callback'=>'sanitize_text_field'));
$c->add_control('nbu_t_footer_tz',array('section'=>'nbu_t_footer','label'=>$s['footer_tz'],'description'=>$s['footer_tz_d'],'type'=>'select','choices'=>array('Asia/Shanghai'=>'UTC+8 北京/CST','UTC'=>'UTC','America/New_York'=>'UTC-5 纽约','America/Los_Angeles'=>'UTC-8 洛杉矶','Europe/London'=>'UTC+0 伦敦','Europe/Berlin'=>'UTC+1 柏林','Asia/Tokyo'=>'UTC+9 东京','Asia/Singapore'=>'UTC+8 新加坡','Australia/Sydney'=>'UTC+10 悉尼')));
$c->add_setting('nbu_t_footer_right_url',array('default'=>'','sanitize_callback'=>'esc_url_raw','transport'=>'postMessage'));
$c->add_control('nbu_t_footer_right_url',array('section'=>'nbu_t_footer','label'=>$s['footer_link'],'description'=>$s['footer_right_link_d'],'type'=>'url','input_attrs'=>array('placeholder'=>'https://')));

}add_action('customize_register','nbu_t_customize');

function nbu_t_body_class($classes){
$mode=get_theme_mod('nbu_t_crt_mode','scanline');
if($mode==='pixel')$classes[]='crt-mode-pixel';
$mas=get_theme_mod('nbu_t_menu_active_style','underline');
if($mas==='outline')$classes[]='menu-style-outline';else $classes[]='menu-style-underline';
$fx=get_theme_mod('nbu_t_logo_fx','none');
if($fx==='glow')$classes[]='logo-fx-glow';
elseif($fx==='blink')$classes[]='logo-fx-blink';
elseif($fx==='glowblink')$classes[]='logo-fx-glow logo-fx-blink';
return $classes;
}add_filter('body_class','nbu_t_body_class');

function nbu_t_widgets_init(){
register_sidebar(array('name'=>nbu_t_t('侧边栏小工具','Sidebar widgets'),'id'=>'sidebar-1','description'=>nbu_t_t('显示在侧边栏 CRT 屏幕下方','Shown under the CRT screen in the sidebar'),'before_widget'=>'<section id="%1$s" class="widget %2$s">','after_widget'=>'</section>','before_title'=>'<p class="label">','after_title'=>'</p>'));
}add_action('widgets_init','nbu_t_widgets_init');

function nbu_t_editor_styles(){add_editor_style('style.css');}add_action('after_setup_theme','nbu_t_editor_styles');

function nbu_t_vars(){ $ps=array('nexterm'=>array('#171924','#1d2033','#282b3d','#30344b','#ddddea','#a9abbb','#777b92','#7c83ff','#51d6a7','#f5c76e'),'nord'=>array('#2e3440','#292e39','#3b4252','#434c5e','#eceff4','#d8dee9','#81a1c1','#88c0d0','#a3be8c','#ebcb8b'),'dracula'=>array('#282a36','#21222c','#343746','#414558','#f8f8f2','#d9d5ef','#8b89a6','#bd93f9','#50fa7b','#f1fa8c'),'monokai'=>array('#272822','#20211d','#35362f','#414339','#f8f8f2','#d5d6c8','#8e9283','#a6e22e','#a6e22e','#e6db74'),'hackerblue'=>array('#071824','#0a2131','#0d2b3e','#12354b','#d8f1ff','#a9c9db','#668da2','#32a7ff','#2fe0a1','#f4cd70'),'hackergreen'=>array('#07130d','#0b1b12','#102519','#163322','#d8f7df','#a5ccb0','#5f8e6c','#4eea83','#4eea83','#e6d75d'),'flexoki'=>array('#1c1b1a','#242321','#302e2b','#3a3835','#cecdc3','#b7b5ac','#87847b','#d0a215','#879a39','#d0a215'),'light'=>array('#f4f5f8','#ffffff','#ffffff','#edf0f7','#20222a','#515767','#7a8090','#5765d9','#248b64','#a96b13'));$v=$ps[get_theme_mod('nbu_t_palette','nexterm')]??$ps['nexterm'];$n=array('--bg','--side','--panel','--raise','--text','--sub','--muted','--accent','--ok','--warn');$x=':root{';foreach($n as $i=>$k)$x.=$k.':'.$v[$i].';';$x.='--radius:'.absint(get_theme_mod('nbu_t_radius',16)).'px;';$d=get_theme_mod('nbu_t_density','comfortable');$x.='--density:'.($d==='compact'?'.84':($d==='spacious'?'1.18':'1')).';';

$x.='--crt-color:'.sanitize_hex_color(get_theme_mod('nbu_t_crt_color','#00ff41')).';';
$x.='--crt-case-color:'.sanitize_hex_color(get_theme_mod('nbu_t_crt_case_color','#26272b')).';';
$x.='--crt-ledon-color:'.sanitize_hex_color(get_theme_mod('nbu_t_crt_ledon_color','#2ee06a')).';';

$scanDensity=absint(get_theme_mod('nbu_t_crt_scanlines',35));$scanGap=max(2,round(6-($scanDensity/100)*4));
$x.='--crt-scan-gap:'.$scanGap.'px;';
$x.='--crt-scan-thickness:'.absint(get_theme_mod('nbu_t_crt_scanline_thickness',2)).'px;';
$scanAlphaPct=absint(get_theme_mod('nbu_t_crt_scan_alpha',35));$x.='--crt-scan-alpha:'.$scanAlphaPct.'%;';

$pixelSizePct=absint(get_theme_mod('nbu_t_crt_pixel_size',50));$pixelGap=max(2,round(10-($pixelSizePct/100)*8));
$x.='--crt-pixel-gap:'.$pixelGap.'px;';
$x.='--crt-pixel-thickness:'.absint(get_theme_mod('nbu_t_crt_pixel_thickness',1)).'px;';
$pixelAlphaPct=absint(get_theme_mod('nbu_t_crt_pixel_alpha',35));$x.='--crt-pixel-alpha:'.$pixelAlphaPct.'%;';

$glowPct=absint(get_theme_mod('nbu_t_crt_glow',50));$x.='--crt-glow-mult:'.round($glowPct/50,2).';';

$edgePct=absint(get_theme_mod('nbu_t_crt_edge_glow',60));$x.='--crt-edge-glow-mult:'.round($edgePct/50,2).';';

$eyeW=absint(get_theme_mod('nbu_t_crt_eye_width',40));$x.='--crt-eye-w:'.$eyeW.'cqw;';
$eyeH=absint(get_theme_mod('nbu_t_crt_eye_height',40));$x.='--crt-eye-h:'.$eyeH.'cqh;';
$eyeR=absint(get_theme_mod('nbu_t_crt_eye_radius',50));$x.='--crt-eye-radius:'.$eyeR.'%;';

$ledBrightness=absint(get_theme_mod('nbu_t_led_brightness',100));$x.='--led-brightness:'.round($ledBrightness/100,2).';';
$ledSpeed=absint(get_theme_mod('nbu_t_led_speed',26));$x.='--led-speed:'.round($ledSpeed/10,2).'s;';
$x.='--led-color:'.sanitize_hex_color(get_theme_mod('nbu_t_led_color','#51d6a7')).';';

$logoColorMode=get_theme_mod('nbu_t_logo_color_mode','auto');
if($logoColorMode==='custom'){
$x.='--logo-color:'.sanitize_hex_color(get_theme_mod('nbu_t_logo_color','#64ffda')).';';
}else{
$x.='--logo-color:var(--accent);';
}
$logoSize=absint(get_theme_mod('nbu_t_logo_size',24));
$x.='--logo-size:'.$logoSize.'px;';

/* CRT text mode size */
$txtSize=absint(get_theme_mod('nbu_t_crt_text_size',100));$x.='--crt-text-size:'.round($txtSize/100,2).';';

/* menu active style: color + thickness */
$menuColorMode=get_theme_mod('nbu_t_menu_active_color_mode','auto');
if($menuColorMode==='custom'){$x.='--menu-active-color:'.sanitize_hex_color(get_theme_mod('nbu_t_menu_active_color','#7c83ff')).';';}
else{$x.='--menu-active-color:var(--accent);';}
$menuTh=absint(get_theme_mod('nbu_t_menu_active_thickness',2));$x.='--menu-active-thickness:'.$menuTh.'px;';

$x.='}';wp_add_inline_style('nbu-terminal',$x);}add_action('wp_enqueue_scripts','nbu_t_vars',20);

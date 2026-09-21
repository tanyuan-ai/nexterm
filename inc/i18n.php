<?php
/**
 * Nexterm i18n helper.
 *
 * All Customizer strings live here and follow the site locale:
 * zh locale -> Chinese, everything else -> English.
 */
if(!defined('ABSPATH'))exit;

function nbu_t_is_zh(){return str_starts_with(get_locale(),'zh');}
function nbu_t_t($zh,$en){return nbu_t_is_zh()?$zh:$en;}

/**
 * Central Customizer string table. Grouped by section.
 */
function nbu_t_strings(){
$s=array(

// sections
'sec_topbar'      =>array('顶部导航','Top Navigation'),
'sec_appearance'  =>array('主题外观','Appearance'),
'sec_crt_content' =>array('CRT 显示器 · 屏幕内容','CRT Companion · Screen Content'),
'sec_crt_texture' =>array('CRT 显示器 · 纹理效果','CRT Companion · Texture Effects'),
'sec_crt_glow'    =>array('CRT 显示器 · 色泽与辉光','CRT Companion · Glow'),
'sec_crt_face'    =>array('CRT 显示器 · 表情设置','CRT Companion · Face Settings'),
'sec_led'         =>array('顶部状态 LED','Topbar Status LED'),
'sec_footer'      =>array('页脚','Footer'),

// topbar
'logo_text'       =>array('Logo 文字','Logo text'),
'logo_text_d'     =>array('留空则使用站点名称','Leave empty to use the site name'),
'logo_color_mode' =>array('Logo 颜色','Logo color'),
'logo_color_auto' =>array('自动（跟随主题色）','Auto (follow theme accent)'),
'logo_color_custom'=>array('手动指定','Manual'),
'logo_color_custom_d'=>array('仅当颜色模式为“手动指定”时生效','Only applies when the color mode is Manual'),
'logo_size'       =>array('Logo 大小','Logo size'),
'menu_align'      =>array('菜单位置','Menu position'),
'menu_align_d'    =>array('顶部导航菜单在顶栏中的对齐方式','Alignment of the top navigation inside the topbar'),
'menu_left'       =>array('居左（靠近 Logo）','Left (next to the logo)'),
'menu_center'     =>array('居中','Center'),
'menu_right'      =>array('居右（靠近 LED）','Right (next to the LED)'),

// appearance
'palette'         =>array('配色方案','Color palette'),
'palette_d'       =>array('全站通用，访客端无法修改','Applies site-wide; visitors cannot change it'),
'pal_nexterm'     =>array('Nexterm 深空紫','Nexterm deep purple'),
'pal_nord'        =>array('Nord 深色','Nord dark'),
'pal_dracula'     =>array('Dracula','Dracula'),
'pal_monokai'     =>array('Monokai','Monokai'),
'pal_hackerblue'  =>array('黑客蓝','Hacker blue'),
'pal_hackergreen' =>array('黑客绿','Hacker green'),
'pal_flexoki'     =>array('Flexoki 深色','Flexoki dark'),
'pal_light'       =>array('浅色工作台','Light workbench'),
'radius'          =>array('面板圆角','Panel radius'),
'density'         =>array('界面密度','UI density'),
'density_compact' =>array('紧凑','Compact'),
'density_comfy'   =>array('适中','Comfortable'),
'density_spacious'=>array('宽松','Spacious'),

// CRT content
'crt_content'     =>array('屏幕内容','Screen content'),
'crt_content_d'   =>array('切换下方出现的设置项：表情 / 文字','Switches which settings appear below: face / text'),
'crt_face'        =>array('表情（互动眼睛）','Face (interactive eyes)'),
'crt_text'        =>array('自定义文字','Custom text'),
'crt_image'       =>array('自定义图片','Custom image'),
'crt_text_label'  =>array('屏幕文字','Screen text'),
'crt_text_fx'     =>array('文字特效','Text effect'),
'fx_typewriter'   =>array('打字机','Typewriter'),
'fx_scramble'     =>array('乱码跳动','Scramble'),
'fx_static'       =>array('静态显示','Static'),
'crt_text_label_d'=>array('支持颜文字或多行文字（例如 (╯°□°)╯︵ ┻━┻）','Supports kaomoji and multi-line text, e.g. (╯°□°)╯︵ ┻━┻'),
'crt_text_size'   =>array('文字大小','Text size'),
'crt_text_size_d' =>array('100% 为默认大小；范围 20%–300%，文字多时自动循环滚动','100% is the default; range 20%–300%. Long text scrolls in a loop automatically'),
'logo_fx'         =>array('Logo 特效','Logo effect'),
'menu_active_style'  =>array('菜单选中样式','Menu active style'),
'menu_active_style_d'=>array('导航链接的悬停与当前页样式','Hover & current-page style for nav links'),
'menu_style_underline'=>array('下划线（无方框）','Underline (no box)'),
'menu_style_outline'  =>array('边框（圆角描边）','Outline (rounded pill)'),
'menu_style_thickness'=>array('粗细','Thickness'),
'menu_style_thickness_d'=>array('下划线或描边的像素宽度','Pixel width of the underline or outline'),
'menu_style_color_mode'=>array('颜色','Color'),
'menu_style_color_auto'=>array('跟随主题色','Follow theme accent'),
'menu_style_color_custom'=>array('自定义颜色','Custom color'),
'logo_fx_none'    =>array('无','None'),
'logo_fx_glow'    =>array('发光','Glow'),
'logo_fx_blink'   =>array('闪烁','Blink'),
'logo_fx_glowblink'=>array('发光 + 闪烁','Glow + blink'),

// CRT texture
'crt_mode'        =>array('纹理类型','Texture type'),
'crt_mode_d'      =>array('叠加在屏幕内容之上的复古纹理','Retro texture overlaid on the screen content'),
'crt_scanline'    =>array('横向扫描线','Scanlines'),
'crt_pixel'       =>array('像素颗粒','Pixel grid'),
'scan_density'    =>array('扫描线密度','Scanline density'),
'scan_density_d'  =>array('0 为关闭，数值越大越明显','0 disables; higher is more visible'),
'scan_thickness'  =>array('扫描线粗细','Scanline thickness'),
'scan_alpha'      =>array('扫描线透明度','Scanline opacity'),
'scan_alpha_d'    =>array('数值越大扫描线越深','Higher values make scanlines darker'),
'pixel_density'   =>array('像素块密度','Pixel grid density'),
'pixel_density_d' =>array('数值越大颗粒越细密','Higher values make the grid finer'),
'pixel_thickness' =>array('网格线粗细','Grid line thickness'),
'pixel_alpha'     =>array('像素透明度','Pixel opacity'),
'pixel_alpha_d'   =>array('数值越大颗粒感越明显','Higher values make the grid more visible'),

// CRT glow
'crt_color'       =>array('屏幕显示颜色','Screen color'),
'crt_case_color'  =>array('显示器外壳颜色','Monitor case color'),
'crt_ledon_color' =>array('ON 指示灯颜色','ON LED color'),
'glow'            =>array('整体辉光强度','Overall glow strength'),
'edge_glow'       =>array('边缘内发光','Edge glow'),

// CRT face
'eye_width'       =>array('眼睛宽度','Eye width'),
'eye_width_d'     =>array('占屏幕宽度的百分比','% of the screen width'),
'eye_height'      =>array('眼睛高度','Eye height'),
'eye_height_d'    =>array('占屏幕高度的百分比','% of the screen height'),
'eye_radius'      =>array('眼睛圆角','Eye roundness'),
'eye_radius_d'    =>array('0 为方形，50 为圆形','0 = square, 50 = circle'),

// LED
'led_text'        =>array('状态文字','Status text'),
'led_text_d'      =>array('显示在 LED 灯右侧','Shown to the right of the LED dot'),
'led_fx'          =>array('文字动效','Text effect'),
'fx_scramble_d'   =>array('默认；字符跳动后定格','Default; characters jitter then settle'),
'led_color'       =>array('LED 颜色','LED color'),
'led_brightness'  =>array('LED 亮度','LED brightness'),
'led_speed'       =>array('呼吸频率','Breathing speed'),
'led_speed_d'     =>array('数值越小闪烁越快','Lower is faster'),

// footer
'footer_left'     =>array('左侧文字','Left text'),
'footer_left_d'   =>array('页脚最左边的文字','The leftmost footer text'),
'footer_center'   =>array('中间文字','Center text'),
'footer_center_d' =>array('留空则显示站点名称','Leave empty to use the site name'),
'footer_right'    =>array('右侧文字','Right text'),
'footer_right_d'  =>array('其中的 -- 会被实时时钟（时:分:秒）替换；时区在下方选择','The -- placeholder is replaced by the live clock; pick a time zone below'),
'footer_tz'       =>array('时钟时区','Clock time zone'),
'footer_tz_d'     =>array('仅当右段为纯文字（未设链接）时生效，显示实时时钟','Applies when the right slot is plain text (no link); renders the live clock'),
'footer_link'     =>array('自定义链接（可选）','Custom link (optional)'),
'footer_link_d'   =>array('填写后此段变成可点击链接；留空则显示纯文字','Fill to make this slot a clickable link; leave empty for plain text'),
'footer_right_link_d'=>array('填写后右段变成链接（时钟不再显示）；留空显示实时时钟','Fill to make the right slot a link (clock hidden); leave empty for the live clock'),

// sidebar widget area
'widget_area'     =>array('侧边栏小工具','Sidebar widgets'),
'widget_area_d'   =>array('显示在侧边栏 CRT 屏幕下方','Shown under the CRT screen in the sidebar'),
);
$r=array();
foreach($s as $k=>$v)$r[$k]=nbu_t_is_zh()?$v[0]:$v[1];
return $r;
}

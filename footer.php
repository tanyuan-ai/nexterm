<?php if(!is_404()):?></div><?php endif;?><footer class="site-footer" role="contentinfo"><?php
/* footer: three slots, each = custom text + optional custom link (empty link = plain text) */
$left_text=get_theme_mod('nbu_t_footer_left','← '.nbu_t_t('返回首页','Back to Home'));
$left_url=get_theme_mod('nbu_t_footer_left_url','');
$center_text=get_theme_mod('nbu_t_footer_center') ?: get_bloginfo('name');
$center_url=get_theme_mod('nbu_t_footer_center_url','');
$right_text=get_theme_mod('nbu_t_footer_right','CST / --');
$right_url=get_theme_mod('nbu_t_footer_right_url','');
?>
<span class="site-footer-slot site-footer-home"><?php
if($left_url){echo '<a href="'.esc_url($left_url).'">'.esc_html($left_text).'</a>';}else{echo esc_html($left_text);}
?></span>
<span class="site-footer-slot site-footer-name"><?php
if($center_url){echo '<a href="'.esc_url($center_url).'">'.esc_html($center_text).'</a>';}else{echo esc_html($center_text);}
?></span>
<span class="site-footer-slot site-footer-clock"><?php
/* right slot: live clock (custom tz, -- placeholder) when plain; becomes a link when a URL is set */
if($right_url){
echo '<a href="'.esc_url($right_url).'">'.esc_html($right_text).'</a>';
}else{
echo '<span id="footer-clock" data-template="'.esc_attr($right_text).'" data-tz="'.esc_attr(get_theme_mod('nbu_t_footer_tz','Asia/Shanghai')).'">'.esc_html($right_text).'</span>';
}
?></span>
</footer><?php if(!is_404()):?></div><?php endif;?><?php wp_footer(); ?></body></html>

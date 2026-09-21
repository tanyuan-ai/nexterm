<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main">Skip to content</a>

<?php if(is_404()): ?>
<!-- 404 页面：无侧边栏/CRT/顶栏，全宽居中布局 -->
<main id="main" class="canvas error-page error-page--standalone">

<?php else: ?>
<!-- 正常页面：侧边栏 + 顶栏 + 内容 -->
<div class="app">
<aside class="sidebar" id="nbu-sidebar">

<div class="crt-brand" role="button" tabindex="0" aria-label="Terminal companion, click to interact" data-crt-brand>
<div class="crt-case">
<span class="crt-notch" aria-hidden="true"></span>
<div class="crt-topbar">
<span class="crt-rec"><i class="crt-rec-dot" aria-hidden="true"></i>REC</span>
<span class="crt-model">TERMINAL-01</span>
<span class="crt-on"><i class="crt-on-dot" data-crt-led aria-hidden="true"></i>ON</span>
</div>
<div class="crt-screen" data-crt-screen data-crt-content="<?php echo esc_attr(get_theme_mod('nbu_t_crt_content','face')); ?>" data-crt-text="<?php echo esc_attr(get_theme_mod('nbu_t_crt_text','HELLO WORLD')); ?>" data-crt-text-fx="<?php echo esc_attr(get_theme_mod('nbu_t_crt_text_fx','typewriter')); ?>">
<span class="crt-static" data-crt-static aria-hidden="true"></span>
<span class="crt-glitch" data-crt-glitch aria-hidden="true"></span>
<span class="crt-face" data-crt-face aria-hidden="true">
<span class="crt-eye" data-crt-eye></span>
<span class="crt-eye" data-crt-eye></span>
</span>
<span class="crt-text" data-crt-textout aria-hidden="true"></span>
<span class="crt-zzz" data-crt-zzz aria-hidden="true">zZz</span>
<span class="crt-corner crt-corner-tl" data-crt-status>STATUS: BOOT</span>
<span class="crt-corner crt-corner-tr" data-crt-term>TERM: vt100</span>
<span class="crt-corner crt-corner-bl" data-crt-uptime>UP: 00:00:00</span>
<span class="crt-corner crt-corner-br" data-crt-sig>SIG: -42dBm</span>
<span class="crt-scanlines" aria-hidden="true"></span>
<span class="crt-vignette" aria-hidden="true"></span>
</div>
<div class="crt-plate">CRT DISPLAY · P31 PHOSPHOR · 15.7 kHz</div>
</div>
</div>

<p class="label">Workspace</p>
<nav class="nav">
<a class="active" href="<?php echo esc_url(home_url('/')); ?>#projects"><i class="dot"></i>Articles</a>
<a href="<?php echo esc_url(home_url('/')); ?>#archive">Archive</a>
</nav>

<div>
<p class="label" style="margin-top:34px">Categories</p>
<div class="collection">
<?php $cats=get_categories(array('hide_empty'=>false));$current=is_category()?get_queried_object_id():0;if($cats):foreach($cats as $cat):?>
<a data-category-link="<?php echo esc_attr($cat->slug); ?>" class="<?php echo $current===$cat->term_id?'active':''; ?>" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><i></i><?php echo esc_html($cat->name); ?></a>
<?php endforeach;else:?><a href="#projects"><i></i>Uncategorized</a><?php endif;?>
</div>
</div>

<?php if(is_active_sidebar('sidebar-1')):?>
<div class="sidebar-widgets">
<?php dynamic_sidebar('sidebar-1'); ?>
</div>
<?php endif;?>

<div class="bottom">
<p class="label">Links</p>
<?php if(is_user_logged_in()):
if(current_user_can('edit_posts')):?>
<a class="side-link" href="<?php echo esc_url(admin_url('post-new.php')); ?>">Write post <b>↗</b></a>
<?php endif;?>
<a class="side-link" href="<?php echo esc_url(admin_url()); ?>">Dashboard <b>↗</b></a>
<a class="side-link" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Sign out <b>↗</b></a>
<?php else:?>
<a class="side-link" href="<?php echo esc_url(wp_login_url(home_url('/'))); ?>">Sign in <b>↗</b></a>
<?php endif;?>
<a class="side-link" href="#top">Back to top <b>↑</b></a>
<a class="side-link" href="<?php echo esc_url(home_url('/')); ?>">Home <b>↗</b></a>
</div>

</aside>

<div class="main">
<!-- 顶部菜单栏：Logo + 导航 + 状态 -->
<header class="topbar" id="top">
<button class="menu" aria-label="<?php echo esc_attr(nbu_t_t('切换侧边栏','Toggle sidebar')); ?>" aria-expanded="false" aria-controls="nbu-sidebar">☰</button>
<a class="topbar-logo" href="<?php echo esc_url(home_url('/')); ?>">
<span class="topbar-logo-text"><?php echo esc_html(get_theme_mod('nbu_t_logo_text') ?: get_bloginfo('name')); ?></span>
</a>
<nav class="topbar-nav topbar-nav--<?php echo esc_attr(get_theme_mod('nbu_t_menu_align','center')); ?>" aria-label="<?php echo esc_attr(nbu_t_t('顶部导航','Primary')); ?>">
<?php
wp_nav_menu(array(
  'theme_location'=>'primary',
  'container'=>false,
  'menu_class'=>'topbar-menu',
  'fallback_cb'=>'nbu_t_menu_fallback'
));
?>
</nav>
<span class="topbar-status"><span class="topbar-led"></span><span class="topbar-ledtext" id="topbar-ledtext" data-ledtext="<?php echo esc_attr(get_theme_mod('nbu_t_led_text','SYSTEM READY')); ?>" data-ledtext-fx="<?php echo esc_attr(get_theme_mod('nbu_t_led_text_fx','scramble')); ?>">--</span></span>
</header>

<?php endif; // end is_404 check ?>

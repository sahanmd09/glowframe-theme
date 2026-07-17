<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
  <div class="topbar bn">সারা বাংলাদেশে ক্যাশ অন ডেলিভারি 🚚 &nbsp;•&nbsp; কাস্টম ছবি দিয়ে <b>৪৮ ঘণ্টায়</b> রেডি</div>
  <div class="nav">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
      <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
        Glow<span>Frame</span>
      <?php endif; ?>
    </a>
    <?php
    wp_nav_menu( array(
      'theme_location' => 'primary',
      'fallback_cb'    => 'glowframe_default_menu',
      'items_wrap'     => '<ul id="%1$s" class="nav-links">%3$s</ul>',
    ) );
    ?>
    <div class="nav-actions">
      <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="icon-btn" title="Shop">🔍</a>
      <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn-primary btn-sm">অর্ডার করুন</a>
    </div>
  </div>
</header>

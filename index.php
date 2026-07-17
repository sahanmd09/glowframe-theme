<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<div class="wrap" style="padding:60px 0;">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article style="margin-bottom:40px;">
			<h2 class="p-title" style="font-size:24px;"><a href="<?php the_permalink(); ?>" style="color:var(--cream);"><?php the_title(); ?></a></h2>
			<div style="color:var(--muted);"><?php the_excerpt(); ?></div>
		</article>
	<?php endwhile; else : ?>
		<p style="color:var(--muted);"><?php _e( 'Nothing found.', 'glowframe' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
while ( have_posts() ) : the_post();
?>
<div class="wrap" style="padding:60px 0; max-width:800px;">
	<h1 class="p-title"><?php the_title(); ?></h1>
	<div class="entry-content" style="color:var(--muted); line-height:1.8;">
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; get_footer(); ?>

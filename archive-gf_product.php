<?php
/**
 * Shop archive — grid of all products (and category archives)
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$title = is_tax( 'gf_category' ) ? single_term_title( '', false ) : 'সব প্রোডাক্ট';
?>

<div class="wrap archive-head">
  <span class="tag">Shop</span>
  <h1><?php echo esc_html( $title ); ?></h1>
</div>

<div class="wrap archive-grid">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    $price     = get_post_meta( get_the_ID(), '_gf_price', true );
    $old_price = get_post_meta( get_the_ID(), '_gf_old_price', true );
    $badge     = get_post_meta( get_the_ID(), '_gf_badge', true );
    $cat_terms = get_the_terms( get_the_ID(), 'gf_category' );
    $cat_name  = $cat_terms && ! is_wp_error( $cat_terms ) ? $cat_terms[0]->name : '';
  ?>
  <div class="prod-card">
    <a href="<?php the_permalink(); ?>" class="prod-link">
      <div class="prod-thumb">
        <?php if ( $badge ) : ?><span class="badge"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
        <span class="wish-btn" onclick="event.stopPropagation(); event.preventDefault();">♡</span>
        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large' ); else : ?>
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg' ); ?>" alt="">
        <?php endif; ?>
      </div>
    </a>
    <div class="prod-info">
      <span class="cat"><?php echo esc_html( $cat_name ); ?></span>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <div class="price-row">
        <span class="price">৳<?php echo esc_html( number_format_i18n( $price ) ); ?></span>
        <?php if ( $old_price ) : ?><span class="price-old">৳<?php echo esc_html( number_format_i18n( $old_price ) ); ?></span><?php endif; ?>
      </div>
      <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm btn-block">কাস্টমাইজ করুন</a>
    </div>
  </div>
  <?php endwhile; else : ?>
    <p class="bn" style="color:var(--muted);">এখনো কোনো প্রোডাক্ট যোগ করা হয়নি।</p>
  <?php endif; ?>
</div>

<div class="wrap pagination-row">
  <?php
  echo paginate_links( array(
    'prev_text' => '←',
    'next_text' => '→',
  ) );
  ?>
</div>

<?php get_footer(); ?>

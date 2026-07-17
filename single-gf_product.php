<?php
/**
 * Single product template
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

while ( have_posts() ) : the_post();
	$product_id = get_the_ID();
	$price      = get_post_meta( $product_id, '_gf_price', true );
	$old_price  = get_post_meta( $product_id, '_gf_old_price', true );
	$sizes      = get_post_meta( $product_id, '_gf_sizes', true );
	$colors     = get_post_meta( $product_id, '_gf_colors', true );
	$size_arr   = $sizes ? array_map( 'trim', explode( ',', $sizes ) ) : array();
	$color_arr  = $colors ? array_map( 'trim', explode( ',', $colors ) ) : array();
	$gallery    = glowframe_get_product_gallery( $product_id );
	$cat_terms  = get_the_terms( $product_id, 'gf_category' );
	$cat_name   = $cat_terms && ! is_wp_error( $cat_terms ) ? $cat_terms[0]->name : '';
	$checkout_page = get_page_by_path( 'checkout' );
	$checkout_url  = $checkout_page ? get_permalink( $checkout_page->ID ) : home_url( '/checkout/' );
	$fallback_img  = get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg';
?>

<div class="wrap crumb">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">হোম</a> /
  <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop</a> /
  <span><?php the_title(); ?></span>
</div>

<section class="product-section">
  <div class="wrap product-grid">

    <div>
      <div class="gallery-main">
        <?php if ( $gallery ) : ?>
          <img src="<?php echo esc_url( wp_get_attachment_image_url( $gallery[0], 'large' ) ); ?>" id="mainImg" alt="<?php the_title_attribute(); ?>">
        <?php else : ?>
          <img src="<?php echo esc_url( $fallback_img ); ?>" id="mainImg" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
      </div>
      <?php if ( count( $gallery ) > 1 ) : ?>
      <div class="gallery-thumbs">
        <?php foreach ( $gallery as $i => $att_id ) :
          $url = wp_get_attachment_image_url( $att_id, 'large' );
          $thumb = wp_get_attachment_image_url( $att_id, 'thumbnail' );
        ?>
          <img src="<?php echo esc_url( $thumb ); ?>" data-full="<?php echo esc_url( $url ); ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" onclick="document.getElementById('mainImg').src=this.dataset.full; document.querySelectorAll('.gallery-thumbs img').forEach(function(el){el.classList.remove('active');}); this.classList.add('active');">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div>
      <span class="p-cat"><?php echo esc_html( $cat_name ); ?></span>
      <h1 class="p-title"><?php the_title(); ?></h1>
      <div class="p-price-row">
        <span class="p-price">৳<?php echo esc_html( number_format_i18n( $price ) ); ?></span>
        <?php if ( $old_price ) :
          $off = $old_price > 0 ? round( ( ( $old_price - $price ) / $old_price ) * 100 ) : 0;
        ?>
          <span class="p-price-old">৳<?php echo esc_html( number_format_i18n( $old_price ) ); ?></span>
          <span class="p-save"><?php echo esc_html( $off ); ?>% ছাড়</span>
        <?php endif; ?>
      </div>
      <div class="p-desc bn"><?php the_content(); ?></div>

      <form action="<?php echo esc_url( $checkout_url ); ?>" method="get">
        <input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">

        <?php if ( $size_arr ) : ?>
        <div class="opt-block">
          <span class="opt-label">সাইজ বাছাই করুন</span>
          <div class="radio-cards">
            <?php foreach ( $size_arr as $i => $s ) : ?>
              <label class="radio-card <?php echo $i === 0 ? 'active' : ''; ?>" onclick="document.querySelectorAll('.opt-block:nth-of-type(1) .radio-card').forEach(function(el){el.classList.remove('active');}); this.classList.add('active');">
                <input type="radio" name="size" value="<?php echo esc_attr( $s ); ?>" <?php checked( $i === 0 ); ?> style="display:none;"><?php echo esc_html( $s ); ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $color_arr ) : ?>
        <div class="opt-block">
          <span class="opt-label">লাইট কালার</span>
          <div class="radio-cards">
            <?php foreach ( $color_arr as $i => $c ) : ?>
              <label class="radio-card <?php echo $i === 0 ? 'active' : ''; ?>" onclick="this.parentNode.querySelectorAll('.radio-card').forEach(function(el){el.classList.remove('active');}); this.classList.add('active');">
                <input type="radio" name="color" value="<?php echo esc_attr( $c ); ?>" <?php checked( $i === 0 ); ?> style="display:none;"><?php echo esc_html( $c ); ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="qty-cart-row">
          <div class="qty-box">
            <button type="button" onclick="var i=this.nextElementSibling; i.value=Math.max(1,parseInt(i.value||1)-1);">−</button>
            <input type="text" name="qty" value="1">
            <button type="button" onclick="var i=this.previousElementSibling; i.value=parseInt(i.value||1)+1;">+</button>
          </div>
          <button type="submit" class="btn btn-primary" style="flex:1;">এখনই অর্ডার করুন — ৳<?php echo esc_html( number_format_i18n( $price ) ); ?></button>
        </div>
      </form>

      <div class="info-strip">
        <div>🚚 <span>সারা বাংলাদেশে ডেলিভারি</span></div>
        <div>⏱ <span>৪৮–৭২ ঘণ্টা প্রোডাকশন</span></div>
        <div>💳 <span>COD / bKash / Nagad</span></div>
      </div>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php
/* ---------------- Related products: 3 per row, 2 rows ---------------- */
$related_args = array(
	'post_type'      => 'gf_product',
	'posts_per_page' => 6,
	'post__not_in'   => array( $product_id ),
	'orderby'        => 'rand',
);
if ( $cat_terms && ! is_wp_error( $cat_terms ) ) {
	$related_args['tax_query'] = array( array(
		'taxonomy' => 'gf_category',
		'field'    => 'term_id',
		'terms'    => wp_list_pluck( $cat_terms, 'term_id' ),
	) );
}
$related = new WP_Query( $related_args );

/* If not enough products in the same category, fall back to any other products */
if ( $related->post_count < 3 ) {
	$related = new WP_Query( array(
		'post_type'      => 'gf_product',
		'posts_per_page' => 6,
		'post__not_in'   => array( $product_id ),
		'orderby'        => 'rand',
	) );
}

if ( $related->have_posts() ) : ?>
<section class="section" style="padding-top:20px;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="tag">You may also like</span>
        <h2>আরও যা পছন্দ করতে পারেন</h2>
      </div>
    </div>
    <div class="prod-grid-3">
      <?php while ( $related->have_posts() ) : $related->the_post();
        $r_price     = get_post_meta( get_the_ID(), '_gf_price', true );
        $r_old_price = get_post_meta( get_the_ID(), '_gf_old_price', true );
        $r_badge     = get_post_meta( get_the_ID(), '_gf_badge', true );
        $r_cat_terms = get_the_terms( get_the_ID(), 'gf_category' );
        $r_cat_name  = $r_cat_terms && ! is_wp_error( $r_cat_terms ) ? $r_cat_terms[0]->name : '';
      ?>
      <div class="prod-card">
        <a href="<?php the_permalink(); ?>" class="prod-link">
          <div class="prod-thumb">
            <?php if ( $r_badge ) : ?><span class="badge"><?php echo esc_html( $r_badge ); ?></span><?php endif; ?>
            <span class="wish-btn" onclick="event.stopPropagation(); event.preventDefault();">♡</span>
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large' ); else : ?>
              <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg' ); ?>" alt="">
            <?php endif; ?>
          </div>
        </a>
        <div class="prod-info">
          <span class="cat"><?php echo esc_html( $r_cat_name ); ?></span>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="price-row">
            <span class="price">৳<?php echo esc_html( number_format_i18n( $r_price ) ); ?></span>
            <?php if ( $r_old_price ) : ?><span class="price-old">৳<?php echo esc_html( number_format_i18n( $r_old_price ) ); ?></span><?php endif; ?>
          </div>
          <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm btn-block">কাস্টমাইজ করুন</a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

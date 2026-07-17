<?php
/**
 * Homepage template
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

/* Bestseller products: marked featured, else fallback to latest 4 */
$bestsellers = new WP_Query( array(
	'post_type'      => 'gf_product',
	'posts_per_page' => 4,
	'meta_key'       => '_gf_bestseller',
	'meta_value'     => '1',
) );
if ( ! $bestsellers->have_posts() ) {
	$bestsellers = new WP_Query( array(
		'post_type'      => 'gf_product',
		'posts_per_page' => 4,
	) );
}

/* Categories with at least one product, show up to 4 */
$categories = get_terms( array(
	'taxonomy'   => 'gf_category',
	'hide_empty' => true,
	'number'     => 4,
) );

$hero_image = get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg';
$hero_query = new WP_Query( array( 'post_type' => 'gf_product', 'posts_per_page' => 1 ) );
if ( $hero_query->have_posts() ) {
	$hero_query->the_post();
	if ( has_post_thumbnail() ) {
		$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}
	wp_reset_postdata();
}
?>

<section class="hero">
  <div class="halo"></div>
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow">হাতে তৈরি, আলোয় ভরা উপহার</span>
      <h1>আপনার প্রিয় মুহূর্ত, <em>জ্বলে উঠুক</em> ভালোবাসার আলোয়</h1>
      <p class="lead bn">হার্ট-শেপ LED লাইট ফ্রেম, ক্রিস্টাল ল্যাম্প আর অ্যাক্রিলিক ওয়েডিং ফ্রেম — আপনার ছবি দিয়ে বানানো, যা রাতে ঘরের এক কোণে ভালোবাসার আলো ছড়ায়।</p>
      <div class="hero-cta">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn-primary">প্রোডাক্ট দেখুন</a>
        <a href="#how" class="btn btn-ghost">কিভাবে অর্ডার করবেন</a>
      </div>
      <div class="trust-row">
        <div class="trust-item"><span class="dot"></span> <b>৫০০০+</b> কাপল অর্ডার করেছেন</div>
        <div class="trust-item"><span class="dot"></span> <b>৪৮ ঘণ্টা</b> প্রোডাকশন টাইম</div>
        <div class="trust-item"><span class="dot"></span> <b>COD</b> সারা দেশে</div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-frame">
        <img src="<?php echo esc_url( $hero_image ); ?>" alt="Glow Frame product">
      </div>
      <div class="float-card f1">
        <div class="num">৳৬৯০</div>
        <div><small>শুরু হচ্ছে</small><small>সব সাইজ পাওয়া যায়</small></div>
      </div>
      <div class="float-card f2">
        <span style="font-size:20px">✨</span>
        <div><small>ফ্রি নাম প্রিন্ট</small><small>+ তারিখ কাস্টমাইজেশন</small></div>
      </div>
    </div>
  </div>
</section>

<?php if ( ! is_wp_error( $categories ) && $categories ) : ?>
<section class="section" id="categories">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="tag">Shop by Category</span>
        <h2>যে আলোয় সাজবে আপনার ঘর</h2>
      </div>
      <p class="bn">প্রতিটি প্রোডাক্ট আলাদাভাবে হাতে তৈরি — আপনার নিজের ছবি, নাম আর তারিখ দিয়ে।</p>
    </div>
    <div class="cat-grid">
      <?php foreach ( $categories as $cat ) :
        $cat_products = new WP_Query( array( 'post_type' => 'gf_product', 'posts_per_page' => 1, 'tax_query' => array( array( 'taxonomy' => 'gf_category', 'field' => 'term_id', 'terms' => $cat->term_id ) ) ) );
        $img = get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg';
        if ( $cat_products->have_posts() ) {
          $cat_products->the_post();
          if ( has_post_thumbnail() ) { $img = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ); }
          wp_reset_postdata();
        }
      ?>
      <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="cat-card glow-ring">
        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>">
        <div class="cat-label"><?php echo esc_html( $cat->name ); ?><small><?php echo intval( $cat->count ); ?> designs</small></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section" id="bestsellers" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="tag">Bestsellers</span>
        <h2>সবচেয়ে বেশি পছন্দের গিফট</h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn-ghost btn-sm">সব দেখুন →</a>
    </div>
    <div class="prod-grid">
      <?php if ( $bestsellers->have_posts() ) : while ( $bestsellers->have_posts() ) : $bestsellers->the_post();
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
      <?php endwhile; wp_reset_postdata(); else : ?>
        <p class="bn" style="color:var(--muted);">এখনো কোনো প্রোডাক্ট যোগ করা হয়নি। <a href="<?php echo esc_url( admin_url('post-new.php?post_type=gf_product') ); ?>" style="color:var(--glow-soft);">এখানে ক্লিক করে প্রথম প্রোডাক্ট যোগ করুন →</a></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section" id="how" style="background:var(--ink-2);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="tag">Process</span>
        <h2>মাত্র ৪ ধাপে অর্ডার করুন</h2>
      </div>
      <p class="bn">ছবি আপলোড থেকে শুরু করে ডেলিভারি পর্যন্ত পুরো প্রক্রিয়াটি সহজ ও দ্রুত।</p>
    </div>
    <div class="steps">
      <div class="step"><div class="idx">1</div><h4>প্রোডাক্ট বাছাই করুন</h4><p class="bn">পছন্দের ডিজাইন ও সাইজ সিলেক্ট করুন।</p></div>
      <div class="step"><div class="idx">2</div><h4>ছবি আপলোড করুন</h4><p class="bn">আপনার প্রিয় মুহূর্তের ছবি ও নাম দিন।</p></div>
      <div class="step"><div class="idx">3</div><h4>অর্ডার কনফার্ম করুন</h4><p class="bn">COD অথবা bKash/Nagad এ পেমেন্ট।</p></div>
      <div class="step"><div class="idx">4</div><h4>ঘরে বসে ডেলিভারি পান</h4><p class="bn">৪৮-৭২ ঘণ্টার মধ্যে হাতে পাবেন।</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="cta-band">
    <div class="halo"></div>
    <h2>আজই আপনার ভালোবাসার মুহূর্তটি আলোয় বাঁধুন</h2>
    <p class="bn">কাস্টম ছবি আপলোড করুন, আমরা বানিয়ে দিচ্ছি আপনার নিজের গ্লো ফ্রেম।</p>
    <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn-primary">এখনই কাস্টমাইজ করুন</a>
  </div>
</section>

<?php get_footer(); ?>

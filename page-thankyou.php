<?php
/**
 * Template Name: Thank You
 * Assign this template to a Page titled "Thank You" (slug: thank-you).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$order_id = isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
$total    = $order_id ? get_post_meta( $order_id, '_gf_total', true ) : '';
$name     = $order_id ? get_post_meta( $order_id, '_gf_name', true ) : '';
?>

<div class="thankyou-box">
  <div class="ic">🎉</div>
  <h1>ধন্যবাদ<?php echo $name ? ', ' . esc_html( $name ) : ''; ?>!</h1>
  <p class="bn">আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে। আমাদের টিম শীঘ্রই আপনার সাথে ফোনে যোগাযোগ করে অর্ডারটি নিশ্চিত করবে।</p>
  <?php if ( $order_id ) : ?>
    <div class="order-id-chip">Order #<?php echo esc_html( $order_id ); ?><?php echo $total ? ' — ৳' . esc_html( $total ) : ''; ?></div>
  <?php endif; ?>
  <p class="bn"><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn-primary">আরও প্রোডাক্ট দেখুন</a></p>
</div>

<?php get_footer(); ?>

<?php
/**
 * Template Name: Checkout
 * Assign this template to a Page titled "Checkout" (slug: checkout).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0;
$size       = isset( $_GET['size'] ) ? sanitize_text_field( wp_unslash( $_GET['size'] ) ) : '';
$color      = isset( $_GET['color'] ) ? sanitize_text_field( wp_unslash( $_GET['color'] ) ) : '';
$qty        = isset( $_GET['qty'] ) ? max( 1, intval( $_GET['qty'] ) ) : 1;
$error      = isset( $_GET['gf_error'] ) ? sanitize_text_field( wp_unslash( $_GET['gf_error'] ) ) : '';

$product = $product_id ? get_post( $product_id ) : null;

if ( ! $product ) {
	?>
	<div class="wrap" style="padding:80px 0; text-align:center;">
		<p class="bn" style="color:var(--muted); font-size:16px;">কোনো প্রোডাক্ট বাছাই করা হয়নি। অনুগ্রহ করে <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" style="color:var(--glow-soft);">শপ পেজ</a> থেকে একটি প্রোডাক্ট বাছাই করুন।</p>
	</div>
	<?php
	get_footer();
	return;
}

$price     = floatval( get_post_meta( $product_id, '_gf_price', true ) );
$subtotal  = $price * $qty;
$thumb_url = has_post_thumbnail( $product_id ) ? get_the_post_thumbnail_url( $product_id, 'thumbnail' ) : get_template_directory_uri() . '/assets/img/heart-lamp-1.jpg';
?>

<section class="checkout-section">
  <div class="wrap">

    <?php if ( $error ) : ?>
      <div class="notice-box notice-error bn">দয়া করে এই তথ্যগুলো ঠিকভাবে দিন: <?php echo esc_html( $error ); ?></div>
    <?php endif; ?>

    <div class="progress">
      <div class="p-step active"><span class="p-dot"></span> প্রোডাক্ট বাছাই</div>
      <div class="p-line"></div>
      <div class="p-step active"><span class="p-dot"></span> ডেলিভারি ও পেমেন্ট</div>
      <div class="p-line"></div>
      <div class="p-step"><span class="p-dot"></span> কনফার্মেশন</div>
    </div>

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
      <?php wp_nonce_field( 'gf_submit_order', 'gf_checkout_nonce' ); ?>
      <input type="hidden" name="action" value="gf_submit_order">
      <input type="hidden" name="gf_product_id" value="<?php echo esc_attr( $product_id ); ?>">
      <input type="hidden" name="gf_size" value="<?php echo esc_attr( $size ); ?>">
      <input type="hidden" name="gf_color" value="<?php echo esc_attr( $color ); ?>">
      <input type="hidden" name="gf_qty" value="<?php echo esc_attr( $qty ); ?>">

      <div class="checkout-grid">
        <div>

          <div class="co-card">
            <h3><span class="n">1</span> ডেলিভারি ঠিকানা</h3>
            <div class="row2">
              <div class="field">
                <label>পূর্ণ নাম <span class="req">*</span></label>
                <input type="text" name="gf_name" required placeholder="যেমন: সামিরা জান্নাত">
              </div>
              <div class="field">
                <label>মোবাইল নম্বর <span class="req">*</span></label>
                <input type="tel" name="gf_phone" required placeholder="01XXX-XXXXXX">
              </div>
            </div>
            <div class="field">
              <label>সম্পূর্ণ ঠিকানা <span class="req">*</span></label>
              <textarea rows="3" name="gf_address" required placeholder="বাসা/হোল্ডিং নং, রোড, এলাকা"></textarea>
            </div>
            <div class="row2">
              <div class="field">
                <label>জেলা</label>
                <select name="gf_district">
                  <option>ঢাকা</option>
                  <option>চট্টগ্রাম</option>
                  <option>সিলেট — মৌলভীবাজার</option>
                  <option>রাজশাহী</option>
                  <option>খুলনা</option>
                  <option>অন্যান্য জেলা</option>
                </select>
              </div>
              <div class="field">
                <label>এলাকা টাইপ</label>
                <select name="gf_area_type" id="gfAreaType" onchange="gfUpdateDelivery()">
                  <option value="dhaka">ঢাকা সিটির ভেতরে — ৳৭০</option>
                  <option value="outside" selected>ঢাকার বাইরে — ৳১৩০</option>
                </select>
              </div>
            </div>
            <div class="delivery-note">📦 <span>নির্বাচিত এলাকার উপর ভিত্তি করে ডেলিভারি চার্জ স্বয়ংক্রিয়ভাবে হিসাব হবে।</span></div>
          </div>

          <div class="co-card">
            <h3><span class="n">2</span> আপনার ছবি আপলোড করুন</h3>
            <div class="field">
              <label>প্রোডাক্টে বসানোর ছবি <span class="req">*</span></label>
              <div class="upload-box" onclick="document.getElementById('gfPhotoInput').click();">
                <div class="ic">📷</div>
                <b>ছবি এখানে ক্লিক করে বাছাই করুন</b>
                <p id="gfFileName">JPG, PNG — সর্বোচ্চ 10MB</p>
              </div>
              <input type="file" id="gfPhotoInput" name="gf_photo" accept="image/*" style="display:none" onchange="document.getElementById('gfFileName').innerText = this.files[0] ? this.files[0].name : 'JPG, PNG — সর্বোচ্চ 10MB';">
            </div>
          </div>

          <div class="co-card">
            <h3><span class="n">3</span> পেমেন্ট পদ্ধতি</h3>

            <div class="pay-option active" onclick="gfSelectPay(this,'cod')">
              <label class="pay-head"><input type="radio" name="gf_payment_method" value="cod" checked> Cash on Delivery (COD) — পণ্য হাতে পেয়ে টাকা দিন</label>
              <div class="pay-body">
                <p class="pay-note bn">ডেলিভারিম্যান পণ্য পৌঁছে দেওয়ার সময় আপনি ক্যাশ পরিশোধ করবেন। <b>অগ্রিম কোনো টাকা লাগবে না।</b></p>
              </div>
            </div>

            <div class="pay-option" onclick="gfSelectPay(this,'bkash')">
              <label class="pay-head"><input type="radio" name="gf_payment_method" value="bkash"> bKash — সেন্ড মানি করে অর্ডার কনফার্ম করুন</label>
              <div class="pay-body">
                <div class="pay-note bn">bKash Personal নম্বরে <b>Send Money</b> করুন: <b>01XXX-XXXXXX</b></div>
                <div class="field"><label>bKash নম্বর যেখান থেকে টাকা পাঠিয়েছেন</label><input type="tel" name="gf_payment_number" placeholder="01XXX-XXXXXX"></div>
                <div class="field"><label>ট্রানজেকশন আইডি (TrxID)</label><input type="text" name="gf_trxid" placeholder="8N7A6B5C4D"></div>
              </div>
            </div>

            <div class="pay-option" onclick="gfSelectPay(this,'nagad')">
              <label class="pay-head"><input type="radio" name="gf_payment_method" value="nagad"> Nagad — সেন্ড মানি করে অর্ডার কনফার্ম করুন</label>
              <div class="pay-body">
                <div class="pay-note bn">Nagad নম্বরে <b>Send Money</b> করুন: <b>01XXX-XXXXXX</b></div>
                <div class="field"><label>Nagad নম্বর যেখান থেকে টাকা পাঠিয়েছেন</label><input type="tel" name="gf_payment_number" placeholder="01XXX-XXXXXX"></div>
                <div class="field"><label>ট্রানজেকশন আইডি (TrxID)</label><input type="text" name="gf_trxid" placeholder="8N7A6B5C4D"></div>
              </div>
            </div>
          </div>

          <div class="co-card">
            <h3><span class="n">4</span> অর্ডার নোট (ঐচ্ছিক)</h3>
            <div class="field">
              <textarea rows="2" name="gf_note" placeholder="বিশেষ কোনো নির্দেশনা থাকলে লিখুন..."></textarea>
            </div>
          </div>

        </div>

        <div class="summary-box">
          <h3 style="font-family:var(--display); font-size:19px; font-weight:600; margin-bottom:20px;">অর্ডার সামারি</h3>

          <div class="summary-item">
            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="">
            <div class="info">
              <b><?php echo esc_html( get_the_title( $product_id ) ); ?></b>
              <span><?php echo esc_html( trim( $size . ' · ' . $color, ' ·' ) ); ?></span>
              <span>Qty: <?php echo esc_html( $qty ); ?></span>
            </div>
            <div style="font-weight:700; color:var(--glow-soft);">৳<?php echo esc_html( number_format_i18n( $subtotal ) ); ?></div>
          </div>

          <div style="margin-top:20px;">
            <div class="summary-line"><span>Subtotal</span><span>৳<?php echo esc_html( number_format_i18n( $subtotal ) ); ?></span></div>
            <div class="summary-line" id="gfDeliveryLine"><span>Delivery Charge</span><span>৳১৩০</span></div>
            <div class="summary-line total"><span>মোট টাকা</span><b id="gfGrandTotal">৳<?php echo esc_html( number_format_i18n( $subtotal + 130 ) ); ?></b></div>
          </div>

          <button type="submit" class="btn btn-primary btn-block" style="margin-top:22px;">অর্ডার কনফার্ম করুন</button>
          <p style="font-size:12px; color:var(--muted); text-align:center; margin-top:12px;">অর্ডার করার মাধ্যমে আপনি আমাদের শর্তাবলীতে সম্মত হচ্ছেন।</p>
        </div>
      </div>
    </form>
  </div>
</section>

<script>
  var GF_SUBTOTAL = <?php echo (float) $subtotal; ?>;
  function gfSelectPay(el, val){
    document.querySelectorAll('.pay-option').forEach(function(o){ o.classList.remove('active'); });
    el.classList.add('active');
    el.querySelector('input[type=radio]').checked = true;
  }
  function gfUpdateDelivery(){
    var val = document.getElementById('gfAreaType').value;
    var charge = val === 'dhaka' ? 70 : 130;
    document.getElementById('gfDeliveryLine').innerHTML = '<span>Delivery Charge</span><span>৳' + charge + '</span>';
    document.getElementById('gfGrandTotal').innerText = '৳' + (GF_SUBTOTAL + charge);
  }
</script>

<?php get_footer(); ?>

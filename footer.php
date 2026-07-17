<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<footer id="contact">
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-about">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">Glow<span>Frame</span></a>
        <p class="bn">বাংলাদেশের বিশ্বস্ত পার্সোনালাইজড LED ফটো গিফট শপ। ভালোবাসার মুহূর্তকে আমরা বানাই চিরস্থায়ী আলো।</p>
        <div class="social-row">
          <a href="#" class="icon-btn">f</a>
          <a href="#" class="icon-btn">@</a>
          <a href="#" class="icon-btn">▶</a>
        </div>
      </div>
      <div>
        <h5>SHOP</h5>
        <ul>
          <?php
          $cats = get_terms( array( 'taxonomy' => 'gf_category', 'hide_empty' => false, 'number' => 5 ) );
          if ( ! is_wp_error( $cats ) && $cats ) {
            foreach ( $cats as $cat ) {
              echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
            }
          } else {
            echo '<li><a href="' . esc_url( home_url( '/shop/' ) ) . '">সব প্রোডাক্ট</a></li>';
          }
          ?>
        </ul>
      </div>
      <div>
        <h5>HELP</h5>
        <ul>
          <li><a href="#">ডেলিভারি তথ্য</a></li>
          <li><a href="#">রিটার্ন পলিসি</a></li>
          <li><a href="<?php echo esc_url( home_url('/#contact') ); ?>">যোগাযোগ</a></li>
        </ul>
      </div>
      <div>
        <h5>যোগাযোগ করুন</h5>
        <ul>
          <?php dynamic_sidebar( 'footer-1' ); ?>
          <li>📞 <?php bloginfo( 'admin_email' ); ?></li>
          <li>💬 WhatsApp Order</li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
      <span>Made with ♥ — <?php bloginfo( 'url' ); ?></span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

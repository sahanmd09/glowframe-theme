<?php
/**
 * Glow Frame theme functions
 * ---------------------------------------------------------
 * This theme does NOT require WooCommerce. It ships its own
 * lightweight product post type + order form so a shop owner
 * can upload it and start taking orders immediately.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ---------------------------------------------------------
   1. Theme support & assets
--------------------------------------------------------- */
function glowframe_default_menu() {
	echo '<nav class="nav-links">';
	echo '<a href="' . esc_url( home_url( '/shop/' ) ) . '">Shop</a>';
	echo '<a href="' . esc_url( home_url( '/#how' ) ) . '">How it Works</a>';
	echo '<a href="' . esc_url( home_url( '/#contact' ) ) . '">Contact</a>';
	echo '</nav>';
}

function glowframe_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'glowframe' ),
	) );
}
add_action( 'after_setup_theme', 'glowframe_setup' );

function glowframe_assets() {
	wp_enqueue_style( 'gf-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'glowframe-style', get_stylesheet_uri(), array(), '1.0' );
	wp_enqueue_script( 'glowframe-script', get_template_directory_uri() . '/assets/js/script.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'glowframe_assets' );

/* ---------------------------------------------------------
   2. Product post type + category taxonomy
--------------------------------------------------------- */
function glowframe_register_product_cpt() {
	register_post_type( 'gf_product', array(
		'labels' => array(
			'name'               => __( 'Products', 'glowframe' ),
			'singular_name'      => __( 'Product', 'glowframe' ),
			'add_new_item'       => __( 'Add New Product', 'glowframe' ),
			'edit_item'          => __( 'Edit Product', 'glowframe' ),
			'all_items'          => __( 'All Products', 'glowframe' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'shop' ),
		'menu_icon'    => 'dashicons-lightbulb',
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'gf_category', 'gf_product', array(
		'labels' => array(
			'name'          => __( 'Categories', 'glowframe' ),
			'singular_name' => __( 'Category', 'glowframe' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'category' ),
	) );
}
add_action( 'init', 'glowframe_register_product_cpt' );

/* Product meta box: price, old price, badge, sizes, light colors */
function glowframe_product_metabox() {
	add_meta_box( 'gf_product_details', __( 'Product Details', 'glowframe' ), 'glowframe_product_metabox_html', 'gf_product', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'glowframe_product_metabox' );

function glowframe_product_metabox_html( $post ) {
	wp_nonce_field( 'gf_save_product', 'gf_product_nonce' );
	$price      = get_post_meta( $post->ID, '_gf_price', true );
	$old_price  = get_post_meta( $post->ID, '_gf_old_price', true );
	$badge      = get_post_meta( $post->ID, '_gf_badge', true );
	$sizes      = get_post_meta( $post->ID, '_gf_sizes', true );
	$colors     = get_post_meta( $post->ID, '_gf_colors', true );
	$bestseller = get_post_meta( $post->ID, '_gf_bestseller', true );
	?>
	<p>
		<label><strong><?php _e( 'Price (৳)', 'glowframe' ); ?></strong></label><br>
		<input type="number" step="1" name="gf_price" value="<?php echo esc_attr( $price ); ?>" style="width:100%;max-width:260px;">
	</p>
	<p>
		<label><strong><?php _e( 'Compare-at / old price (৳) — optional', 'glowframe' ); ?></strong></label><br>
		<input type="number" step="1" name="gf_old_price" value="<?php echo esc_attr( $old_price ); ?>" style="width:100%;max-width:260px;">
	</p>
	<p>
		<label><strong><?php _e( 'Badge text — optional (e.g. বেস্ট সেলার, নতুন)', 'glowframe' ); ?></strong></label><br>
		<input type="text" name="gf_badge" value="<?php echo esc_attr( $badge ); ?>" style="width:100%;max-width:260px;">
	</p>
	<p>
		<label><strong><?php _e( 'Sizes — comma separated (e.g. Small,Medium,Large)', 'glowframe' ); ?></strong></label><br>
		<input type="text" name="gf_sizes" value="<?php echo esc_attr( $sizes ); ?>" style="width:100%;max-width:420px;" placeholder="Small,Medium,Large">
	</p>
	<p>
		<label><strong><?php _e( 'Light colors — comma separated (e.g. Warm White,RGB Multicolor)', 'glowframe' ); ?></strong></label><br>
		<input type="text" name="gf_colors" value="<?php echo esc_attr( $colors ); ?>" style="width:100%;max-width:420px;" placeholder="Warm White,RGB Multicolor">
	</p>
	<p>
		<label><input type="checkbox" name="gf_bestseller" value="1" <?php checked( $bestseller, '1' ); ?>> <strong><?php _e( 'Show on homepage bestsellers', 'glowframe' ); ?></strong></label>
	</p>
	<p style="color:#777;"><?php _e( 'Tip: add extra gallery photos by opening this product in the Media Library uploader below the editor, or attach images to this post — they will show automatically as thumbnails on the product page.', 'glowframe' ); ?></p>
	<?php
}

function glowframe_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['gf_product_nonce'] ) || ! wp_verify_nonce( $_POST['gf_product_nonce'], 'gf_save_product' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$fields = array( 'gf_price' => '_gf_price', 'gf_old_price' => '_gf_old_price', 'gf_badge' => '_gf_badge', 'gf_sizes' => '_gf_sizes', 'gf_colors' => '_gf_colors' );
	foreach ( $fields as $key => $meta_key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	update_post_meta( $post_id, '_gf_bestseller', isset( $_POST['gf_bestseller'] ) ? '1' : '' );
}
add_action( 'save_post_gf_product', 'glowframe_save_product_meta' );

/* ---------------------------------------------------------
   3. Orders post type (admin only — this is where orders land)
--------------------------------------------------------- */
function glowframe_register_order_cpt() {
	register_post_type( 'gf_order', array(
		'labels' => array(
			'name'          => __( 'Orders', 'glowframe' ),
			'singular_name' => __( 'Order', 'glowframe' ),
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-cart',
		'supports'            => array( 'title' ),
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
	) );
}
add_action( 'init', 'glowframe_register_order_cpt' );

/* Custom admin columns for the Orders list */
function glowframe_order_columns( $columns ) {
	$columns = array(
		'cb'         => $columns['cb'],
		'title'      => __( 'Order', 'glowframe' ),
		'gf_product' => __( 'Product', 'glowframe' ),
		'gf_customer'=> __( 'Customer', 'glowframe' ),
		'gf_phone'   => __( 'Phone', 'glowframe' ),
		'gf_address' => __( 'Address', 'glowframe' ),
		'gf_payment' => __( 'Payment', 'glowframe' ),
		'gf_photo'   => __( 'Photo', 'glowframe' ),
		'gf_total'   => __( 'Total', 'glowframe' ),
		'date'       => __( 'Date', 'glowframe' ),
	);
	return $columns;
}
add_filter( 'manage_gf_order_posts_columns', 'glowframe_order_columns' );

function glowframe_order_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'gf_product':
			echo esc_html( get_post_meta( $post_id, '_gf_product_name', true ) . ' (x' . get_post_meta( $post_id, '_gf_qty', true ) . ')' );
			break;
		case 'gf_customer':
			echo esc_html( get_post_meta( $post_id, '_gf_name', true ) );
			break;
		case 'gf_phone':
			echo esc_html( get_post_meta( $post_id, '_gf_phone', true ) );
			break;
		case 'gf_address':
			echo esc_html( get_post_meta( $post_id, '_gf_address', true ) . ', ' . get_post_meta( $post_id, '_gf_district', true ) );
			break;
		case 'gf_payment':
			$method = get_post_meta( $post_id, '_gf_payment_method', true );
			echo esc_html( strtoupper( $method ) );
			if ( in_array( $method, array( 'bkash', 'nagad' ), true ) ) {
				echo '<br><small>' . esc_html( get_post_meta( $post_id, '_gf_payment_number', true ) ) . ' / TrxID: ' . esc_html( get_post_meta( $post_id, '_gf_trxid', true ) ) . '</small>';
			}
			break;
		case 'gf_photo':
			$attachment_id = get_post_meta( $post_id, '_gf_photo_id', true );
			if ( $attachment_id ) {
				echo wp_get_attachment_image( $attachment_id, array( 60, 60 ) );
			} else {
				echo '—';
			}
			break;
		case 'gf_total':
			echo '৳' . esc_html( get_post_meta( $post_id, '_gf_total', true ) );
			break;
	}
}
add_action( 'manage_gf_order_posts_custom_column', 'glowframe_order_column_content', 10, 2 );

/* ---------------------------------------------------------
   4. Order form submission handler (no WooCommerce needed)
--------------------------------------------------------- */
function glowframe_handle_order_submit() {
	if ( ! isset( $_POST['gf_checkout_nonce'] ) || ! wp_verify_nonce( $_POST['gf_checkout_nonce'], 'gf_submit_order' ) ) {
		wp_die( __( 'Security check failed. Please go back and try again.', 'glowframe' ) );
	}

	$name         = isset( $_POST['gf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_name'] ) ) : '';
	$phone        = isset( $_POST['gf_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_phone'] ) ) : '';
	$address      = isset( $_POST['gf_address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['gf_address'] ) ) : '';
	$district     = isset( $_POST['gf_district'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_district'] ) ) : '';
	$area_type    = isset( $_POST['gf_area_type'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_area_type'] ) ) : 'outside';
	$payment      = isset( $_POST['gf_payment_method'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_payment_method'] ) ) : 'cod';
	$pay_number   = isset( $_POST['gf_payment_number'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_payment_number'] ) ) : '';
	$trxid        = isset( $_POST['gf_trxid'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_trxid'] ) ) : '';
	$note         = isset( $_POST['gf_note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['gf_note'] ) ) : '';
	$product_id   = isset( $_POST['gf_product_id'] ) ? intval( $_POST['gf_product_id'] ) : 0;
	$size         = isset( $_POST['gf_size'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_size'] ) ) : '';
	$color        = isset( $_POST['gf_color'] ) ? sanitize_text_field( wp_unslash( $_POST['gf_color'] ) ) : '';
	$qty          = isset( $_POST['gf_qty'] ) ? max( 1, intval( $_POST['gf_qty'] ) ) : 1;

	$errors = array();
	if ( empty( $name ) )    $errors[] = 'নাম দিন';
	if ( empty( $phone ) )   $errors[] = 'মোবাইল নম্বর দিন';
	if ( empty( $address ) ) $errors[] = 'ঠিকানা দিন';
	if ( ! $product_id )     $errors[] = 'প্রোডাক্ট পাওয়া যায়নি';

	if ( ! empty( $errors ) ) {
		$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		$redirect = add_query_arg( 'gf_error', urlencode( implode( ', ', $errors ) ), $redirect );
		wp_safe_redirect( $redirect );
		exit;
	}

	$product      = get_post( $product_id );
	$product_name = $product ? $product->post_title : 'Unknown';
	$price        = $product ? floatval( get_post_meta( $product_id, '_gf_price', true ) ) : 0;
	$delivery     = ( $area_type === 'dhaka' ) ? 70 : 130;
	$subtotal     = $price * $qty;
	$total        = $subtotal + $delivery;

	/* Handle optional photo upload */
	$attachment_id = 0;
	if ( ! empty( $_FILES['gf_photo']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		$attachment_id = media_handle_upload( 'gf_photo', 0 );
		if ( is_wp_error( $attachment_id ) ) {
			$attachment_id = 0;
		}
	}

	/* Create the order */
	$order_id = wp_insert_post( array(
		'post_type'   => 'gf_order',
		'post_title'  => 'Order — ' . $name . ' — ' . current_time( 'Y-m-d H:i' ),
		'post_status' => 'publish',
	) );

	if ( $order_id && ! is_wp_error( $order_id ) ) {
		update_post_meta( $order_id, '_gf_name', $name );
		update_post_meta( $order_id, '_gf_phone', $phone );
		update_post_meta( $order_id, '_gf_address', $address );
		update_post_meta( $order_id, '_gf_district', $district );
		update_post_meta( $order_id, '_gf_area_type', $area_type );
		update_post_meta( $order_id, '_gf_payment_method', $payment );
		update_post_meta( $order_id, '_gf_payment_number', $pay_number );
		update_post_meta( $order_id, '_gf_trxid', $trxid );
		update_post_meta( $order_id, '_gf_note', $note );
		update_post_meta( $order_id, '_gf_product_id', $product_id );
		update_post_meta( $order_id, '_gf_product_name', $product_name );
		update_post_meta( $order_id, '_gf_size', $size );
		update_post_meta( $order_id, '_gf_color', $color );
		update_post_meta( $order_id, '_gf_qty', $qty );
		update_post_meta( $order_id, '_gf_price', $price );
		update_post_meta( $order_id, '_gf_delivery_charge', $delivery );
		update_post_meta( $order_id, '_gf_total', $total );
		if ( $attachment_id ) {
			update_post_meta( $order_id, '_gf_photo_id', $attachment_id );
		}

		/* Email notification to shop owner */
		$admin_email = get_option( 'admin_email' );
		$subject     = 'নতুন অর্ডার — ' . $name . ' (৳' . $total . ')';
		$body        = "একটি নতুন অর্ডার এসেছে:\n\n"
			. "প্রোডাক্ট: {$product_name} (Size: {$size}, Color: {$color}, Qty: {$qty})\n"
			. "নাম: {$name}\nফোন: {$phone}\nঠিকানা: {$address}, {$district}\n"
			. "পেমেন্ট মেথড: {$payment}\n"
			. "মোট: ৳{$total}\n\n"
			. "অ্যাডমিন প্যানেলে দেখুন: " . admin_url( 'edit.php?post_type=gf_order' );
		wp_mail( $admin_email, $subject, $body );
	}

	/* Redirect to thank you page */
	$thankyou_page = get_page_by_path( 'thank-you' );
	$redirect_url  = $thankyou_page ? get_permalink( $thankyou_page->ID ) : home_url( '/' );
	$redirect_url  = add_query_arg( 'order_id', $order_id, $redirect_url );
	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'admin_post_gf_submit_order', 'glowframe_handle_order_submit' );
add_action( 'admin_post_nopriv_gf_submit_order', 'glowframe_handle_order_submit' );

/* ---------------------------------------------------------
   5. Helper: product gallery images (featured + attached media)
--------------------------------------------------------- */
function glowframe_get_product_gallery( $product_id ) {
	$images = array();
	if ( has_post_thumbnail( $product_id ) ) {
		$images[] = get_post_thumbnail_id( $product_id );
	}
	$attached = get_children( array(
		'post_parent'    => $product_id,
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );
	foreach ( $attached as $attachment ) {
		if ( ! in_array( $attachment->ID, $images, true ) ) {
			$images[] = $attachment->ID;
		}
	}
	return $images;
}

/* ---------------------------------------------------------
   6. Widget area (optional, used by footer.php fallback)
--------------------------------------------------------- */
function glowframe_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'glowframe' ),
		'id'            => 'footer-1',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', 'glowframe_widgets_init' );
/* ===============================
   Glow Frame AJAX Search
================================ */

add_action('wp_ajax_gf_live_search', 'gf_live_search');
add_action('wp_ajax_nopriv_gf_live_search', 'gf_live_search');

function gf_live_search(){

    $keyword = sanitize_text_field($_POST['keyword']);

    $query = new WP_Query(array(
        'post_type' => 'gf_product',
        'posts_per_page' => 8,
        's' => $keyword
    ));

    if($query->have_posts()){

        while($query->have_posts()){

            $query->the_post();

            ?>

            <a class="live-search-item" href="<?php the_permalink();?>">

                <div class="live-thumb">

                <?php the_post_thumbnail('thumbnail');?>

                </div>

                <div class="live-content">

                    <strong><?php the_title();?></strong>

                </div>

            </a>

            <?php

        }

    }else{

        echo '<div class="no-result">No Product Found</div>';

    }

    wp_die();

}

<header class="site-header">

    <!-- Top Announcement -->
    <div class="topbar">
        <div class="container topbar-inner">

            <div class="top-left">
                🚚 সারা বাংলাদেশে ডেলিভারি
            </div>

            <div class="top-center">
                ⭐ ৫,০০০+ Happy Customers
            </div>

            <div class="top-right">
                📞 01871698464
            </div>

        </div>
    </div>

    <!-- Main Header -->
    <div class="header-main">

        <div class="container header-row">

            <!-- Logo -->
            <div class="header-logo">

                <a href="<?php echo esc_url(home_url('/')); ?>">

                    <?php
                    if(has_custom_logo()){
                        the_custom_logo();
                    }else{
                        ?>
                        <span class="logo-text">
                            Glow<span>Frame</span>
                        </span>

                        <small class="logo-tagline">
                            Personalized Gifts
                        </small>

                        <?php
                    }
                    ?>

                </a>

            </div>

            <!-- Menu -->

            <nav class="main-nav">

                <?php

                wp_nav_menu(array(

                    'theme_location'=>'primary',

                    'fallback_cb'=>'glowframe_default_menu',

                    'menu_class'=>'nav-links'

                ));

                ?>

            </nav>

            <!-- Right Icons -->

            <div class="header-actions">

                <a href="#" class="header-icon search-btn">
                    🔍
                </a>

                <a href="#" class="header-icon favorite-btn">
                    ❤️
                    <span class="count">0</span>
                </a>

                <a href="<?php echo esc_url(home_url('/checkout')); ?>" class="header-icon">
                    🛒
                    <span class="count">0</span>
                </a>

                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn btn-primary">
                    Shop Now
                </a>

            </div>

            <!-- Mobile Button -->

            <button class="mobile-toggle">

                ☰

            </button>

        </div>

    </div>

</header>

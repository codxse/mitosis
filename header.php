<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <script src="https://analytics.ahrefs.com/analytics.js" data-key="FvepyLJkfiyPN6W5nbDQHQ" async></script>
</head>

<body <?php body_class(); ?>>
    
<?php
$enable_h1 = is_home() || is_front_page();
?>
    
    
<header id="masthead" class="site-header">
    <div class="header-container">
        <div class="site-branding">
            <?php
            // Display custom logo if available, otherwise show site title.
            if (function_exists('the_custom_logo') && has_custom_logo()) {
                printf(
                    '<%1$s class="site-title">%2$s</%1$s>',
                    $enable_h1 ? 'h1' : 'div',
                    get_custom_logo()
                );
            } else {
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
            }

            // Display subtitle if enabled.
            if (get_theme_mod('mitosis_show_subtitle', true)) {
                ?>
                <p class="site-description"><?php bloginfo('description'); ?></p>
                <?php
            }
            ?>
        </div>

        <div class="header-navigation">
            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'primary-menu'
                ));
                ?>
            </nav>
            
            <span class="nav-separator"></span>

            <!-- Search Toggle Button -->
            <button id="search-toggle" class="search-toggle" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e('Toggle Search', 'mitosis'); ?></span>
                <i class="icon-magnifying-glass"></i>
            </button>
            
            <!-- Responsive Menu Toggle Button -->
            <button id="menu-toggle" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'mitosis'); ?></span>
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    
    <nav id="site-navigation-mobile" class="main-navigation-mobile">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu-mobile',
            'menu_class'     => 'primaru-menu-mobile'
        ));
        ?>
    </nav>
</header>

<!-- Search Overlay -->
<div id="search-overlay" class="search-overlay">
    <button class="search-close" aria-label="<?php esc_attr_e('Close search', 'mitosis'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
    <div class="search-overlay-content">
        <?php
        // Using WordPress's native search form
        get_search_form();
        ?>
        <p class="search-instruction">Type above and press <em>Enter</em> to search. Press <em>Esc</em> to cancel.</p>
    </div>
</div>
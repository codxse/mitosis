<footer id="colophon" class="site-footer">
    <div class="site-info">
        <?php
        // Retrieve the custom footer copyright from the Customizer.
        $footer_copyright = get_theme_mod(
            'mitosis_footer_copyright',
            __( '© ' . date('Y') . ' Your Site Name. All Rights Reserved.', 'mitosis' )
        );
        echo wp_kses_post( $footer_copyright );
        ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>

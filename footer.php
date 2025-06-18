<?php
/**
 * The template for displaying the footer
 *
 * @package Hariharan
 */

?>
        </div><!-- .container -->
    </div><!-- #content -->

    <?php
    // Get footer layout from theme options
    $footer_layout = get_option('hariharan_footer_layout', 'default');
    
    // Check if the footer template exists
    $footer_template = get_template_directory() . '/template-parts/footer/footer-' . $footer_layout . '.php';
    
    if (file_exists($footer_template)) {
        get_template_part('template-parts/footer/footer', $footer_layout);
    } else {
        get_template_part('template-parts/footer/footer', 'default');
    }
    ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

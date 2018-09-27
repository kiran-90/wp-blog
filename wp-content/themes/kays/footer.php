<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kays
 */

?>

	</div><!-- #content -->
        
        <?php get_sidebar( 'footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
            <div class="social-menu">
                <?php wp_nav_menu( array( 'theme_location' => 'social' ) ); ?>
            </div>
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'kays' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'kays' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'kays' ), 'kays', '<a href="https://github.com/kiran-90/wp-blog" rel="designer">Kirandeep Chahal</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

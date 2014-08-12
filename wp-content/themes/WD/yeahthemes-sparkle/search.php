<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package yeahthemes
 */

get_header(); ?>

	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<div id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'yeahthemes' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			
			<?php yt_before_loop(); ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php yt_loop_start(); ?>

				<?php get_template_part( 'content', 'search' ); ?>
				
				<?php yt_loop_end(); ?>

			<?php endwhile; ?>

			<?php yt_after_loop(); ?>
			
			<?php yt_direction_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		</div><!-- #content -->
	
		<?php yt_primary_end(); ?>
		
	</div><!-- #primary -->
	
	<?php yt_after_primary(); ?>
	
<?php get_footer(); ?>

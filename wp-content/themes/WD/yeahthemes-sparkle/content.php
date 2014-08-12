<?php
/**
 * @package yeahthemes
 */
 
	$format = get_post_format();
	
	if( false === $format )
		$format = 'standard';
	
	$formats_meta = yt_get_post_formats_meta( get_the_ID());
	

	$output_excerpt = '';
	if( 'automatic' == yt_get_options( 'excerpt_output' ) ){

		$excerpt = get_the_content('');
		
		$excerpt_length = yt_get_options('custom_excerpt_length');
		$excerpt_length = $excerpt_length ? $excerpt_length : 55;

		$trimmed_excerpt = wp_trim_words( $excerpt, $excerpt_length , '...');
		$readmore = 'show' == yt_get_options( 'blog_readmore_button' ) ? '<p><a class="more-tag btn btn-default btn-lg margin-top-15" href="'. get_permalink( get_the_ID() ) . '"> ' . __('Read More...','yeahthemes') . '</a></p>' : '';

		$output_excerpt = sprintf('%s%s', apply_filters( 'the_content', $trimmed_excerpt ), $readmore );
	}

?>
<?php 
/**
 * Standard format
 ******************************************************************
 */
if( 'standard' === $format ){ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta margin-bottom-30 hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail margin-bottom-30">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>

		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
			
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->

<?php
}
/**
 * Video format
 ******************************************************************
 */
elseif( 'video' === $format ){?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta margin-bottom-30 hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail margin-bottom-30">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>
		
		<?php if ( !has_post_thumbnail() && yt_get_the_post_format_video() && !post_password_required() ) : ?>
		<div class="entry-format-media margin-bottom-30">
			<?php echo yt_get_the_post_format_video(); ?>
		</div>
		<?php endif; ?>
		
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->
<?php	
}
/**
 * Audio format
 ******************************************************************
 */
elseif( 'audio' === $format ){?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta margin-bottom-30 hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && yt_get_the_post_format_audio() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail<?php echo !yt_get_the_post_format_audio() ? ' margin-bottom-30' : ''; ?>">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>
		<?php if ( !post_password_required() && yt_get_the_post_format_audio() ) : ?>
		<div class="entry-format-media <?php echo has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ? 'with-cover ' : ''; ?>margin-bottom-30">
			<?php echo yt_get_the_post_format_audio(); ?>
		</div>
		<?php endif; ?>
		
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->
<?php	
}
/**
 * Gallery format
 ******************************************************************
 */
elseif( 'gallery' === $format ){?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta margin-bottom-30 hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && yt_get_the_post_format_gallery() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail margin-bottom-30">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>
		<?php if ( !has_post_thumbnail() && yt_get_the_post_format_gallery() && !post_password_required() ) : ?>
		<div class="entry-format-media margin-bottom-30">
			<?php echo yt_get_the_post_format_gallery(); ?>
		</div>
		<?php endif; ?>
		
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->
<?php	
}
/**
 * Image format
 ******************************************************************
 */
elseif( 'image' === $format ){ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header hidden">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( ! post_password_required() && yt_get_the_post_format_image() ) : ?>
		<div class="entry-media margin-bottom-30">
		<?php echo yt_get_the_post_format_image(); ?>
		</div>
		<?php endif; ?>
		
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->

<?php
}/**
 * Quote format
 ******************************************************************
 */
elseif( 'quote' === $format ){
	/*if( has_post_thumbnail() ){
		
		$thumb_id = get_post_thumbnail_id();
		$thumb = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
		$quote_bg = sprintf( ' style="background:url(%s);background-size:cover;"', esc_url( $thumb[0] )  );
	}*/
	
	//print_r($formats_meta);
	$quote_author = !empty( $formats_meta['_format_quote_source_name'] )  ? '<cite class="margin-bottom-30">' . $formats_meta['_format_quote_source_name'] . '</cite>' : '';
	$quote_author = !empty( $formats_meta['_format_quote_source_url'] ) ? sprintf( '<cite class="entry-format-meta margin-bottom-30"><a href="%s">%s</a></cite>', $formats_meta['_format_quote_source_url'], $formats_meta['_format_quote_source_name'] ) : $quote_author;
	
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ( isset( $quote_bg ) ? 'has-background' : '' )); ?>>
	<header class="entry-header"<?php echo isset( $quote_bg ) ? $quote_bg : '';?>>
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		<?php echo $quote_author;?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail margin-bottom-30">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>		
		
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->

<?php
}
/**
 * Link format
 ******************************************************************
 */
elseif( 'link' === $format ){
	
	$share_url = !empty( $formats_meta['_format_link_url'] ) ? $formats_meta['_format_link_url'] : get_permalink( get_the_ID() );
	
	//print_r($formats_meta);
	$share_url_text = !empty( $formats_meta['_format_link_url'] )  
		? sprintf( '<div class="entry-format-meta margin-bottom-30">%s <a href="%s">#</a></div>',
			$formats_meta['_format_link_url'],
			get_permalink( get_the_ID() ) )
		: '';
	
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php echo isset( $quote_bg ) ? $quote_bg : '';?>>
	<header class="entry-header">
		<h2 class="entry-title secondary-2-primary margin-bottom-30"><a href="<?php echo esc_url( $share_url ); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
		<?php echo $share_url_text;?>
	</header><!-- .entry-header -->
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom-30">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail margin-bottom-30">
			<a href="<?php echo esc_url( $share_url ); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>
		<?php endif; ?>
				
		<?php 
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){
			echo $output_excerpt;
		}else{
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
			) );
		}
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
	<footer class="entry-meta">
		<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php }?>
</article><!-- #post-<?php the_ID(); ?>## -->

<?php
}
?>
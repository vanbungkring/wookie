<?php
/**
 * @package yeahthemes
 */
	$format = get_post_format();
	
	if( false === $format )
		$format = 'standard';
	
	$formats_meta = yt_get_post_formats_meta( get_the_ID());
	
	/**
	 *Quote format
	 */
	$quote_author = !empty( $formats_meta['_format_quote_source_name'] )  ? '<cite class="entry-format-meta margin-bottom-30">' . $formats_meta['_format_quote_source_name'] . '</cite>' : '';
	$quote_author = !empty( $formats_meta['_format_quote_source_url'] ) ? sprintf( '<cite class="entry-format-meta margin-bottom-30"><a href="%s">%s</a></cite>', $formats_meta['_format_quote_source_url'], $formats_meta['_format_quote_source_name'] ) : $quote_author;
	
	/**
	 *Link format
	 */
	$share_url = !empty( $formats_meta['_format_link_url'] ) ? $formats_meta['_format_link_url'] : get_permalink( get_the_ID() );
	
	//print_r($formats_meta);
	$share_url_text = !empty( $formats_meta['_format_link_url'] )  
		? sprintf( '<div class="entry-format-meta margin-bottom-30">%s <a href="%s">#</a></div>',
			$formats_meta['_format_link_url'],
			get_permalink( get_the_ID() ) )
		: '';
	
	/**
	 *Extra class for entry title
	 */
	$entry_title_class = ' margin-bottom-30';
	if( 'quote' === $format  && $quote_author 
		|| 'link' === $format  && $share_url_text 
	){
		$entry_title_class = '';
	}
	
	
	$entry_title = get_the_title( get_the_ID() );
	if( 'link' === $format  && $share_url_text  ){
		$entry_title = sprintf('<a href="%s" title="%s" target="_blank" rel="external" class="secondary-2-primary">%s</a>', $share_url, get_the_title( get_the_ID() ), get_the_title( get_the_ID() ) );
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>
		
		<?php echo 'quote' === $format ? $quote_author : '';?>
		<?php echo 'link' === $format ? $share_url_text : '';?>
		
		<div class="entry-meta margin-bottom-30 hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		/*Standard*/
		if( in_array( $format, array( 'standard', 'quote', 'link' ) ) ){
			
			if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
			<div class="entry-thumbnail margin-bottom-30">
				<?php the_post_thumbnail(); ?>
				<?php

				if( $thumb_excerpt = yt_get_thumbnail_meta( get_post_thumbnail_id( get_the_ID() ), 'post_excerpt' ) ){
					echo '<div class="entry-caption thumbnail-caption">' . wpautop( $thumb_excerpt ) . '</div>';
				}
				//echo yt_get_post_thumbnail_meta( get_the_ID(), 'post_excerpt' ) ? yt_get_post_thumbnail_meta(null) : '';

				//print_r( yt_get_post_thumbnail_meta());
				?>
			</div>
			<?php endif;
			
		
		}
		elseif( 'image' === $format ){
			if ( ! post_password_required() && yt_get_the_post_format_image() ) : ?>
			<div class="entry-media margin-bottom-30">
			<?php echo yt_get_the_post_format_image(); ?>
			</div>
			<?php endif;
		}
		/*Audio*/
		elseif( 'audio' === $format ){
			if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ) : ?>
			<div class="entry-thumbnail<?php echo !yt_get_the_post_format_audio() ? ' margin-bottom-30' : ''; ?>">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php endif;
			if ( yt_get_the_post_format_audio() && !post_password_required() ) : ?>
			<div class="entry-format-media <?php echo has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ? 'with-cover ' : ''; ?>margin-bottom-30">
				<?php echo yt_get_the_post_format_audio(); ?>
			</div>
			<?php endif;
			
		/*Gallery*/	
		}elseif( 'gallery' === $format ){
			if ( yt_get_the_post_format_gallery() && !post_password_required() ) : ?>
			<div class="entry-format-media margin-bottom-30">
				<?php echo yt_get_the_post_format_gallery(); ?>
			</div>
			<?php endif;
			
		/*Video*/
		}elseif( 'video' === $format ){
			if ( yt_get_the_post_format_video() && !post_password_required() ) : ?>
			<div class="entry-format-media margin-bottom-30">
				<?php echo yt_get_the_post_format_video(); ?>
			</div>
			<?php endif;
		}
		?>
		
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links pagination-nav">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers">',
				'link_after' => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
	
	<?php
	
	$tag_list = get_the_tag_list( '', '' );
	if ( $tag_list ) :
	
	?>
	<footer class="entry-meta hidden-print">
		<?php
			$meta_text = '';
			/* translators: used between list items, there is a space after the comma */
			

			if ( '' != $tag_list ) {
				$meta_text = '<div class="entry-tags">';
				$meta_text .= __( '<strong class="tag-heading">%1$s Tagges:</strong> %2$s', 'yeahthemes' );
				$meta_text .= '</div>';
			} 

			printf(
				$meta_text,
				apply_filters('yt_icon_tag', '<i class="fa fa-tag"></i>'),
				$tag_list
			);
		?>
	</footer><!-- .entry-meta -->
	<?php endif;?>
	
</article><!-- #post-<?php the_ID(); ?>## -->

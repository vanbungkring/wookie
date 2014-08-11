	<?php yt_before_primary(); ?>
	
		<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
			
			<?php yt_primary_start(); ?>
			
			<div id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">
				<?php if( is_page() || is_archive() ) { ?>
					<h1 class="page-title"><?php _e( 'Question & Answer', 'dw-minion' ); ?></h1>
				<?php } ?>
				<div class="dwqa-container">
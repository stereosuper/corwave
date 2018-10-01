<?php get_header();
	
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	get_template_part('partials/sidebar');
	$sidebar_components = create_sidebar('page_template');
?>
<div class="<?php echo $sidebar_components['has_sidebar_class'] ?>">
	<header class='header-page'>
		<?php if( page_has_thumbnail() ): ?>
			<div class='header-bkg' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'></div>
		<?php endif; ?>
		<div class='header-page-container'>
			<div class='header-content'>
				<h1 class='container-small'><?php echo title_with_linebreaks( get_the_title() ); ?></h1>
			</div>
		</div>
	</header>
    <div class='wrapper-collant <?php echo $sidebar_components['custom_anchors_sidebar'] ?>'>
        <?php if ($sidebar_components['has_sidebar']) {
			echo $sidebar_components['custom_sidebar_menu'];
		} ?>
		<?php
			if (have_posts()): the_post(); 
				$the_content = get_the_content();
				if ($the_content !== ''):
		?>
			<div class='container pb'>
				<div class='container-small'>
					<div class='content-page base-style js-content-page' data-io='revealContentImg'>
						
						<?php
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('
							<div class="breadcrumbs">','</div>
							');
						}
						?>
					<?php the_content(); ?>
					</div>
				</div>
			</div>
		<?php 
				endif;
			endif;
		?>

        <?php get_template_part('partials/flexible-content'); ?>
    </div>
</div>

<?php get_footer(); ?>
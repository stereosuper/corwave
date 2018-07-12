<?php get_header(); 
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	$custom_anchors_sidebar  = isset($_POST['custom-anchors-sidebar']) ? ' custom-anchors-sidebar': '';
?>

<header class='header-page'>
	<?php if( page_has_thumbnail() ): ?>
		<div class='header-bkg' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'></div>
	<?php endif; ?>
	<div class='header-page-container'>
		<div class='header-content'>
			<h1 class='container-small'><?php the_title(); ?></h1>
		</div>
	</div>
</header>


<div class='container <?php echo $custom_anchors_sidebar ?>'>
	<div class='container-small'>
		<?php if ( have_posts() ) : the_post(); ?>
			<div class='content-page'>
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('
                        <div class="breadcrumbs">','</div>
                        ');
                    }
                ?>
				<?php the_content(); ?>
			</div>
		
		<?php else : ?>
					
			<h1>404</h1>

		<?php endif; ?>
	</div>
	<?php 
	if (isset($_POST['custom-anchors-sidebar'])): 
		echo $_POST['custom-anchors-sidebar'];
	endif; 
	?>
</div>

<?php get_template_part('partials/flexible-content'); ?>



<?php get_footer(); ?>
<?php get_header(); ?>

<?php
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;
?>

<header class='header-page'>
	<div class='header-page-container'>
		<?php if( page_has_thumbnail() ): ?>
			<div class='header-bkg' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'></div>
		<?php endif; ?>
		<div class='header-content'>
			<h1 class='container-small'><?php the_title(); ?></h1>
		</div>
	</div>
</header>


<div class='container'>
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
</div>

<?php get_footer(); ?>
<?php get_header(); ?>

<?php
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;
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
				<a href='#' class='cta'>
					<span>
						<svg class='ellypsis top' width='52' height='12' viewBox='0 0 52 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
							<path d='M0 9.66831C5.4502 3.81882 14.4298 0 24.584 0C34.7382 0 43.7178 3.81882 49.168 9.66831' transform='translate(1 1)' stroke='#5CCCFF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
						</svg>
						<svg class='ellypsis bottom' width='52' height='12' viewBox='0 0 52 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
							<path d='M0 9.66831C5.4502 3.81882 14.4298 0 24.584 0C34.7382 0 43.7178 3.81882 49.168 9.66831' transform='translate(1.83203 11) scale(1 -1)' stroke='#5CCCFF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
						</svg>
						Learn more about neptune
					</span>
					<svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
				</a>
			</div>
		
		<?php else : ?>
					
			<h1>404</h1>

		<?php endif; ?>
	</div>
</div>

<?php get_template_part('partials/flexible-content'); ?>



<?php get_footer(); ?>
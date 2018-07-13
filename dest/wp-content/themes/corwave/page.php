<?php get_header(); 
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	$has_sidebar = get_field('sidebar', get_the_ID());
	$custom_anchors_sidebar  = $has_sidebar ? ' custom-anchors-sidebar': '';
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
				<?php the_content() ?>
			</div>
		
		<?php else : ?>
					
			<h1>404</h1>

		<?php endif; ?>
	</div>
	<?php if ($has_sidebar):  ?>
	<nav class="anchors-sidebar js-anchors-sidebar">
		<ul class="anchors-list">
			<?php if (have_rows('links_and_anchors', get_the_ID())):  ?>
				<?php 
				while (have_rows('links_and_anchors', get_the_ID())): the_row(); 
					
					if ($link = get_sub_field('link')):
						$url = $link['url'];
						$class = $is_anchor = $url[0] === '#' ? 'class="scroll-to"' : '';
						
						$title = $link['title'];
					?>
					<li>
						<a href="<?php echo $url ?>" <?php echo $class ?>>
						<?php echo $title ?>
					</a>
					</li>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</ul>
	</nav>
	<?php endif; ?>
</div>

<?php get_template_part('partials/flexible-content'); ?>



<?php get_footer(); ?>
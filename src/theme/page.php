<?php get_header(); ?>

<div class='container'>
	<div class='container-small'>
		<?php if ( have_posts() ) : the_post(); ?>

			<h1><?php the_title(); ?></h1>
			<div class='content-page'>
				<?php the_content(); ?>
			</div>
		
		<?php else : ?>
					
			<h1>404</h1>

		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
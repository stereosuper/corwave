<?php get_header();?>

<div>
    <?php if ( have_posts() ) : the_post(); ?>

        <header class='product-header'>
            <div class='product-header-texts'>
                <h1><?php the_title(); ?></h1>
                <h2><?php the_field('header_title'); ?></h2>
            </div>
            <div class='product-header-image'>
                <?php wp_get_attachment_image( get_field('header_image'), 'full' ); ?>
            </div>
        </header>
			
    <?php else : ?>
                
        <h1>404</h1>

    <?php endif; ?>
</div>

<?php get_footer(); ?>
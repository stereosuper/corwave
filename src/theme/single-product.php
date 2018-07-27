<?php get_header();?>

<div>
    <?php if ( have_posts() ) : the_post(); ?>

        <header class='product-header container'>
            <div class='product-header-texts'>
                <h1><?php the_title(); ?></h1>
                <h2><?php the_field('header_title'); ?></h2>
            </div>
            <div class='product-header-image'>

                <?php $hImage = get_field('header_image');
                if( $hImage ) {

                    echo wp_get_attachment_image( $hImage, 'full' );

                } ?>
            </div>
        </header>

        <section class='product-video'>
            <p><?php the_field('video_text'); ?></p>
            <?php if(get_field('video_id')){ ?>
            <div class='wrapper-video-product'>
                <div class='inner-video wrapper-video js-video' data-id='<?php the_field('video_id'); ?>'>
                    <div class='iframe'></div>
                    <div class='cover-video' style='background-image:url(<?php echo wp_get_attachment_url(get_field('video_cover')); ?>)'>
                        <span class='play'></span>
                    </div>
                </div>
            </div>
        <?php } ?>
        </section>

        <section class='keywords container'>
            <?php if(get_field('keywords')): ?>
                <ul class='keywords-list'>
                    <?php while(have_rows('keywords')): the_row(); ?>
                        <li class='keywords-item'>
                            <?php the_sub_field('keyword'); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <div class='keywords-content-container'>
                <h2><?php the_field('keywords_title'); ?></h2>
                
                <div class='keywords-content'>
                    <?php if( $kImage = get_field('keywords_image') ): ?>
                        <div class='keywords-content-img'>                    
                            <?php echo wp_get_attachment_image( $kImage, 'full' ); ?>
                        </div>
                    <?php endif; ?>
                
                    <div class='keywords-content-txt'>                    
                        <?php the_field('keywords_text'); ?>
                    </div>
                </div>

            </div>
            

        </section>
			
    <?php else : ?>
                
        <h1>404</h1>

    <?php endif; ?>
</div>

<?php get_footer(); ?>
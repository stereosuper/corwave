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

        <section class='dark-section'>
            <div class='dark-texts'>
                <?php 
                    if ($dark_content = get_field('dark_content')) {
                        echo $dark_content;
                    }
                ?>
            </div>
            <div class='dark-image'>
            <?php 
                if($dark_image = get_field('dark_image')) {
                    echo wp_get_attachment_image( $dark_image, 'full' );
                }
            ?>
            </div>
        </section>

        <section class='photo-text container'>
            <div class='photo-text-content-container'>
                <h2><?php the_field('pt_title'); ?></h2>
                
                <div class='photo-text-content'>
                    <?php if( $pt_image = get_field('pt_image') ): ?>
                        <div class='photo-text-content-img'>                    
                            <?php echo wp_get_attachment_image( $pt_image, 'full' ); ?>
                        </div>
                    <?php endif; ?>
                
                    <div class='photo-text-content-txt'>
                        <?php the_field('pt_text'); ?>
                    </div>
                </div>

            </div>
        </section>
        <section class='product-cards container pb'>
            <?php if (have_rows('columns')) : ?>
                <div class='cards'>
                <?php while ( have_rows('columns') ) : the_row(); ?>
                    <div class='card'>
                        <div class='card-content'>
                            <h3><?php the_sub_field('title') ?></h3>
                            <p><?php the_sub_field('text') ?></p>
                        </div>
                        <?php if ($link = get_sub_field('link')) : ?>
                        <a class='cta cta-light' href='<?php echo $link['url']; ?>' title="<?php echo htmlspecialchars($link['title'], ENT_QUOTES); ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                            <span>
                                <?php echo $link['title']; ?>
                            </span>
                            <svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
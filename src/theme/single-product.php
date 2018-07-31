<?php get_header();

    get_template_part('partials/sidebar');
    $sidebar_components = create_sidebar('product_template');
?>
<div class="wrapper-collant <?php echo $sidebar_components['has_sidebar_class'] ?> <?php echo $sidebar_components['custom_anchors_sidebar'] ?>">
    <?php if ( have_posts() ) : the_post(); ?>
        <?php 
        $is_anchored = get_field('header_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('header_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <header <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='product-header container <?php echo $anchor_js_selector ?>'>
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

        <?php if ($sidebar_components['has_sidebar']) {
			echo $sidebar_components['custom_sidebar_menu'];
		} ?>

        <?php 
        $is_anchored = get_field('video_section_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('video_section_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <section <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='product-video <?php echo $anchor_js_selector ?>'>
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
        
        <?php 
        $is_anchored = get_field('keyword_section_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('keyword_section_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <section <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='keywords container <?php echo $anchor_js_selector ?>'>
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
        
        <?php 
        $is_anchored = get_field('dark_section_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('dark_section_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <section <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='dark-section <?php echo $anchor_js_selector ?>'>
            <div class='container'>
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
                        echo wp_get_attachment_image($dark_image, 'full', false, ['class'=>'object-fit']);
                    }
                ?>
                </div>
            </div>
        </section>
        
        <?php 
        $is_anchored = get_field('photo_text_section_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('photo_text_section_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <section <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='photo-text container <?php echo $anchor_js_selector ?>'>
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

        <?php 
        $is_anchored = get_field('columns_section_is_anchored');
        $anchor_id = $is_anchored ? 'id="'.get_field('columns_section_id').'-will-scroll"' : '';
        $anchor_io = $is_anchored ? 'data-io="activeAnchor"' : '';
        $anchor_js_selector = $is_anchored ? 'js-custom-anchor' : '';
        ?>
        <section <?php echo $anchor_id ?> <?php echo $anchor_io ?> class='product-cards container pb <?php echo $anchor_js_selector ?>'>
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
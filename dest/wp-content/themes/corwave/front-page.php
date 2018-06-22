<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>
    <div class='hero menu-pad'>
        <div class='container'>
            <div class='container-small'>
                <h1 class='align-center h1'><?php the_title(); ?></h1>
                <p class='align-center'><?php the_field('hero_text'); ?></p>
                <?php 
                $button = get_field('hero_button'); 
                if($button):
                ?>
                    <a class='cta' href='<?php echo $button['link']['url']; ?>' title="<?php echo $button['link']['title']; ?>" target="<?php echo $button['link']['target']; ?>" <?php echo $button['link']['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                        <span>
                            <svg class='ellypsis top'><use xlink:href='#icon-ellypsis-top'></use></svg>
                            <svg class='ellypsis bottom'><use xlink:href='#icon-ellypsis-bottom'></use></svg>
                            <?php echo $button['link']['title']; ?>
                        </span>
                        <svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class='what-we-do pt pb'>
        <div class='container'>
            <div class='container-small'>
                <h2 class='wwd-title align-center'><?php the_field('wwd_title'); ?></h2>
                <p class='wwd-text align-center'>
                    <?php the_field('wwd_text'); ?>
                </p>
                <?php if( have_rows('wwd_links') ): ?>
                <div class='cta-wrapper'>
                <?php
                    while ( have_rows('wwd_links') ) : the_row(); 
                        $wwdLink = get_sub_field('link'); 
                        
                        if($wwdLink):?>
                            <a class='cta cta-light white' href='<?php echo $wwdLink['url']; ?>' title="<?php echo $wwdLink['title']; ?>" target="<?php echo $wwdLink['target']; ?>" <?php echo $wwdLink['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                <span>
                                    <?php echo $wwdLink['title']; ?>
                                </span>
                                <svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
                            </a>
                        <?php endif; ?>

                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class='home-slider'>
        <?php 
            $autoscroll = get_field('auto_scrolling', get_the_ID());
            $layout_left = get_field('slider_layout', get_the_ID());
            $reversed_mobile = get_field('module_text_img_reversed', get_the_ID());
        ?>
        <div class='slider js-slider <?php echo $reversed_mobile ? 'mod-mobile-reversed' : '' ?>' <?php echo $autoscroll ? 'data-auto-slide="true"' : '' ?>>
            <?php if( have_rows('slides') ):?>
                <div class='bullets'>
                    <?php 
                    for ($i = 0; $i < count(get_field('slides')) && count(get_field('slides')) > 1; $i++) :
                        $class = $i == 0 ? 'bullet active' : 'bullet';
                    ?>
                        <span class='<?php echo $class ?>' data-slide="<?php echo $i; ?>"></span>
                    <?php endfor; ?>
                </div>
                <?php $total = count(get_field('slides')); ?>
                <div class='slides' data-nb='<?php echo $total; ?>'>
                    
                    <?php $sCount = 0; while ( have_rows('slides') ) : the_row(); ?>
                        <?php 
                            
                            $sClass = $sCount == 0 ? 'slide active half' : 'slide half';
                            $sClass .= $layout_left ? ' reversed' : '';
                        ?>
                        <div class='<?php echo $sClass; ?>' data-slide="<?php echo $sCount; ?>">
                            <?php 
                                $image_side = get_sub_field('image_side');
                                $text_side = get_sub_field('text_side');
                            ?>

                            <?php if( $text_side ): ?>
                                <?php
                                    $class = $text_side['text_color'] === 'black' ? 'color-black' : '';
                                    // $styles = $text_side['bg_color'] ? 'style="background-color:'. $text_side['bg_color'] .'"' : ''
                                ?>
                                <div class='txt-side <?php echo $class; ?>'>
                                    <span class='slide-layer-background' <?php //echo $styles; ?>></span>
                                    <div class='inner-txt-side pt pb'>
                                        <?php echo $text_side['text_content'] ?>
                                        <?php if (sizeof($text_side['links'])) : 
                                            foreach ($text_side['links'] as $link) :
                                                $link = $link['link'];
                                        ?>
                                            <a class='cta cta-light js-cta' href='<?php echo $link['url']; ?>' title="<?php echo $link['title']; ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                                <span>
                                                    <?php echo $link['title']; ?>
                                                </span>
                                                <svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
                                            </a>
                                        <?php 
                                            endforeach;
                                        endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if( $image_side ): ?>
                                <div class='img-side'>
                                    <?php echo wp_get_attachment_image($image_side['image']['ID'], 'full', false, ['class'=>'object-fit']) ?>
                                </div>
                            <?php endif; ?>


                        </div>
                        <?php $sCount++; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class='home-company pt pb'>
        <div class='container'>
            <div class='container-small'>
                <h2><?php get_field('company_title') ? the_field('company_title') : null ?></h2>
                <?php get_field('company_description') ? the_field('company_description') : null ?>
            </div>
        </div>
    </div>
    <div class='home-cards'>
        <div class='container'>
            <div class='container-small'>
                <?php if (have_rows('cards')) : ?>
                    <div class='cards'>
                    <?php while ( have_rows('cards') ) : the_row(); ?>
                        <div class='card'>
                            <div class='card-content'>
                                <h3><?php the_sub_field('title') ?></h3>
                                <p><?php the_sub_field('text') ?></p>
                            </div>
                            <?php if ($link = get_sub_field('link')) : ?>
                            <a class='cta cta-light' href='<?php echo $link['url']; ?>' title="<?php echo $link['title']; ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
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
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
<?php get_header(); ?>

<?php if ( have_posts() ) : the_post(); ?>
    <div class='hero menu-pad'>
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
    <div class='what-we-do pt pb'>
            <h2><?php the_field('wwd_title'); ?></h2>
            <p>
                <?php the_field('wwd_text'); ?>
            </p>
            <?php if( have_rows('wwd_links') ):
                while ( have_rows('wwd_links') ) : the_row(); 
                    $wwdLink = get_sub_field('link'); 
                    
                    if($wwdLink):?>
                        <a class='cta' href='<?php echo $wwdLink['url']; ?>' title="<?php echo $wwdLink['title']; ?>" target="<?php echo $wwdLink['target']; ?>" <?php echo $wwdLink['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                            <span>
                                <svg class='ellypsis top'><use xlink:href='#icon-ellypsis-top'></use></svg>
                                <svg class='ellypsis bottom'><use xlink:href='#icon-ellypsis-bottom'></use></svg>
                                <?php echo $wwdLink['title']; ?>
                            </span>
                            <svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
                        </a>
                    <?php endif; ?>

                <?php endwhile;
            endif; ?>
        </div>
        <div class='home-slider'>
            <?php 
                $autoscroll = get_field('auto_scrolling', get_the_ID());
                $layout_left = get_field('slider_layout', get_the_ID());
            ?>
            <div class='slider js-slider' <?php echo $autoscroll ? 'data-auto-slide="true"' : '' ?>>
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
                            ?>
                            <div class='<?php echo $sClass; ?>' data-slide="<?php echo $sCount; ?>">
                                <?php 
                                    $image_side = get_sub_field('image_side');
                                    $text_side = get_sub_field('text_side');
                                ?>

                                <?php if( $text_side ): ?>
                                    <?php
                                        $class = $text_side['text_color'] === 'black' ? 'color-black' : '';
                                        $styles = $text_side['bg_color'] ? 'style="background-color:'. $text_side['bg_color'] .'"' : ''
                                    ?>
                                    <div class='left-side <?php echo $class; ?>'>
                                        <?php if (count(get_field('slides')) > 1) : ?>
                                            <svg class="icon arrow-home icon-arrow-left js-arrow">
                                                <use xlink:href="#icon-arrow"></use>
                                            </svg>
                                            <svg class="icon arrow-home icon-arrow-right js-arrow">
                                                <use xlink:href="#icon-arrow"></use>
                                            </svg>
                                        <?php endif; ?>
                                        <span class='slide-layer-background' <?php echo $styles; ?>></span>
                                        <div class='inner-left-side pt pb'>
                                            <?php echo $text_side['text_content'] ?>
                                            <?php if (sizeof($text_side['links'])) : 
                                                foreach ($text_side['links'] as $link) :
                                                    $link = $link['link'];
                                            ?>
                                                <a class='cta' href='<?php echo $link['url']; ?>' title="<?php echo $link['title']; ?>" target="<?php echo $link['target']; ?>" <?php echo $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                                                    <span>
                                                        <svg class='ellypsis top'><use xlink:href='#icon-ellypsis-top'></use></svg>
                                                        <svg class='ellypsis bottom'><use xlink:href='#icon-ellypsis-bottom'></use></svg>
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
                                    <div class='right-side pt pb'>
                                        <!-- COMBAK: Do not forget to add object-fit cover to the image -->
                                        <!-- NOTE: Don't worry the IE fallback is already in fallback.js -->
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
            <div class='container-small'>
                <h2><?php get_field('company_title') ? the_field('company_title') : null ?></h2>
                <?php get_field('company_description') ? the_field('company_description') : null ?>
            </div>
        </div>
<?php endif; ?>

<?php get_footer(); ?>
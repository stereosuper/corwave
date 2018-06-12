<?php get_header(); ?>

    <?php if ( have_posts() ) : the_post(); ?>
        <div class='hero menu-pad' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'>
            <div class='container-small'>
                <h2 class='align-center h1'><?php the_title(); ?></h2>
                <h1 class='align-center'><?php the_field('hero_text'); ?></h1>
                <div class='multiple-btn'>
                    <?php $demo = get_field('hero_demo_button'); 
                    
                    if($demo): ?>

                        <a class="btn btn-layers" href="<?php echo $demo['link']['url']; ?>" title="<?php echo $demo['link']['title']; ?>" target="<?php echo $demo['link']['target']; ?>"><?php echo $demo['link']['title']; ?></a>

                    <?php endif; ?>

                    <?php $other = get_field('hero_other_button'); 
                    
                    if($other): ?>

                        <button class="btn btn-icon js-pop-up-cta" type='button'><?php echo $other['button_label']; ?><span><svg class='icon'><use xlink:href='#icon-<?php echo $other["picto"]; ?>'></svg></span></button>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php get_footer(); ?>
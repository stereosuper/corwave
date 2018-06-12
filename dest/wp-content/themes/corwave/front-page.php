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
                    <a class="btn btn-layers" href="<?php echo $button['link']['url']; ?>" title="<?php echo $button['link']['title']; ?>" target="<?php echo $button['link']['target']; ?>"><?php echo $button['link']['title']; ?></a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

<?php get_footer(); ?>
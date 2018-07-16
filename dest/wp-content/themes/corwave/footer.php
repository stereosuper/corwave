        </main>

        <footer class='footer' role='contentinfo'>
            <div class='container'>
                
                    <?php if( have_rows('website_footer', 'options') ): ?>
                        <div class='top'>

                            <?php 
                                while ( have_rows('website_footer', 'options') ) : the_row();
                                $class = get_sub_field('footer_double') ? 'footer-category big' : 'footer-category';
                            ?>

                                <section class='<?php echo $class; ?>'>

                                    <h4 class='category-title'><?php the_sub_field('footer_column_title') ?></h4>

                                    <?php if( have_rows('footer_column_links') ): ?>
                                        <ul>
                                            <?php 
                                                while ( have_rows('footer_column_links') ) : the_row();
                                                $link = get_sub_field('footer_column_link');
                                                    if($link):
                                            ?>

                                                        <li>
                                                            <a href='<?php echo $link['url'] ?>' title='<?php echo $link['title'] ?>'><?php echo $link['title'] ?></a>
                                                        </li>


                                            <?php 
                                                    endif;
                                                endwhile; 
                                            ?>
                                        </ul>
                                    <?php endif; ?>


                                </section>
                            <?php endwhile; ?>
                        </div>
                    <?php 
                        endif;
                    ?>
                <aside class='legal'>
                    <span>© Copyright CorWave SA 2018
                        <?php 
                        if ($footer_legals = get_field('footer_legals', 'option')) :
                            $url = $footer_legals['url'];
                            $title = $footer_legals['title'];
                            $target = $footer_legals['target']
                        ?>
                        - <a href='<?php echo $url ?>'
                        title='<?php echo $title ?>'
                        target='<?php echo $target ?>'
                        <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php _e('Legal Notice', 'corwave') ?>
                        </a></span>
                        <?php endif; ?>
                </aside>
            </div>
        </footer>

        <?php get_template_part( 'include/icons' ); ?>

        <?php wp_footer(); ?>

        </body>
    </html>

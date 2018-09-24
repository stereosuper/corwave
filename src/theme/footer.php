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
                                                            <a href='<?php echo $link['url'] ?>' title='<?php echo htmlspecialchars($link['title'], ENT_QUOTES); ?>'><?php echo $link['title'] ?></a>
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
                <div class='legal'>
                    <span class='social'>
                        <?php if(get_field('social_networks_linkedin', 'option')): ?>
                            <a href="<?php the_field('social_networks_linkedin', 'option') ?>" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                                <svg class='icon'><use xlink:href='#icon-linkedin'></use></svg>
                            </a>
                        <?php endif; ?>
                        <?php if(get_field('social_networks_twitter', 'option')): ?>
                            <a href="<?php the_field('social_networks_twitter', 'option') ?>" target="_blank" rel="noopener noreferrer" title="Twitter">
                                <svg class='icon'><use xlink:href='#icon-twitter'></use></svg>
                            </a>
                        <?php endif; ?>
                    </span>
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
                        <?php echo $title ?>
                        </a></span>
                        <?php endif; ?>
                </div>
            </div>
        </footer>

        <?php get_template_part( 'include/icons' ); ?>

        <?php wp_footer(); ?>
        <script async src='https://www.google.com/recaptcha/api.js'></script>
        </body>
    </html>

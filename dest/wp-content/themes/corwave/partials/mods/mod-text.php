<?php
    $modT_class = array('module-text','clearfix','pb','container');
    $modT_container_class = array('container-small');

    $mod  = sprintf(
                '<section class="%1$s"><div class="%2$s">',
                join(' ', $modT_class),
                join(' ', $modT_container_class)
            );
        $mod .= get_sub_field('module_text-content');
        
        if( have_rows('module_text-buttons') ):
            $mod .= '<div class="multiple-btn">';
            while ( have_rows('module_text-buttons') ) : the_row();

                $btn = get_sub_field('module_text-button-link');
                $btn['class'] = array('btn', 'btn-layers', 'alternative');

                if( get_sub_field('module_text-button-type') ):
                    array_pop($btn['class']);
                endif;

                $mod .= sprintf(
                            '<a href="%1$s" target="%2$s" class="%3$s" rel="%4$s">%5$s</a>',
                            $btn['url'],
                            $btn['target'],
                            join(' ', $btn['class']),
                            $btn['target'] == '_blank' ? "noopener noreferrer" : "",
                            $btn['title']
                        );
                            
            endwhile;
            $mod .= '</div>';
         endif;
    $mod .= '</section></div>';
    echo $mod;
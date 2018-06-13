<?php
    // $columns_row = get_sub_field('columns_row');
?>
    <section class='module-columns pb'>
        <div class='container-columns container'>
            <div class='container-small'>
                <?php 
                $introduction = get_sub_field('introduction');
                $intro_txt = get_sub_field('columns_introduction');
                if ($introduction && $intro_txt) : 
                    echo $intro_txt;
                endif;
                if( have_rows('columns_row') ):
                    while ( have_rows('columns_row') ) : the_row();
                        $columns_number = get_sub_field('columns_number') ? intval(get_sub_field('columns_number')) : false;

                        $first_col = get_sub_field('first_column');
                        $second_col = get_sub_field('second_column');
                        $third_col = get_sub_field('third_column');
                        $fourth_col = get_sub_field('fourth_column');

                        $columns = array(
                            $first_col,
                            $second_col,
                        );
                        if ($columns_number === 3) {
                            $columns[] = $third_col;
                        } else if ($columns_number === 4) {
                            $columns[] = $third_col;
                            $columns[] = $fourth_col;
                        }
                ?>
                <div class="columns <?php echo $columns_number === 3 ? ' third' : '' ?><?php echo $columns_number === 4 ? ' fourth' : '' ?>">
                    <?php foreach ($columns as $col) : ?>
                    <div class="column">
                        <div class='column-content'>
                            <?php echo $col['column_content']; ?>
                        </div>                    

                        <?php if( $col['column_links']): ?>
                            <div class='column-links'>
                                <?php 
                                    foreach ($col['column_links'] as $linkArray) : 
                                    $link = $linkArray['link'];
                                ?>
                                    <a href='<?php echo $link["url"] ?>'><?php echo $link["title"] ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php 
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </section>
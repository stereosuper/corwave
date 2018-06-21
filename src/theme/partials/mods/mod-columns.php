<?php
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

            switch ($columns_number) {
                case 2:
                    if ($second_col['column_content'] === '') {
                        $class_col_number = 'solo';
                    } else {
                        $class_col_number = '';
                    }
                    break;
                case 3:
                    $class_col_number = 'third';
                    break;
                case 4:
                    $class_col_number = 'fourth';
                    break;
                
                default:
                    break;
            }
        endwhile;
    endif;
?>
    <section class='module-columns pb'>
        <div class="container-columns container-big">
            <?php
            $introduction = get_sub_field('introduction');
            $intro_txt = get_sub_field('columns_introduction');
            if ($introduction && $intro_txt) :
            ?>
                <div class='introduction <?php echo $class_col_number ?>'>
                    <?php echo $intro_txt ?>
                </div>
            <? endif;
            if( have_rows('columns_row') ):
                while ( have_rows('columns_row') ) : the_row();
            ?>
            <div class="columns <?php echo $class_col_number ?>">
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
    </section>
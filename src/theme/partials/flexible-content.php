<?php if( have_rows('pages_modules') ): ?>
    <section class='flexible-content'>
        <?php while( have_rows('pages_modules') ): the_row(); ?>
            <?php switch (get_row_layout()) {
                case 'module_text-img':
                    include 'mods/mod-text-img.php';
                    break;

                case 'module_text':
                    include 'mods/mod-text.php';
                    break;

                case 'module_columns':
                    include 'mods/mod-columns.php';
                    break;
                case 'module_contact':
                    include 'mods/mod-contact.php';
                    break;
                
                default:
                    # code...
                    break;
            } ?>
        <?php endwhile; ?>
    </section>
<?php endif; ?>
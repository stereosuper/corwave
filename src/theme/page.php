<?php get_header();
	$post_id = get_the_ID();
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	$has_sidebar = get_field('sidebar', get_the_ID());
	$custom_anchors_sidebar  = $has_sidebar ? ' custom-anchors-sidebar': '';
?>

<header class='header-page'>
	<?php if( page_has_thumbnail() ): ?>
		<div class='header-bkg' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'></div>
	<?php endif; ?>
	<div class='header-page-container'>
		<div class='header-content'>
			<h1 class='container-small'><?php the_title(); ?></h1>
		</div>
	</div>
</header>


<div class='container <?php echo $custom_anchors_sidebar ?>'>
	<div class='container-small'>
		<?php if ( have_posts() ) : the_post(); ?>
			<div class='content-page'>
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('
                        <div class="breadcrumbs">','</div>
                        ');
                    }
                ?>
				<?php the_content() ?>
			</div>
		
		<?php else : ?>
					
			<h1>404</h1>

		<?php endif; ?>
	</div>
	<?php 
		if (isset(get_nav_menu_locations()['tree_structure'])) {
			$nav_id = get_nav_menu_locations()['tree_structure'];
			class TexasRanger extends Walker_Nav_Menu {
				function __construct($post_id) {
					$this->post_id = $post_id;
				}
				public function start_lvl( &$output, $depth = 0, $args = array()) {
				}
				public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0) {
					$id = intval($item->object_id);
					// var_dump($item);
					if ($this->post_id === $id) {
						$is_anchor = $item->url === '#';
						$classLink = $is_anchor ? 'class="scroll-to"' : '';
						$classLi = $is_anchor ? 'class="js-anchor-link"' : '';
		
						$output .= "<li $classLi>";
						$output .= '<a';
						$output .= " href='$item->url' ";
						$output .= " title='$item->title' ";
						$output .= " target='$item->target' ";
						$output .= " $classLink ";
						$output .= $item->target === '_blank' ? ' rel="noopener noreferrer" s' : '';
						$output .= '>';
						$output .= $item->title;
					}
			}
			public function end_el( &$output, $item, $depth = 0, $args = array()) {
				$id = intval($item->object_id);
				if ($this->post_id === $id) {

					$output .= '</a></li>';
				}
			}
			public function end_lvl( &$output, $depth = 0, $args = array()) {}
		}


		wp_nav_menu( array( 
			'container'   => 'nav',
			'container_class' => 'anchors-sidebar js-anchors-sidebar',
			'menu' => $nav_id,
			'menu_class' => 'anchors-list',
			'depth' => 0,
			'walker' => new TexasRanger($post_id),
		));
	}
	?>
	<?php if ( false && $has_sidebar):  ?>
	<nav class="anchors-sidebar js-anchors-sidebar">
		<ul class="anchors-list">
			<?php if (have_rows('links_and_anchors', get_the_ID())):  ?>
				<?php 
				while (have_rows('links_and_anchors', get_the_ID())): the_row(); 
					
					if ($link = get_sub_field('link')):
						$url = $link['url'];
						$is_anchor = $url[0] === '#';

						$classLink = $is_anchor ? 'class="scroll-to"' : '';
						$classLi = $is_anchor ? 'class="js-anchor-link"' : '';
						
						$title = $link['title'];
					?>
					<li <?php echo $classLi ?>>
						<a href="<?php echo $url ?>" <?php echo $classLink ?>>
						<?php echo $title ?>
					</a>
					</li>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</ul>
	</nav>
	<?php endif; ?>
</div>

<?php get_template_part('partials/flexible-content'); ?>



<?php get_footer(); ?>
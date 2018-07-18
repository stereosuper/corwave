<?php get_header();
	
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	$post_id = get_the_ID();
$has_sidebar = false;
$custom_sidebar_menu = null;

	if (isset(get_nav_menu_locations()['tree_structure'])) {
		$nav_id = get_nav_menu_locations()['tree_structure'];
		class TexasRanger extends Walker_Nav_Menu {
			function __construct($post_id) {
				$this->has_sidebar = false;
				$this->post_id = $post_id;
				$this->parent_id = null;
			}
			public function start_lvl( &$output, $depth = 0, $args = array()) {
			}
			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0) {
				$id = intval($item->object_id);
				if ($this->post_id === $id) {
					$this->set_parent_id(intval($item->ID));
				}
				
				if ($this->parent_id === intval($item->menu_item_parent) && $this->post_id !== $id) {
					$this->has_sidebar = true;

					$is_anchor = strpos($item->url, '#') !== false;
					$classLink = $is_anchor ? 'class="scroll-to"' : '';
					$classLi = $is_anchor ? 'class="js-anchor-link"' : '';
	
					$output .= "<li $classLi>";
					$output .= '<a';
					$output .= " href='$item->url' ";
					$output .= " title='".htmlspecialchars($item->title, ENT_QUOTES)."' ";
					$output .= " target='$item->target' ";
					$output .= " $classLink ";
					$output .= $item->target === '_blank' ? ' rel="noopener noreferrer" s' : '';
					$output .= '>';
					$output .= $item->title;
				}
			}
			public function end_el( &$output, $item, $depth = 0, $args = array()) {
				$id = intval($item->object_id);
				if ($this->parent_id === intval($item->menu_item_parent) && $this->post_id !== $id) {
					$output .= '</a></li>';
				}
			}
			public function end_lvl( &$output, $depth = 0, $args = array()) {}
			private function set_parent_id($id) {
				$this->parent_id = $id;
			}
		}

		$texas_ranger_instance = new TexasRanger($post_id);
		$custom_sidebar_menu = wp_nav_menu( array( 
			'container'   => 'nav',
			'container_class' => 'anchors-sidebar js-anchors-sidebar',
			'menu' => $nav_id,
			'menu_class' => 'anchors-list',
			'depth' => 0,
			'walker' => $texas_ranger_instance,
			'echo' => false,
		));

		$has_sidebar = $texas_ranger_instance->has_sidebar;
	}

	$has_sidebar_class  = $has_sidebar ? ' class="has-sidebar"': '';
	$custom_anchors_sidebar  = $has_sidebar ? ' custom-anchors-sidebar': '';
?>
<div <?php echo $has_sidebar_class ?>>
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
		if ($has_sidebar) {
			echo $custom_sidebar_menu;
		}
		?>
	</div>
	<?php get_template_part('partials/flexible-content'); ?>
</div>

<?php get_footer(); ?>
<?php
/**
 * Contact Archive Template.
 *
 * This file serves as a custom archive template for the contact post type.
 */


get_header();
?>

<?php
// get the current URL so we can add active states to our taxonomy filters
$current_page_url = home_url( $wp->request ) . '/';

// default variables
$view_all = ( is_archive() ) ? false : true;
$view_all_url = '/contact-directory/';
$taxonomy = WPCD_CONTACT_GROUP_TAXONOMY;
$current_term = ( is_single() ) ? get_the_terms( $post, $taxonomy )[0] : null;
$terms = null;

// define args that will retrieve all children of the "Knowledge Center" category (id: 222)
$args = array(
	'taxonomy' => $taxonomy
);

// get the terms
$terms = get_terms( $args );
$groups = array();

// loop through the terms
foreach( $terms as $term ) {
	// get the contacts for this contact group
	$args = array(
		'post_type' => 'contact',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term->slug,
			),
		),
	);
	$query = new WP_Query( $args );

	$groups[] = array(
		'term' => $term,
		'query' => $query
	);
}

?>

	<ul class="nav filter-nav contact-group-nav flex-column">

		<?php
		// loop through the groups
    foreach( $groups as $group ) :
      $term = $group['term'];
			$posts = $group['query']->posts;
			$target = 'collapse-' . $term->slug;
			$is_target_active = ( $current_term->term_id == $term->term_id ) ? true : false;
			$collapse = ( $is_target_active ) ? 'show' : 'collapse';
			$expanded = ( $is_target_active ) ? 'true' : 'false';
			?>

			<li class="nav-item">
				<a href="#<?php echo $target; ?>" class="nav-link" data-toggle="collapse" aria-expanded="<?php echo $expanded; ?>">
					<i class="material-icons">keyboard_arrow_right</i>
					<?php echo $term->name; ?>
				</a>

				<ul class="nav sub-nav <?php echo $collapse; ?>" id="<?php echo $target; ?>">

					<?php
					foreach( $posts as $post ) {
						$active_class = ( $current_page_url == get_permalink( $post ) ) ? 'active' : '';
						?>

						<li class="nav-item w-100">
							<a href="<?php echo get_permalink( $post ); ?>" class="nav-link <?php echo $active_class; ?>">
								<?php echo $post->name; ?>
							</a>
						</li>

						<?php
					}
					?>

				</ul>
			</li>

			<?php
		endforeach;
		?>
  </ul>

	<?php
	if( $view_all ) {
		echo '<a href="' . $view_all_url . '" class="btn btn-primary my-3 w-100">View All</a>';
	}

  // loop through the groups
  foreach( $groups as $group ) {
    $term = $group['term'];
    $query = $group['query'];
    ?>

    <h1 class="col-12"><?php echo $term->name; ?></h1>

    <?php
    while ( $query->have_posts() ) {
      $query->the_post();

      $post_thumbnail = get_the_post_thumbnail( $query->post->ID, 'full', array( 'class' => 'img-fluid mx-auto d-block' ) );
      ?>

      <div class="directory-image media-container">
        <a href="<?php echo get_permalink( $query->post ); ?>">
          <?php echo $post_thumbnail; ?>
        </a>

        <div class="directory-details">
          <div class="directory-name">
            <a href="<?php echo get_permalink( $query->post ); ?>">
              <?php echo $query->post->name; ?>
            </a>
          </div>

          <div class="directory-title">
            <?php echo $query->post->title; ?>
          </div>

        </div>
      </div>
      <?php
    }
  }

get_footer();

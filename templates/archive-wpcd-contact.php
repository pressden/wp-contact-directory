<?php
/**
 * Contact Archive Template.
 *
 * This file serves as a custom archive template for the contact post type.
 *
 * @package WP Contact Directory
 */

get_header();

// get the current URL so we can add active states to our taxonomy filters
$current_page_url = home_url( $wp->request ) . '/';

// Default variables.
$view_all     = ! is_archive();
$view_all_url = '/contact-directory/';
$current_term = ( is_single() ) ? get_the_terms( $post, WPCD_CONTACT_GROUP_TAXONOMY )[0] : null;

// Define args that will retrieve all children of the "Knowledge Center" category (id: 222).
$args = [
	'taxonomy' => WPCD_CONTACT_GROUP_TAXONOMY,
];

// Get the terms.
$terms  = get_terms( $args );
$groups = array();

// Loop through the terms.
foreach ( $terms as $the_term ) {
	// Get the contacts for this contact group.
	$args  = [
		'post_type'      => WPCD_CONTACT_POST_TYPE,
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'tax_query'      => [
			[
				'taxonomy' => WPCD_CONTACT_GROUP_TAXONOMY,
				'field'    => 'slug',
				'terms'    => $the_term->slug,
			],
		],
	];
	$query = new WP_Query( $args );

	$groups[] = array(
		'term'  => $the_term,
		'query' => $query,
	);
}

?>
<div class="facet-bar">
	<ul class="nav filter-nav contact-group-nav flex-column">

		<?php
		// Loop through the groups.
		foreach ( $groups as $group ) :
			$group_term       = $group['term'];
			$group_term_posts = $group['query']->posts;
			$target           = 'collapse-' . $group_term->slug;
			$is_target_active = ( $current_term instanceof \WP_Term ) && ( $current_term->term_id === $group_term->term_id ) ? true : false;
			$collapse         = ( $is_target_active ) ? 'show' : 'collapse';
			$expanded         = ( $is_target_active ) ? 'true' : 'false';
			?>

			<li class="nav-item">
				<a href="#<?php echo esc_attr( $target ); ?>" class="nav-link" data-toggle="collapse" aria-expanded="<?php echo esc_attr( $expanded ); ?>">
					<i class="material-icons"><?php esc_html_e( 'keyboard_arrow_right', 'wpcd' ); ?></i>
					<?php echo esc_html( $group_term->name ); ?>
				</a>

				<ul class="nav sub-nav <?php echo sanitize_html_class( $collapse ); ?>" id="<?php echo esc_attr( $target ); ?>">

					<?php
					foreach ( $group_term_posts as $group_post ) {
						$active_class = ( get_permalink( $group_post ) === $current_page_url ) ? 'active' : '';
						?>

						<li class="nav-item w-100">
							<a href="<?php echo esc_url( get_permalink( $group_post ) ); ?>" class="nav-link <?php echo sanitize_html_class( $active_class ); ?>">
								<?php echo esc_html( get_the_title( $group_post->ID ) ); ?>
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
	if ( $view_all ) {
		echo '<a href="' . esc_url( $view_all_url ) . '" class="btn btn-primary my-3 w-100">View All</a>';
	}
	?>
 </div>

	<div class="results">
		<?php if ( ! is_single() ) : ?>
				<?php
				// loop through the groups.
				foreach ( $groups as $group ) {
					$group_term = $group['term'];
					$query      = $group['query'];
					?>

					<h1 class="col-12"><?php echo esc_html( $group_term->name ); ?></h1>

					<?php
					while ( $query->have_posts() ) {
						$query->the_post();

						$post_thumbnail = get_the_post_thumbnail( $query->post->ID, 'full', array( 'class' => 'img-fluid mx-auto d-block' ) );
						?>

						<div class="directory-image media-container">
							<a href="<?php echo esc_url( get_permalink( $query->post ) ); ?>">
								<?php echo wp_kses_post( $post_thumbnail ); ?>
							</a>

							<div class="directory-details">
								<div class="directory-name">
									<a href="<?php echo esc_url( get_permalink( $query->post ) ); ?>">
										<?php echo wp_kses_post( $query->post->name ); ?>
									</a>
								</div>

								<div class="directory-title">
									<?php echo wp_kses_post( $query->post->title ); ?>
								</div>

							</div>
						</div>
						<?php
					}
				}
				?>
			<?php
		else :
			$tagline  = get_post_meta( $query->post->ID, 'wpcd_contact_details_tagline', true );
			$location = get_post_meta( $query->post->ID, 'wpcd_contact_details_location', true );
			$email    = get_post_meta( $query->post->ID, 'wpcd_contact_details_email', true );
			$twitter  = get_post_meta( $query->post->ID, 'wpcd_contact_details_twitter', true );
			$linkedin = get_post_meta( $query->post->ID, 'wpcd_contact_details_linkedin', true );
			?>
			<main class="content col-lg-9 order-lg-last" id="genesis-content">
				<div class="row">

					<div class="media-container contact-image-container col-md-4">
						<?php echo get_the_post_thumbnail( $query->post->ID, 'full', array( 'class' => 'img-fluid mx-auto d-block' ) ); ?>

						<article class="post-<?php echo absint( $query->post->ID ); ?> contact type-contact status-publish format-standard has-post-thumbnail contact-group-consulting-services-team entry col-md-8" itemscope="" itemtype="https://schema.org/CreativeWork"><header class="entry-header clearfix">
							<div class="entry-header-container container">
								<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>

								<?php if ( ! empty( $tagline ) ) : ?>
									<p class="entry-meta"><?php echo esc_html( $tagline ); ?></p>
								<?php endif; ?>

							</div>

							</header>
								<div class="entry-content clearfix" itemprop="text">
									<section id="apl-content-0-content" class="content-wrap layer-wrap">
										<div class="container content-container">
											<div class="content-layer layer row">

												<div class="content-col col">
													<?php the_content(); ?>
												</div>

											</div>
										</div>
									</section>
								</div>
								<footer class="entry-footer clearfix">
									<div class="entry-footer-container container"></div>

								</footer>
						</article>
						<h2 class="screen-reader-text">Reader Interactions</h2>
					</div>

					</main>
		<?php endif; ?>
	</div>

<?php
get_footer();

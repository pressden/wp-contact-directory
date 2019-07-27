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

// Define args that will retrieve all children of the contact group taxonomy.
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

<div class="wpcd-wrap">
  <div class="wpcd-sidebar">
    <ul class="wpcd-nav wpcd-contact-groups-nav">

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

        <li>
          <a href="#<?php echo esc_attr( $target ); ?>" data-toggle="collapse" aria-expanded="<?php echo esc_attr( $expanded ); ?>">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
            <?php echo esc_html( $group_term->name ); ?>
          </a>

          <ul class="wpcd-nav wpcd-contact-group-nav <?php echo sanitize_html_class( $collapse ); ?>" id="<?php echo esc_attr( $target ); ?>">

            <?php
            foreach ( $group_term_posts as $group_post ) {
              $active_class = ( get_permalink( $group_post ) === $current_page_url ) ? 'active' : '';
              ?>

              <li>
                <a href="<?php echo esc_url( get_permalink( $group_post ) ); ?>" class="<?php echo sanitize_html_class( $active_class ); ?>">
                  <?php echo esc_html( get_the_title( $group_post->ID ) ); ?>
                </a>
              </li>

              <?php
            }
            ?>

          </ul><!-- .wpcd-nav .wpcd-contact-group-nav -->
        </li>

        <?php
      endforeach;
      ?>
    </ul><!-- .wpcd-nav .wpcd-contact-groups-nav -->

    <?php
    if ( $view_all ) {
      echo '<a href="' . esc_url( $view_all_url ) . '" class="wpcd-view-all">View All</a>';
    }
    ?>
  </div><!-- .wpcd-sidebar -->

	<main class="wpcd-content">
		<?php if ( ! is_single() ) : ?>
				<?php
				// loop through the groups.
				foreach ( $groups as $group ) {
					$group_term = $group['term'];
					$query      = $group['query'];
					?>

          <h1 class="wpcd-contact-group-title"><?php echo esc_html( $group_term->name ); ?></h1>

          <div class="wpcd-contact-group">

            <?php
            while ( $query->have_posts() ) {
              $query->the_post();

              $post_thumbnail = get_the_post_thumbnail( $query->post->ID, 'full' );
              $description  = get_post_meta( $query->post->ID, 'wpcd_contact_details_description', true );
              ?>

              <a href="<?php echo esc_url( get_permalink( $query->post ) ); ?>" class="wpcd-contact">
                <div class="wpcd-contact-image">
                  <?php echo wp_kses_post( $post_thumbnail ); ?>
                </div><!-- .wpcd-contact-image -->

                <div class="wpcd-details">
                  <div class="wpcd-name">
                    <?php echo wp_kses_post( $query->post->post_title ); ?>
                  </div><!-- .wpcd-name -->

                  <div class="wpcd-description">
                    <?php echo wp_kses_post( $description ); ?>
                  </div><!-- .wpcd-description -->
                </div><!-- .wpcd-details -->
              </a><!-- .wpcd-contact -->
              <?php
            }
            ?>

          </div><!-- .wpcd-contact-group -->

          <?php
				}
				?>
			<?php
		else :
			$description  = get_post_meta( $query->post->ID, 'wpcd_contact_details_description', true );
			$location     = get_post_meta( $query->post->ID, 'wpcd_contact_details_location', true );
			$email        = get_post_meta( $query->post->ID, 'wpcd_contact_details_email', true );
			$twitter      = get_post_meta( $query->post->ID, 'wpcd_contact_details_twitter', true );
			$linkedin     = get_post_meta( $query->post->ID, 'wpcd_contact_details_linkedin', true );
			?>

      <article id="post-<?php echo absint( $query->post->ID ); ?>" class="wpcd-contact">

        <div class="wpcd-contact-image">
          <?php echo get_the_post_thumbnail( $query->post->ID, 'full' ); ?>
        </div>

        <header class="entry-header clearfix">
          <h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>

          <?php if ( ! empty( $description ) ) : ?>
            <p class="entry-meta"><?php echo esc_html( $description ); ?></p>
          <?php endif; ?>
        </header>

        <div class="entry-content clearfix" itemprop="text">
          <?php the_content(); ?>
        </div>

        <footer class="entry-footer clearfix">

        </footer>

      </article><!-- .wpcd-contact -->

		<?php endif; ?>

	</main><!-- .wpcd-content -->
</div><!-- .wpcd-wrap -->

<?php
get_footer();

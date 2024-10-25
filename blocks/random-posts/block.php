<?php
$id = 'random-posts-' . $block['id'];

if( !empty($block['anchor']) ) {
  $id = $block['anchor'];
}

$className = '';

if( !empty($block['className']) ) {
  $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
  $className .= ' align' . $block['align'];
}

if( !empty( $block['data']['__is_preview'] ) ) {
	$className .= ' is-admin';

	echo '<img style="width: 100%; max-height: 100%;" src="'.get_template_directory_uri().'/blocks/random-posts/preview.png" alt="">';
	return;
}

$content = get_field('random_posts');

global $excluded_post_ids;
?>

<section
    id="<?php echo esc_attr($id); ?>"
    class="random-posts <?php echo esc_attr($className); ?>">
  <div class="random-posts__header">
	  <?php if ($content['title']) : ?>
    <div class="random-posts__title">
	    <?php echo $content['title']; ?>
    </div>
	  <?php endif; ?>
    
    <div class="random-posts__controls">
      <button class="js-refresh-posts">
        <img
            loading="lazy"
            width="275"
            height="200"
            src="<?php echo get_stylesheet_directory_uri(); ?>/images/icons/refresh.svg"
            alt="<?php echo __('Refresh posts', 'usm'); ?>">
      </button>
    </div>
  </div>
  
  <?php
  // get all categories ids
  $term_ids = array_map(function($term) {
	  return $term->term_id;
  }, $content['filter_by_category']);
  $term_id_string = implode(',', $term_ids);
  
  $args = array(
	  'post_type' => 'post',
	  'posts_per_page' => $content['posts_amount'],
	  'orderby' => 'rand',
	  'suppress_filters' => false,
	  'post__not_in' => $excluded_post_ids,
	  'tax_query' => array(
		  array(
			  'taxonomy' => 'category',
			  'field' => 'term_id',
			  'terms' => $term_ids,
			  'operator' => 'IN',
		  ),
	  ),
  );
  
  $query = new WP_Query($args);
  
  if ($query->have_posts()) :
  ?>
  
    <div
        data-posts-amount="<?php echo $content['posts_amount']; ?>"
        data-term-ids="<?php echo htmlspecialchars($term_id_string); ?>"
        class="random-posts__list js-random-posts-list">
      
      <?php
      while ($query->have_posts()) :
        $query->the_post();
	      
	      $excluded_post_ids[] = get_the_ID();
     
        get_template_part( 'template-parts/cards/new-card-rectangle', null, ['posts_amount' => $content['posts_amount']] );
        
      endwhile;
      ?>

    </div>
  
  <?php
  else :
	  
	  echo __('There are no posts in the specified categories.', 'usm');
  
  endif;
  
  wp_reset_postdata();
  ?>
  
</section>

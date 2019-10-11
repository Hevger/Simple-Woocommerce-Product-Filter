
<?php
// Add wp_ajax hook to our function
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

// Filter Function
function filter_products()
{
  // Get all products from clothes term as default tax query
  $tax_query   = array(
    'relation' => 'AND'
  );
  $tax_query[] = array(
    'taxonomy' => 'product_cat',
    'field' => 'slug',
    'terms' => 'clothes'
  );

  // Check if filter is set and isn't empty
  // Get filters and push each filter into tax query
  if (isset($_POST['filterArray']) && !empty($_POST['filterArray'])) {
    // Decode filter array
    $data = json_decode(stripslashes($_POST['filterArray']));
    foreach ($data as $key => $value) {
      $tax_query[] = array(
        'taxonomy' => $key,
        'field' => 'slug',
        'terms' => $value
      );
    }
  }

  // Query
  $the_query = new WP_Query(array(
    'post_type' => 'product',
    'post_per_page' => -1,
    'tax_query' => $tax_query
  ));

  // Handle query
  // Check if query returns any products
  if ($the_query->have_posts()) :
    while ($the_query->have_posts()) :
      $the_query->the_post();
      // Handle product data - you can use particle or use html/php
      echo "<br>" . get_the_title() . "<br>";
    endwhile;
  // if no products found based on our filters
  else :
    // Handle error message
    echo "No products found based on selected filters!";
  endif;

  // Don't forget to stop execution afterward
  wp_die();
}

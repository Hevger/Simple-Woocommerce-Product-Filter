<?php
// Get all terms in colors taxonomy
$colors = get_terms(array(
  'taxonomy' => 'colors'
));

// Get all terms in sizes taxonomy
$sizes = get_terms(array(
  'taxonomy' => 'sizes'
));
?>

<div class="container">
  <!-- Name should taxonomy as we are going to use it as array key -->
  <select id="filterSelect" name="colors">
    <option value="">All colors</option>
    <!-- Loop terms into select, set term slug as value and term name as option -->
    <?php foreach ($colors as $color) : ?>
      <option value="<?= $color->slug; ?>">
        <?= $color->name; ?>
      </option>
    <?php endforeach; ?>
  </select>

  <!-- Name should taxonomy as we are going to use it as array key -->
  <select id="filterSelect" name="sizes">
    <option value="">All sizes</option>
    <!-- Loop terms into select, set term slug as value and term name as option -->
    <?php foreach ($sizes as $size) : ?>
      <option value="<?= $size->slug; ?>">
        <?= $size->name; ?>
      </option>
    <?php endforeach; ?>
  </select>

  <div id="products"></div>
</div>

<script type="text/javascript">
  // WordPress AJAX processor
  var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
  jQuery(function($) {
    // Create empty array for filters
    var filters = {};

    // Default show all products - empty array is passed
    postData(filters);

    // On select change
    $('body').on('change', '#filterSelect', function() {
      // Get selected term
      var selectedFilterValue = $(this).val();
      // Get selected taxonomy
      var selectFilterName = $(this).attr('name');
      // Push above filter to filters array
      filters[selectFilterName] = selectedFilterValue;
      // Call postData function
      postData(filters);
    });

    function postData(filters) {
      // Set data
      var data = {
        action: 'filter_products',
        // Parse array to JSON
        filterArray: JSON.stringify(filters)
      }

      // Post data. Return and pust response into div with products as id
      $.post(ajaxurl, data, function(response) {
        $('#products').html(response);
      });
    }
  });
</script>
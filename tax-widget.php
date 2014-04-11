<?php
/*
Plugin Name: Taxonomy List
Description: Show taxonomies for current post, in a widget
*/

class tax_widget extends WP_Widget {
  function __construct() {
    parent::__construct(
      'tax_widget',
      __('Taxonomy List', 'taxwidget'),
      array('description' => __('Show taxonomies for current post, in a widget', 'taxwidget'),)
    );
  }

  public function widget($args, $instance) {
    global $post;
    $title = apply_filters('widget_title', $instance['title']);
    $post_terms = wp_get_object_terms($post->ID, $instance['taxonomy'],
                                      array('fields' => 'all_with_object_id'));

    echo $args['before_widget'];
    if (!empty($title)) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    echo '<p>' . $instance['description'] . '</p>';
    ?>
  <ul>
  <?php
    if (!empty($post_terms) && !is_wp_error($post_terms)) {
      foreach ($post_terms as $post_term) {
        echo '<li><a href="' . get_term_link($post_term) . '">' .  $post_term->name . '</a></li>';
      }
    } ?>
  </ul><?php
    echo $args['after_widget'];
  }

  public function form($instance) {
    if (isset($instance['title'])) {
      $title = $instance['title'];
    }
    else {
      $title = __('New title', 'taxwidget');
    }
    if (isset($instance['description'])) {
      $description = $instance['description'];
    } else {
      $description = 'Description';
    }
    $custom_taxonomies = get_taxonomies();
    if (isset($instance['taxonomy'])) {
      $taxonomy = $instance['taxonomy'];
    } else {
      $taxonomy = '';
    }
?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
      <input class="widefat" type="text"
        id="<?php echo $this->get_field_id('title');?>"
        name="<?php echo $this->get_field_name('title');?>"
        value="<?php echo esc_attr($title); ?>"></input>
      <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:') ?></label>
      <textarea class="widefat"
        id="<?php echo $this->get_field_id('description'); ?>"
        name="<?php echo $this->get_field_name('description'); ?>"
        ><?php echo esc_attr($description); ?></textarea>
      <select size="1"
        id="<?php echo $this->get_field_id('taxonomy'); ?>"
        name="<?php echo $this->get_field_name('taxonomy'); ?>"
        value="<?php echo esc_attr($taxonomy); ?>">
        <option></option>
      <?php foreach ($custom_taxonomies as $taxonomy_option) {
        if ($taxonomy_option == $taxonomy) {
          $selected = ' selected';
        } else { $selected = ''; }
        echo "<option" . $selected . ">" . $taxonomy_option . "</option>";
        }
      ?>
      </select>
    </p>
<?php
  }
  public function update($new_instance, $old_instance) {
    $instance = array();
    if (!empty($new_instance['title'])) {
      $instance['title'] = $new_instance['title'];
    } else {
      $instance['title'] = '';
    }
    if (!empty($new_instance['description'])) {
      $instance['description'] = $new_instance['description'];
    } else {
      $instance['description'] = '';
    }
    if (!empty($new_instance['taxonomy'])) {
      $instance['taxonomy'] = $new_instance['taxonomy'];
    } else  {
      $instance['taxonomy'] = '';
    }

    return $instance;
  }
}

function register_tax_widget() {
  register_widget('tax_widget');
}
add_action('widgets_init', 'register_tax_widget');

?>

<?php
/*
Plugin Name: Browse Tags Widget
Description: A simple widget that displays tags
Author: Chris Rudzki
*/


class browse_tags extends WP_Widget {

function __construct() {
	parent::__construct(
		'browse_tags',
		__('Browse Tags Widget', 'browse_tags'),
		array( 'description' => __( 'A simple widget that displays tags', 'browse_tags' ), )
	);
}

public function widget( $args, $instance ) {
	echo $args['before_widget'];
	if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
	}

	$term_args = array( 'hide_empty' => true );
	$terms = get_terms('post_tag', $term_args);
if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
    $term_list = '';
    $threshold_terms = array();
    foreach ($terms as $term) {
        if($term->count > 3) {
            $threshold_terms[] = $term;
        }
    }
    $i = 0;
    $count = count($threshold_terms);
    foreach ($threshold_terms as $term) {
            $i++;
            $term_list .= ' <a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all posts filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a>';
            if ( $count > $i ) {
                $term_list .= '<span class="tag-sep">&middot;</span> ';
            }
    }
    echo '<p class="tag-browse-list">' . $term_list . '</p>';
}
		echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php

}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
}

} // Class browse_tags ends here

function load_widget() {
	register_widget( 'browse_tags' );
}
add_action( 'widgets_init', 'load_widget' );

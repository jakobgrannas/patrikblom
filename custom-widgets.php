<?php
// Creating the widget 
class pb_widget extends WP_Widget {
	
	function pb_widget() {
		$widget_ops = array(
			'description' => __('Add footer text (or HTML) block with optional CSS classes', 'patrikblom'),
		);
		$this->WP_Widget('pb_widget', __('Footer block', 'patrikblom'), $widget_ops);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$text = apply_filters( 'widget_text', $instance['text']);
		$class_name = isset($instance['class_name']) ? $instance['class_name'] : '';
		$hide_title = isset($instance['hide_title']) ? $instance['hide_title'] : 0;
		
		//	Add custom classes to class list
		$class_names = ltrim($args['class'] . ' ' . $class_name . ' ');
		$before_widget = str_replace('class="', 'class="' . $class_names, $args['before_widget']);
		
		// before and after widget arguments are defined by themes
		echo $before_widget;
		if ( ! empty( $title ) && !$hide_title)
			echo $args['before_title'] . $title . $args['after_title'];
		if (!empty($text))
			echo $text;

		// This is where you run the code and display the output
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		$title = isset($instance['title']) ? $instance['title'] : '';
		$hide_title = isset($instance['hide_title']) ? $instance['hide_title'] : 0;
		$text = isset($instance['text']) ? $instance['text'] : '';
		$class_name = isset($instance['class_name']) ? $instance['class_name'] : '';
		$class_name_place_holder = __('Eg. myClass or myClass1 myClass2', 'patrikblom');
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
				   type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="1" <?php checked('1', $hide_title); ?> />
			<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e( 'Hide title' ); ?></label>
		</p>
		<p>
			<textarea class="widefat" rows="16" cols="16" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_attr( $text ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'class_name' ); ?>"><?php _e( 'CSS Classes (space separated):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'class_name' ); ?>" name="<?php echo $this->get_field_name( 'class_name' ); ?>"
				   type="text" value="<?php echo esc_attr( $class_name ); ?>" placeholder="<?php echo $class_name_place_holder ?>" />
		</p>
	<?php 
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? $new_instance['text'] : '';
		$instance['class_name'] = ( ! empty( $new_instance['class_name'] ) ) ? strip_tags( $new_instance['class_name'] ) : '';
		$instance['hide_title'] =  !empty( $new_instance['hide_title'] ) ? $new_instance['hide_title'] : 0;
		return $instance;
	}
}

// Register and load the widget
function pb_load_widget() {
	register_widget( 'pb_widget' );
}
add_action( 'widgets_init', 'pb_load_widget' );
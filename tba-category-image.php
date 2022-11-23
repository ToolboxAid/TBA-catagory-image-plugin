<?php
/*
Plugin Name: TBA Category Image Widget Plugin
Plugin URI: https://toolboxaid.com/create-widget-plugin-wordpress/
Description: This plugin adds a custom category image widget.
Version: 1.1
Author: David Quesenberry
Author URI: http://www.wpexplorer.com/create-widget-plugin-wordpress/
License: GPL2
*/

// The widget class
class TBA_Category_Image_Widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'tba_category_image_widget',
			__( 'TBA Category Image Widget', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )
	public function form( $instance ) {

		// Set widget defaults
		$defaults = array(
			'title'    => '',
			'text'     => '',
			'textarea' => '',
			'checkbox' => '',
			'select'   => '',
		);
		
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php // Text Field ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
		</p>

		<?php // Textarea Field ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>"><?php _e( 'Textarea:', 'text_domain' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'textarea' ) ); ?>"><?php echo wp_kses_post( $textarea ); ?></textarea>
		</p>

		<?php // Checkbox ?>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkbox' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>"><?php _e( 'Checkbox', 'text_domain' ); ?></label>
		</p>

		<?php // Dropdown ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Select', 'text_domain' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
			<?php
			// Your options array
			$options = array(
				''        => __( 'Select', 'text_domain' ),
				'option_1' => __( 'Option 1', 'text_domain' ),
				'option_2' => __( 'Option 2', 'text_domain' ),
				'option_3' => __( 'Option 3', 'text_domain' ),
			);

			// Loop through options and add each one to the select dropdown
			foreach ( $options as $key => $name ) {
				echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $select, $key, false ) . '>'. $name . '</option>';

			} ?>
			</select>
		</p>

	<?php }

	function get_categories_parent_id ($catid) {
	 while ($catid) {
		$cat = get_category($catid); // get the object for the catid
		$catid = $cat->category_parent; // assign parent ID (if exists) to $catid
		$catParent = $cat->cat_ID;
		}
	  echo $catParent;
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['text']     = isset( $new_instance['text'] ) ? wp_strip_all_tags( $new_instance['text'] ) : '';
		$instance['textarea'] = isset( $new_instance['textarea'] ) ? wp_kses_post( $new_instance['textarea'] ) : '';
		$instance['checkbox'] = isset( $new_instance['checkbox'] ) ? 1 : false;
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {

		extract( $args );

		// Check the widget options
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$text     = isset( $instance['text'] ) ? $instance['text'] : '';
		$textarea = isset( $instance['textarea'] ) ?$instance['textarea'] : '';
		$select   = isset( $instance['select'] ) ? $instance['select'] : '';
		$checkbox = ! empty( $instance['checkbox'] ) ? $instance['checkbox'] : false;

		$categories = get_the_category();

		// WordPress core before_widget hook (always include )
		echo $before_widget;

		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box tba_category_image_widget">';

			// Display widget title if defined
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			// Display text field
			if ( $text ) {
				echo '<p>' . $text . '</p>';
			}

			// Display textarea field
			if ( $textarea ) {
				echo '<p>' . $textarea . '</p>';
			}

			// Display select field
			if ( $select ) {
				echo '<p>' . $select . '</p>';
			}

			// Display something if checkbox is true
			if ( $checkbox ) {
				echo '<p>Something awesome</p>';
			}

			$my_cat="ToolBoxAid";			
			echo "<p>";
			if ( (! empty( $categories )) && $categories[0]->category_parent !== null) {

				$parent = $categories[0]->category_parent;

				if (!empty($parent)) {

					$my_cat=get_cat_name($parent );
				} else {

					$parent=$categories[0]->cat_name;
					$my_cat=$parent;
				}
			}
			
			echo "$my_cat";
			echo "</p>";

         	$htmlpath = '/wp-content/uploads/category/' .esc_html( strtolower($my_cat )) . '.png';
			$filepath = $_SERVER['DOCUMENT_ROOT'] . $htmlpath;

			if ( (! empty( $categories )) && file_exists($filepath) ) {
			    echo '<img width="345" height="225" src="';
			    echo $htmlpath;
			    echo '" alt="Parent Category: ' . $category . '">';
			    echo '<p>Parent Category: ';
				echo '<a href ="/category/' . $category . '">';
			    echo $category;
                echo '</a></p>';
			} else {
				echo '<img width="345" height="225" src="/wp-content/uploads/category/TBA.png" alt="Toolbox Aid">';
			}

		echo '</div>';

		// WordPress core after_widget hook (always include )
		echo $after_widget;

	}
}

// Register the widget
function register_tba_category_image_widget() {
	register_widget( 'TBA_Category_Image_Widget' );
}
add_action( 'widgets_init', 'register_tba_category_image_widget' );

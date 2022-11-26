<?php
/*
Plugin Name: TBA Category Image Widget
Plugin URI: https://toolboxaid.com/create-widget-plugin-wordpress/
Description: This plugin adds a custom category image widget.
Version: 1.1
Author: Mr Q
Author URI: http://toolboxaid.com/
License: GPL2
*/

// The widget class
// Creating the widget
class tba_category_image extends WP_Widget {
 
	function __construct() {
		parent::__construct(
	 
			'tba_category_image',                                         // Base ID of your widget
			__('TBA Category Image Widget', 'tba_category_image_domain'), // Widget name will appear in UI
		 
			// Widget description
			array( 'description' => __( 'This Toolbox Aid widget will display an image for a category', 'tba_category_image_domain' ), )
			);
	}

    // ------------------------------------------------------------------------------------------------------------------------------------
    // Widget Backend
	public function form( $instance ) {

       // Set widget defaults
        $defaults = array(
            'title'         => 'Toolbox Aid',
            'defaultImage'  => 'TBA',
            'debugPath'     => true,
        );

        // Parse current settings with defaults
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
		
		<?phpi // Widget Title ?>        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'defaultImage' ) ); ?>"><?php _e( 'Default Image', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'defaultImage' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'defaultImage' ) ); ?>" type="text" value="<?php echo esc_attr( $defaultImage ); ?>" />
        </p>

		<p>
			 <input class="checkbox" type="checkbox" <?php checked( $instance[ 'debugPath' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'debugPath' ); ?>" name="<?php echo $this->get_field_name( 'debugPath' ); ?>" /> 
		     <label for="<?php echo $this->get_field_id( 'debugPath' ); ?>">Show Image Path</label>
		</p>

        <?php
    }

	// ------------------------------------------------------------------------------------------------------------------------------------	 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title        = apply_filters( 'widget_title', $instance['title'] );
        $defaultImage = apply_filters( 'defaultImage', $instance['defaultImage'] );
		$debugPath    = $instance[ 'debugPath' ] ? 'true' : 'false';
	
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		if ( ! empty( $title ) ){
			echo $args['before_title'] . $title . $args['after_title'];
		}

		//======================================================
        $categories = get_the_category();
        $my_cat="";
        if ( (! empty( $categories )) && $categories[0]->category_parent !== null) {

            $parent = $categories[0]->category_parent;

            if (!empty($parent)) {
                $my_cat=get_cat_name($parent );
            } else {
                $parent=$categories[0]->cat_name;
                $my_cat=$parent;
            }
        }

        $htmlpath = '/wp-content/uploads/category/' . esc_html( strtolower($my_cat )) . '.png';
        $filepath = $_SERVER['DOCUMENT_ROOT'] . $htmlpath;

		if ( (! empty( $categories )) && file_exists($filepath) ) {
			// all is good
		} else {
	        if ( ! empty( $defaultImage ) ){
				//echo '<p>' . $defaultImage . '</p>';
	            $htmlpath = '/wp-content/uploads/category/' . $defaultImage . '.png';
			} else {
				$htmlpath = '/wp-content/uploads/category/TBA.png';
			}

            $filepath = $_SERVER['DOCUMENT_ROOT'] . $htmlpath;
		}

        echo '<img width="345" height="225" src="';
        echo $htmlpath;
		echo '" alt="Parent Category: ' . $my_cat . '" alt="Toolbox Aid" >';

		echo '<p>&nbsp;&nbsp;Parent Category: ';
        echo '<a href ="/category/' . $my_cat . '">' . $my_cat .'</a>';
        echo '</p>';

		//=====================================================

        if (  ! empty( $debugPath ) && $debugPath == 'true' ) {
			echo '<p>&nbsp;&nbsp;Image Path: ';
            echo $filepath . '</p>';
		}

		echo $args['after_widget'];
		}

    // ------------------------------------------------------------------------------------------------------------------------------------
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
		$instance['title']        = ( ! empty( $new_instance['title'] ) )        ? strip_tags( $new_instance['title'] )        : 'TBA';
        $instance['defaultImage'] = ( ! empty( $new_instance['defaultImage'] ) ) ? strip_tags( $new_instance['defaultImage'] ) : 'Toolbox Aid';
        $instance['debugPath'] = isset( $new_instance['debugPath'] ) ? $new_instance['debugPath'] : false;

		return $instance;
		}
	 
} // Class tba_category_image ends here
	 
    // ------------------------------------------------------------------------------------------------------------------------------------
	// Register and load the widget
	function tba_category_image_widget() {
	    register_widget( 'tba_category_image' );
	}

	add_action( 'widgets_init', 'tba_category_image_widget' );


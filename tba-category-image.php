<?php
/*
Plugin Name: TBA Category Image Widget
Plugin URI: https://toolboxaid.com/create-widget-plugin-wordpress/
Description: This plugin adds a custom category image widget.
Version: 1.3
Author: Mr Q
Author URI: http://toolboxaid.com/
License: GPL2
*/

// The widget class
// Creating the widget
class tba_category_image extends WP_Widget {

	const CATEGORY_DIR = '/category/';

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
	    'defaultImage'  => 'tba.png',
        );

        // Parse current settings with defaults
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
		
	<?php // Widget Title ?>        

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
            <input class="input" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'defaultImage' ) ); ?>"><?php _e( 'Default Image', 'text_domain' ); ?></label>
        </p>
	<p>
	<?php 
	    $upload_dir = wp_upload_dir( null, false, false ); // Array of key => value pairs
	    echo 'Image directory  ' . $upload_dir['basedir'] . self::CATEGORY_DIR . '<br />';
	    ?>
	    <input class="input" id="<?php echo esc_attr( $this->get_field_id( 'defaultImage' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'defaultImage' ) ); ?>" type="text" value="<?php echo esc_attr( $defaultImage ); ?>" />
		* image should be a 'png' file </br>
		* use slug naming convention. ie:</br>
			- image name must be lowercase</br>
			- spaces in image name must be replaced with a dash '-'</br>
			- example 'my test category' must be saved as 'my-test-category.png'</br>
        </p>

	<?php
	}
	
    // ------------------------------------------------------------------------------------------------------------------------------------
    // Creating widget front-end
    public function widget( $args, $instance ) {
        try {
            $title        = apply_filters( 'widget_title', $instance['title'] );
            $defaultImage = apply_filters( 'defaultImage', $instance['defaultImage'] );
        } catch (Exception $e) {
            $title = 'tba - change me and save me';
            $defaultImage = "tba.png";
        }

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        if ( ! empty( $title ) ){
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $category = get_the_category();
        $category_parent_id = $category[0]->category_parent;
        if ( $category_parent_id != 0 ) {
            $category_parent = get_term( $category_parent_id, 'category' );
            $my_cat_name = $category_parent->name;
            $my_cat_slug = $category_parent->slug;
        } else {
            $my_cat_name = $category[0]->name;
            $my_cat_slug = $category[0]->slug;
        }

        $uploadsInfo = wp_upload_dir( null, false, false );  // don't create the yyyy/mm directory
        $filepath = $uploadsInfo['basedir'] . self::CATEGORY_DIR;
        $htmlpath = $uploadsInfo['baseurl'] . self::CATEGORY_DIR;

        $showCat=false;

        $imagePath = $filepath .  strtolower($my_cat_slug ) . '.png';
        $altText = $title ;
        if ( file_exists($imagePath) ) { // category image found, show it
            $htmlpath = $htmlpath .  strtolower($my_cat_slug ) . '.png';
            $altText = "Parent Category: " . $my_cat_slug;
            $showCat=true;
        } else {
            $imagePath = $filepath .  strtolower($defaultImage);
            if ( file_exists($imagePath) ){ // Default image found, show it
                $htmlpath = $htmlpath .  strtolower($defaultImage);
            } else { // fall back to TBA image.
                $htmlpath = plugin_dir_path( __FILE__ ) . '/assets/tba.png';
            }
        }

        echo '<img width="345" height="225" src="' . $htmlpath . '" alt="' . $altText . '" >';

        if ( $showCat ) {
            echo '<p>&nbsp;&nbsp;Parent Category: ';
            #echo '<a href ="' . self::CATEGORY_DIR . $my_cat_slug . '">' . $my_cat_slug .'</a>';
            echo '<a href ="/category/' . $my_cat_slug . '">' . $my_cat_name .'</a>';
            echo '</p>';
        } else {
            echo '<p>&nbsp;</p>';
        }

        echo $args['after_widget'];
    }


    // ------------------------------------------------------------------------------------------------------------------------------------
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']        = ( ! empty( $new_instance['title'] ) )        ? strip_tags( $new_instance['title'] )        : 'Toolbox Aid';
        	$instance['defaultImage'] = ( ! empty( $new_instance['defaultImage'] ) ) ? strip_tags( $new_instance['defaultImage'] ) : 'tba.png';

        	$upload_dir = wp_upload_dir();
        	$catDir = $upload_dir['basedir'] . self::CATEGORY_DIR;
		if ( ! file_exists($catDir) ) {
			wp_mkdir_p($catDir);
		}

		return $instance;
	}	
	 
} // Class tba_category_image ends here
	 
    // ------------------------------------------------------------------------------------------------------------------------------------
	// Register and load the widget
	function tba_category_image_widget() {
	    register_widget( 'tba_category_image' );
	}

	add_action( 'widgets_init', 'tba_category_image_widget' );

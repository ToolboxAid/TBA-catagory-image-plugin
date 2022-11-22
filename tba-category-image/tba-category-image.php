<?php
/*
Plugin Name: TBA Image by Category Widget Plugin
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
			'category_image_widget',
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
		echo '<div class="widget-text wp_widget_plugin_box category_image_widget">';

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


$nnn="ToolBoxAid";			
echo "<p>";
#try{
if ($categories[0]->category_parent !== null) {

	$parent = $categories[0]->category_parent;

#}catch(Exception $e){
#    $nnn="abc";
#}

    if (!empty($parent)) {
#    	echo "parent:";
#    	echo $parent;
    	$nnn=get_cat_name($parent );
    } else {
#    	echo "cat_name:";
    #	echo $categories[0]->cat_name;
    	$parent=$categories[0]->cat_name;
#    	echo $parent;
    	$nnn=$parent;
    }
}
#echo " - N: ";

echo "$nnn";
echo "</p>";
#------------------------------------------------------------------------------------
/*
                        echo "<p> post id: ";

			$post = get_post();
			echo $post->ID;
                        echo "</p>";


            global $wp;

			echo "<p> req: ";
			echo "$wp->request";
			echo "</p>";

			// Since slugs itself can't contain slashes,
			// let's explode on slashes and get just the last portion.
			$request_args = explode('/', $wp->request);
			$current_slug = end($request_args);
			echo "<p> args: ";
			echo "$request_args[0]";
			echo "</p>";
			echo "<p> slug: ";
			echo "$current_slug";
			echo "</p>";

			$categories = wp_get_post_categories($post->ID);

			$category= '';
			foreach($categories as $childcat) {
#				get_categories_parent_id ($childcat);
                $this_category = get_category($childcat);
				echo "<p>$childcat";

#				cur_cat_id = get_query_var($childcat);
#				echo "$cur_cat_id";

#				$findme = $this_category-ID;
#                echo " - ";
#                echo "$findme";

				echo " - ";
				$findme=$this_category->name;
				$lookin=$wp->request;
#				$pos = stripos($mystring1, $findme);
                echo "find: $findme ";
#				echo "$pos";
                echo "</p>";
#				$parentcat = $childcat->category_parent;
#				if($parentcat>0){
#					$category = get_cat_name($parentcat);
#					continue;
#				 }
			}

#$catid=74;
# while ($catid) {
#  $cat = get_category($catid); // get the object for the catid
#  $catid = $cat->category_parent; // assign parent ID (if exists) to $catid
#  $catParent = $cat->cat_ID;
# }
#echo $catParent;

$findme    = 'c';
$mystring1 = 'xyz';
$mystring2 = 'ABC';

$pos1 = stripos($mystring1, $findme);
$pos2 = stripos($mystring2, $findme);
// Nope, 'a' is certainly not in 'xyz'
if ($pos1 === false) {
    echo "<p>The string '$findme' was not found in the string '$mystring1'</p>";
}

// Note our use of ===.  Simply == would not work as expected
// because the position of 'a' is the 0th (first) character.
if ($pos2 !== false) {
    echo "<p>We found '$findme' in '$mystring2' at position $pos2</p>";
}



/*

1512
11 – Health
12 – Nutrition
req: blog-category/nutrition
args: blog-category
slug: nutrition

https://toolboxaid.com/blog-category/nutrition/

11 – Health
req: blog-category/health
args: blog-category
slug: health

677
11 – Health
req: health/booty-cream
args: health
slug: booty-cream

665
2 – Career
15 – Personal
req: blog-category/personal
args: blog-category
slug: personal

665
2 – Career
15 – Personal
req: career/best-career-advice
args: career
slug: best-career-advice
 
381
15 – Personal
req: personal/8-simple-rules
args: personal
slug: 8-simple-rules 
 
665
2 – Career
15 – Personal
req: blog-category/personal
args: blog-category
slug: personal

 
 
 */

#$string = 'The lazy fox jumped over the fence';
#
#if (str_contains($string, 'lazy')) {
#    echo "The string 'lazy' was found in the string\n";
#}
#
#if (str_contains($string, 'Lazy')) {
#    echo 'The string "Lazy" was found in the string';
#} else {
#    echo '"Lazy" was not found because the case does not match';
#}			


#			foreach(wp_get_post_categories($post->ID) as $childcat) {
#			   	$parentcat = get_category($childcat);;  
#				echo get_cat_name($parentcat);
#				echo $parentcat->term_id;
#			}   
#	        echo $post->post_parent->cat_ID; 


#			foreach ( get_categories() as $category ) :
#			     echo $category->name;
#			endforeach;			

#            try{
#                $category = (strlen($category)>0)? $category :  $categories[0]->cat_name;
#            } catch (Exception $e) {
#                $category = "Other";
#            }

			//============================================
	$htmlpath = '/wp-content/uploads/category/' .esc_html( strtolower($nnn )) . '.png';
#            $htmlpath = '/wp-content/uploads/category/' .esc_html( strtolower($category )) . '.png';
			$filepath = $_SERVER['DOCUMENT_ROOT'] . $htmlpath;

#			if (file_exists($filepath)) {
#			   echo "The file $filepath exists";
#			} else {
#			   echo "The file $filepath does not exist";
#			}
			
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
function my_register_custom_widget() {
	register_widget( 'TBA_Category_Image_Widget' );
}
add_action( 'widgets_init', 'my_register_custom_widget' );

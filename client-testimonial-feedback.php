<?php
/**
 * Plugin Name: Client Testimonials Feedback
 * Author: Anil Ankola
 * Version: 2.2
 * Description: A WordPress client testimonial feedback plugin created by Anil Ankola for your website Dispaly Client Testimonial.
 * Text Domain: client-testimonial
*/
if(!defined('ABSPATH')) exit; // Prevent Direct Browsing

// CSS and JS include
function ctf_wp_include_script_style() {
	wp_enqueue_script('jquery' );
    wp_enqueue_style( 'ctf-slick-css', plugins_url('/include/css/testimonial-slick.css', __FILE__) );
	wp_enqueue_style( 'ctf-styles-css', plugins_url('/include/css/styles.css', __FILE__) );
    wp_enqueue_script( 'ctf-slick-js', plugins_url('/include/js/testimonial-slick.js', __FILE__) );
	wp_enqueue_script( 'ctf-functions-js', plugins_url('/include/js/functions.js', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'ctf_wp_include_script_style' );

function ctf_wp_ecpt_create_post_type() {
	$labels = array(
		'name'                => _x( 'Testimonial', 'Post Type General Name', 'client-testimonial' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'client-testimonial' ),
		'menu_name'           => esc_html__( 'Testimonial', 'client-testimonial' ),
		'parent_item_colon'   => esc_html__( 'Parent Testimonial', 'client-testimonial' ),
		'all_items'           => esc_html__( 'All Testimonial', 'client-testimonial' ),
		'view_item'           => esc_html__( 'View Testimonial', 'client-testimonial' ),
		'add_new_item'        => esc_html__( 'Add New Testimonial', 'client-testimonial' ),
		'add_new'             => esc_html__( 'Add New', 'client-testimonial' ),
		'edit_item'           => esc_html__( 'Edit Testimonial', 'client-testimonial' ),
		'update_item'         => esc_html__( 'Update Testimonial', 'client-testimonial' ),
		'search_items'        => esc_html__( 'Search Testimonial', 'client-testimonial' ),
		'not_found'           => esc_html__( 'Not Found', 'client-testimonial' ),
		'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'client-testimonial' ),
	);
	$args = array(
		'label'               => esc_html__( 'testimonial', 'client-testimonial' ),
		'description'         => esc_html__( 'Testimonial ', 'client-testimonial' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-format-status',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
	);
	register_post_type( 'testimonial', $args );
}
add_action( 'init', 'ctf_wp_ecpt_create_post_type');

add_filter( 'enter_title_here', 'ctf_wp_custom_enter_title' );
function ctf_wp_custom_enter_title( $input ) {
    if ( 'testimonial' === get_post_type() ) {
        return __( 'Testimonial author name here', 'client-testimonial' );
    }
    return $input;
}
// admin menu
function ctf_wp_create_menus() {
	add_submenu_page("edit.php?post_type=testimonial", "Settings", "Settings", "administrator", "testimonial-setting", "ctf_add_page");
}
add_action("admin_menu", "ctf_wp_create_menus");

function ctf_add_page(){
	global $wpdb;
	$message = '';
	if(isset($_POST['submit'])) 
	{
		if(!wp_verify_nonce('testmonial_setting_submit_nonce','testmonial_setting_submit'))
		{		
			$testimonial_heading_title= sanitize_text_field( $_POST['testimonial_heading_title'] );
			$background_color= sanitize_text_field( $_POST['background_color'] );
			$author_title_color= sanitize_text_field( $_POST['author_title_color'] );
			$text_color = sanitize_text_field( $_POST['text_color'] );
			
			if(!empty($_POST['select_style'])) {
				$select_style = sanitize_text_field( $_POST['select_style'] );
			}
			$saved= sanitize_text_field( $_POST['saved'] );
			if(isset($testimonial_heading_title) ) {
				update_option('testimonial_heading_title', $testimonial_heading_title);
			}
			if(isset($background_color) ) {
				update_option('background_color', $background_color);
			}
			if(isset($author_title_color) ) {
				update_option('author_title_color', $author_title_color);
			}
			if(isset($text_color) ) {
				update_option('text_color', $text_color);
			}
			if(isset($select_style) ) {
				update_option('select_style', $select_style);
			}	
			if($saved==true) {
				$message='saved';
			} 
		}
	}
	if ( $message == 'saved' ) {
		echo ' <div class="updated settings-error"><p><strong>Settings Saved.</strong></p></div>';
	}
	?>
	<div class="wrap testimonial-setting">
		<form method="post" id="whoisSettingForm" action="">
		<h2><?php echo esc_html__('Client Testimonials Setting','client-testimonial');?></h2>
			<table class="form-table">
				<h3>Use this shortcode [client-testimonial-feedback] show the testimonial.</h3>
				<tr valign="top">
					<th scope="row" style="width: 370px;">
						<label><?php echo esc_html__('Heading','client-testimonial');?></label>
					</th>
					<td>
					<input name="testimonial_heading_title" type="text" value="<?php echo esc_html__(get_option('testimonial_heading_title'),'client-testimonial');?>"  />
					</td>
				</tr>
				 <tr valign="top">
					<th scope="row" style="width: 370px;">
						<label><?php  echo esc_html__('Select Testimonial Type','client-testimonial');?></label>
					</th>
					<td>
						<input type="radio" name="select_style" value="testimonial_slider" <?php if(get_option('select_style')=='testimonial_slider'){echo esc_html__('checked','client-testimonial');}?>> Slider &nbsp; 
						<input type="radio" name="select_style" value="testimonial_listing" <?php if(get_option('select_style')=='testimonial_listing'){echo esc_html__('checked','client-testimonial');}?>> Listing
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 370px;">
						<label><?php  echo esc_html__('Background Color','client-testimonial');?></label>
					</th>
					<td>
					<input type="text" name="background_color" value="<?php echo get_option('background_color');?>" class="wp-color-picker-field" data-default-color="#ffffff" >
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 370px;">
						<label><?php  echo esc_html__('Author Title Color','client-testimonial');?></label>
					</th>
					<td>
					<input type="text" name="author_title_color" value="<?php echo get_option('author_title_color');?>" class="wp-color-picker-field" data-default-color="#000000" >
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 370px;">
						<label><?php  echo esc_html__('Text Color','client-testimonial');?></label>
					</th>
					<td>
					<input type="text" name="text_color" value="<?php echo get_option('text_color');?>" class="wp-color-picker-field" data-default-color="#000000" >
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="hidden" name="saved" value="saved"/>
				<input type="submit" name="submit" class="button-primary" value="Save Changes" />
				<?php wp_nonce_field( 'testmonial_setting_submit', 'testmonial_setting_submit_nonce' );?>
			</p> 
		</form>
	</div>
	<?php
}

add_action( 'admin_enqueue_scripts', 'ctf_wp_enqueue_color_picker' );
function ctf_wp_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', plugins_url('include/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Add Admin CSS
add_action('admin_head', 'ctf_wp_admin_css');
function ctf_wp_admin_css() {
  wp_enqueue_style( 'wp-admin-css', plugins_url('include/css/admin.css', __FILE__ ),false , '1.0', 'all' );
}

// Add Custom meta box
function add_post_meta_boxes() {
	add_meta_box('client_position_box', 'Client Designation', 'client_position_box', 'testimonial', 'normal', 'default');
}
add_action( "admin_init", "add_post_meta_boxes" );

function save_post_meta_boxes(){
    global $post;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    }
    update_post_meta( $post->ID, "client_position", sanitize_text_field( $_POST[ "client_position" ] ) );
}
add_action( 'save_post', 'save_post_meta_boxes' );

function client_position_box(){
    global $post;
   $custom = get_post_custom( $post->ID );
    $client_position = $custom[ "client_position" ][ 0 ];?>
    <input type="text" id="client_position" name="client_position" value="<?php echo $client_position; ?>">
    <?php    
}

//Client Testimonial Shortcode
add_shortcode( 'client-testimonial-feedback', 'ctf_wp_custom_shortcode' );
function ctf_wp_custom_shortcode( $atts ) {
    ob_start();
	
	//get saved settings
	$testimonial_heading_title= get_option('testimonial_heading_title'); 
    $background_color= get_option('background_color'); 
	$author_title_color= get_option('author_title_color'); 
    $text_color= get_option('text_color'); 
	$select_style= get_option('select_style');
	
	//default color settings
	if($background_color == ''){ $background_color='#ffffff'; }
	if($background_color == '#ffffff'){ $border_color='#000000'; }else{$border_color=$background_color; }
	if($author_title_color == ''){ $author_title_color='#000000'; }
	if($text_color == ''){ $text_color='#000000'; }
	?>
	<style>
	.testimonial-slider { background: <?php echo $background_color;?>; }
	.ctesti-author, .ctesti-list-author, .ctesti-author-postion, .ctesti-list-author-postion{color: <?php echo $author_title_color;?>;}
	.ctesti-text, .ctesti-list-text{color: <?php echo $text_color;?>;}	
	.ctesti-list-item{background: <?php echo $background_color;?>; border-color: <?php echo $border_color;?>;}
	</style>
	<?php	
    $query_options = new WP_Query(array(	
		'post_type' => 'testimonial',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order'=> 'DESC',
	));
    if ( $query_options->have_posts() ) { ?>
    	<div class="Testimonial-main <?php echo $select_style;?>">
        	<?php if(!empty($testimonial_heading_title)){?>
        		<div class="testimonial-title"><?php echo esc_html__($testimonial_heading_title,'client-testimonial');?></div>
            <?php } 
			if($select_style == 'testimonial_listing'){?>
                <div class="testimonial-list">
					<?php while ( $query_options->have_posts() ) : $query_options->the_post();
                    $img_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full');
                    $cposition = get_post_meta(get_the_ID(),'client_position', true);
                    $getContent = strip_tags(get_the_content());
					$dir_file = plugin_dir_url( __FILE__ );
                    ?>
                    <div class="ctesti-list-item">
                        <div class="ctesti-list-left">
                        	<div class="ctesti-list-image">
								<?php if(!empty(has_post_thumbnail())){
                                    the_post_thumbnail( array( 200, 200 ) );
                                }else{?>
                                    <img src="<?php echo $dir_file . 'include/images/author-balnk.jpg';?>" alt="<?php the_title();?>"/>
                                <?php } ?>
                            </div>
                        </div>                       
                        <div class="ctesti-list-content">                        	
                            <div class="ctesti-list-text"><?php echo $getContent;?></div>
                            <div class="ctesti-list-author"><?php the_title();?></div>
                            <div class="ctesti-list-author-postion"><?php echo $cposition;?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php }else{ ?>
                <div class="testimonial-slider">
                    <?php while ( $query_options->have_posts() ) : $query_options->the_post();
                    $img_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full');
                    $cposition = get_post_meta(get_the_ID(),'client_position', true);
                    $getContent = strip_tags(get_the_content());
					$dir_file = plugin_dir_url( __FILE__ );
                    ?>
                    <div class="ctesti-item">
                        <div class="ctesti-image">
                        	<?php if(!empty(has_post_thumbnail())){
								the_post_thumbnail( array( 150, 150 ) );
							}else{?>
                            	<img src="<?php echo $dir_file . 'include/images/author-balnk.jpg';?>" alt="<?php the_title();?>"/>
                            <?php } ?>
                        </div>
                       <div class="ctesti-content">
                            <div class="ctesti-text"><?php echo $getContent;?></div>                            
                            <div class="ctesti-author"><?php the_title();?></div>
                            <div class="ctesti-author-postion"><?php echo $cposition;?></div>
                        </div>
                    </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php } ?>
        </div>
    	<?php $myvariable = ob_get_clean();
    	return $myvariable;
    }else{
		echo 'NO Client Testimonial Feedback!!!';
	}
}
?>
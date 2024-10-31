<?php

/*
Plugin Name: MW Team Gallery
Plugin URI: http://Maniwebify.com/mw-team-gallery/
Description: Description: This plugin allows users to add their team profile in wordpress theme and manage their infomation. use Thumbnail picture, Destination, Description, tags, categories. very easy to use and add/edit/remove member profile etc.
Author: Maniwebify
Version: 1.0
Author URI: https://Maniwebify.com/
License: GPLv2 or later
Text Domain: Maniwebify.com

*/


//================== ADD CUSTOM POST TYPE=============================//
function mwtg_team() {

	$labels = array(
		'name'                  => __( 'Teams', 'Post Type General Name', 'ec' ),
		'singular_name'         => __( 'Team', 'Post Type Singular Name', 'ec' ),
		'menu_name'             => __( 'Team Member', 'ec' ),
		'name_admin_bar'        => __( 'Team Member', 'ec' ),
		'archives'              => __( 'Member Archives', 'ec' ),
		'attributes'            => __( 'Member Attributes', 'ec' ),
		'parent_item_colon'     => __( 'Parent Member:', 'ec' ),
		'all_items'             => __( 'All Member', 'ec' ),
		'add_new_item'          => __( 'Add New Member', 'ec' ),
		'add_new'               => __( 'Add Member', 'ec' ),
		'new_item'              => __( 'New Member', 'ec' ),
		'edit_item'             => __( 'Edit Member', 'ec' ),
		'update_item'           => __( 'Update Member', 'ec' ),
		'view_item'             => __( 'View Member', 'ec' ),
		'view_items'            => __( 'View Member', 'ec' ),
		'search_items'          => __( 'Search Member', 'ec' ),
		'not_found'             => __( 'Not found', 'ec' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ec' ),
		'featured_image'        => __( 'Featured Image', 'ec' ),
		'set_featured_image'    => __( 'Set featured image', 'ec' ),
		'remove_featured_image' => __( 'Remove featured image', 'ec' ),
		'use_featured_image'    => __( 'Use as featured image', 'ec' ),
		'insert_into_item'      => __( 'Insert into Member', 'ec' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Member', 'ec' ),
		'items_list'            => __( 'Members list', 'ec' ),
		'items_list_navigation' => __( 'Members list navigation', 'ec' ),
		'filter_items_list'     => __( 'Filter Members list', 'ec' ),
	);
	$args = array(
		'label'                 => __( 'Team', 'ec' ),
		'description'           => __( 'Post Type Description', 'ec' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', '' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon' 			=> 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'mwtg_team', $args );

}


//================== ADD CUSTOM INPUT=============================//

function mwtg_destination_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function mwtg_destination_add_meta_box() {
	add_meta_box(
		'destination-destination',
		__( 'Destination', 'destination' ),
		'mwtg_destination_html',
		'mwtg_team',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mwtg_destination_add_meta_box' );

function mwtg_destination_html( $post) {
	wp_nonce_field( '_destination_nonce', 'destination_nonce' ); ?>

	<p>
		<label for="destination_destination"><?php _e( 'Destination', 'destination' ); ?></label><br>
		<input type="text" name="destination_destination" id="destination_destination" value="<?php echo mwtg_destination_get_meta( 'destination_destination' ); ?>">
	</p><?php
}

function mwtg_destination_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['destination_nonce'] ) || ! wp_verify_nonce( $_POST['destination_nonce'], '_destination_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['destination_destination'] ) )
		update_post_meta( $post_id, 'destination_destination', sanitize_text_field( $_POST['destination_destination'] ) );
}

add_action( 'save_post', 'mwtg_destination_save' );

add_action( 'init', 'mwtg_team', 0 );


//================== SHORT CODE=============================//

add_shortcode('mw_team_gallery', 'mw_team_shortcode');
function mw_team_shortcode($atts, $content){

	?>

	<style type="text/css">


#ec-team-bar ::-webkit-scrollbar {
  width: 10px;
  background:transparent;
}

/* Track */
#ec-team-bar ::-webkit-scrollbar-track {
  border:1px solid #ccc; 
  border-radius: 0px;
}
 
/* Handle */
#ec-team-bar ::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.1); 
  border-radius: 0px;
}

		.ec-team-members{
	max-width:1450px;
	width:100%;
	margin:0px auto;
	display:inline-block;
	transition: all 0.3s ease-out !important;
}

.ec-team-single{
	max-width:300px;
	display:block;
	float: left;
    margin-left: 60px;
    margin-bottom: 50px;
	transition: all 0.3s ease-out !important;
}
				
.ec-top{
	position:relative;
	height:200px !important;
	background:#eeee;
	transition: all 0.3s ease-out !important;
	cursor: pointer;
}

.ec-top .ec-img{
	position:absolute;
	top: 0px;
	display: block;
	height:100%;
	width:100%;
	transition: all 0.3s ease-out !important;
	
}

.ec-top .ec-img2{
	position:absolute;
	top: 0px;
	height:100%;
	width:100%;
	display:none;
	transition: all 0.3s ease-out !important;

}


.ec-top .ec-team-title{
	color:#fff;
	position:absolute;
	left:15px;
	bottom:10px;
	font-size: 22px;
	transition: all 0.3s ease-out !important;
	
	
}
.ec-top .ec-team-title::after{
	content:"";
	display:block;
	width:40px;
	height:2px;
	background:white;
	margin-top:2px;
	transition: all 0.3s ease-out !important;
	
}
.ec-top .ec-team-des{
	color:#fff;
	position:absolute;
	left:15px;
	bottom:50px;
	display:none;
	transition: all 0.3s ease-out !important;
	font-size:15px;
	
}

.ec-top:hover .ec-team-des{
	display:block;
	
}
.ec-top:hover .ec-team-title{
	transform:translateY(-105px);
}
.ec-top:hover .ec-img2{
	display:block;
}

.ec-bottom{
	margin-top:15px;
	font-size: 14px;
	transition: all 0.3s;
	overflow: auto;
    height: 200px;
}
		.ec-overlay{
			background:rgba(0,0,0,0.4);
			z-index:0;
			position:absolute;
			top: 0px;
			height:100%;
			width:100%;
			display:block;
			transition: all 0.3s ease-out !important;
		}
		
	@media only screen and (max-width: 1400px) {
  .ec-team-members{
  	width:100%;
  }
  .ec-team-single{
  	width:250px ;
	
  }

}
		
	/* Custom CSS Insert here*/

	<?php echo sanitize_text_field( get_option('customcss') ); ?>	

	</style>
<div class="ec-team-members row" id="ec-team-bar">

	<?php
  

  global $post;

   $options = array (
    'post_type' => 'mwtg_team',
    'orderby'   => 'ID',
        'order' => 'ASC',
	   'nopaging' => true
);

  $posts = new WP_Query($options);
  $output = '';
	$sno = 1;
    if ($posts->have_posts())
        while ($posts->have_posts()):
            $posts->the_post();
           
?>

	<?php
		 $options = get_option( 'profile-link' );
		  @$checked = $options['checked'];
		  if($checked == 1){
			
		 echo '<a href="'.get_post_permalink($post->ID).'">';
	?>
		<div class="ec-team-single cols-lg-4 <?php echo $sno; ?>">
			<div class="ec-top">
				<?php $thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
				<img class="ec-img" src="<?php echo $thumbnail; ?>">
				<?php $post_thumbnail_url = wp_get_attachment_url( get_post_meta($post->ID, 'second_featured_image', true)); ?>
				<img class="ec-img2" src="<?php echo $post_thumbnail_url; ?>">
				<div class="ec-overlay"></div>
				<h3 class="ec-team-title"><?php echo get_the_title(); ?></h3>
				<h5 class="ec-team-des"><?php echo get_post_meta($post->ID, 'destination_destination', true); ?></h5>
			</div>
			<div class="ec-bottom"><?php echo get_the_content(); ?></div>
			
		</div>
		<?php
		echo '</a>';

		}else{	
		?>
	<div class="ec-team-single cols-lg-4 <?php echo $sno; ?>">
			<div class="ec-top">
				<?php $thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
				<img class="ec-img" src="<?php echo $thumbnail; ?>">
				<?php $post_thumbnail_url = wp_get_attachment_url( get_post_meta($post->ID, 'second_featured_image', true)); ?>
				<img class="ec-img2" src="<?php echo $post_thumbnail_url; ?>">
				<div class="ec-overlay"></div>
				<h3 class="ec-team-title"><?php echo get_the_title(); ?></h3>
				<h5 class="ec-team-des"><?php echo get_post_meta($post->ID, 'destination_destination', true); ?></h5>
			</div>
			<div class="ec-bottom"><?php echo get_the_content(); ?></div>
			
		</div>

<?php
		} //else end
	$sno++;
    endwhile;
  else
    return; // no posts found
?>
</div>
	<?php
  wp_reset_query();
  return html_entity_decode($out);
}


//===================HOVER IMG =================//


    


function mwtg_custom_postimage_meta_box(){

    //on which post types should the box appear?
    
		add_meta_box('mwtg_custom_postimage_meta_box',__( 'Hover Images', 'yourdomain'),'mwtg_custom_postimage_meta_box_func','mwtg_team','side','low');
    
}

function mwtg_custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in mwtg_custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!='' ? wp_get_attachment_image_src( $image_meta_val[0]) : ''); ?>" style="width:100%;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" onclick="mwtg_custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('add image','yourdomain'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="mwtg_custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('remove image','yourdomain'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function mwtg_custom_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image','yourdomain'); ?>',
            button: {
                text: '<?php _e('select image','yourdomain'); ?>'
            },
            multiple: false
        });
        custom_postimage_uploader.on('select', function() {

            var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        custom_postimage_uploader.on('open', function(){
            var selection = custom_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        custom_postimage_uploader.open();
        return false;
    }

    function mwtg_custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'mwtg_custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function mwtg_custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'mwtg_custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}

add_action( 'add_meta_boxes', 'mwtg_custom_postimage_meta_box' );
	add_action( 'save_post', 'mwtg_custom_postimage_meta_box_save' );

	//Register Setting Options

	add_action( 'admin_init', 'mwtg_register_mysettings' );

function mwtg_register_mysettings() { // whitelist options
	register_setting( 'mw-team-options', 'customcss' );
	register_setting( 'mw-team-options', 'profile-link' );
	
  }
	
	// Add MW Options Page

	add_action('admin_menu', 'mwtg_menu_options');
 
function mwtg_menu_options(){

	add_submenu_page( 'edit.php?post_type=mwtg_team', "Options", 'Options', 'manage_options', 'mw-plugin-options', 'mwtg_test_init','dashicons-option' );
}
 
function mwtg_test_init(){
        ?>
		
		<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>MW Team Gallery</h2></div>

		<h3>Usage</h3>
		<label>Use shortcode: </label><input type="text" value="[mw_team_gallery]" size="30">
		
		

		
		<form method="post" action="options.php">
		<?php settings_fields( 'mw-team-options' ); ?>
   		 <?php do_settings_sections( 'mw-team-options' ); ?> 

			
	<?php
			// Get an array of options from the database.
 $options = get_option( 'profile-link' );
 
// Get the value of this option.
 @$checked = $options['checked'];
 
// The value to compare with (the value of the checkbox below).
$current = 1; 
 
// True by default, just here to make things clear.
$echo = true;
 
?>
<h3>Options</h3>
<label>Open individual Profile</label>
<input type="checkbox" name="profile-link[checked]" value="1"
    <?php checked( $checked, $current, $echo ); ?>/>
		
	<h3>Custom CSS</h3>
		<textarea name="customcss" style="width:50%; height:400px; resize:none;"><?php echo sanitize_text_field( get_option('customcss') ); ?></textarea>
		<?php submit_button(); ?>
		</form>
		<div id="footer" style="bottom: 100px;position: fixed;background: #ccc;width: 100%;PADDING: 10px;text-align: center;">Develop by <a href="https://maniwebify.com/">Maniwebify</a></div>

<?php
}
?>
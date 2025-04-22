<?php
if(!defined('WPINC')){
    exit("you can't access this file directly");
}

class GalleriaX {
    public function init(){
        //action hooks
        add_action('init', array($this,'register_post_type')); // to add a custom content type
        add_action('add_meta_boxes',array($this,'addMetaBox')); // to add a custom editor box for extra data (images)
        add_action('save_post', array($this, 'saveMetaBox')); // to save the added data when the post is saved
        add_shortcode('galleriax', array($this, 'renderGalleryShortcode')); //short code for each gallery

    }
    public function saveMetaBox($post_id) {
        if (!current_user_can('edit_post', $post_id)) return; // checks if the current user has permission to edit the post
        if (isset($_POST['galleriax_image_ids'])) {
            $image_ids = $_POST['galleriax_image_ids'];
            update_post_meta($post_id, '_galleriax_image_ids', $image_ids);//saves the data to the database
        }
    }
    public function addMetaBox(){
        add_meta_box(
            'GalleriaX meta box',
            'GalleriaX gallery',
            array($this,'renderMetaBox'),
            'GalleriaX',
            'normal',
            'high'
        );
    }
    public function renderMetaBox($post){
        $image_ids = get_post_meta($post->ID, '_galleriax_image_ids', true);//get the saved images by their id
        $ids = explode(',', $image_ids); // the ids is returned in a form of string so this function turns it into array
    //Loop to display each image
    foreach ($ids as $id) {
        if ($id) {
            $img_url = wp_get_attachment_url($id);
            echo '<div class="gallery-item">';
            echo '<img src="'.esc_url($img_url).'" />';
            echo '<p class="remove-image">Remove Image</p>';
            echo '</div>';
        }
    }

    //display the shortcode of the gallery to be used later
    echo '<div style="margin-top: 20px; padding: 10px; background: #f1f1f1; border-left: 4px solid #6082B6;">';
    echo '<strong>Shortcode:</strong><br>';
    echo '<code>[galleriax id="' . esc_attr($post->ID) . '"]</code>';
    echo '</div>';
    ob_start();
    include_once GalleriaX_directory .'/templates/add-images.php';
    $output = ob_get_clean();
    echo $output;
    }
    public function register_post_type(){
        $labels=[
            'name'=>('GalleriaX'),
            'singular_name'=>('Galleriax'),
            'menu_name' =>('GalleriaX'),
            'name_admin_bar'=>('GalleriaX Gallery'),
            'add_new_item'=> ('Add New Gallery Item'),
            'add_new' =>('Add New Gallery'),
            'new_item' =>('New Gallery Item'),
            'edit_item' =>('Edit Gallery Item'),
            'view_item'=>('View Gallery Item'),
            'all_items'=>('All Gallery Items'),
            'search_items'=>('Search Gallery Item'),
            'parent_item_colon'=>('Parent Gallery Item:'),
            'not_found'=>('Not found'),
            'not_found_in_trash'=>('Not found in Trash'),
        ];
        $args = [
            'label'=> 'GalleriaX',
            'labels' => $labels,
            'description' => 'A modern and responsive gallery plugin for WordPress',
            'show_ui' => true,
            'supports' =>['title'],
        ];
        register_post_type('GalleriaX',$args);
    }
    //defining a function to make the users able to insert a gallery into any page or post through a shortcode 
    public function renderGalleryShortcode($atts){
        $atts = shortcode_atts(array(
            'id' => ''
        ), $atts);
    
        $post_id = intval($atts['id']);
    
        if (!$post_id) return '';
    
        $image_ids = get_post_meta($post_id, '_galleriax_image_ids', true);
        if (!$image_ids) return '<p>No images found in this gallery.</p>';
    
        $ids = explode(',', $image_ids);
        $output = '<div class="galleriax-frontend-gallery" style="display: flex; flex-wrap: wrap; gap: 10px;">';
    
        foreach ($ids as $id) {
            $img_url = wp_get_attachment_url($id);
            if ($img_url) {
                $output .= '<div style="width: 150px;"><img src="'.esc_url($img_url).'" style="width: 100%; border-radius: 5px;" /></div>';
            }
        }
    
        $output .= '</div>';
        return $output;
    }

}

$init = new Galleriax();
$init->init();
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
});

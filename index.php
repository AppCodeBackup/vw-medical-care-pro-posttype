<?php 
/*
 Plugin Name: VW Medical Care Pro Posttype
 Plugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Medical Care Pro Theme.
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_MEDICAL_CARE_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'vw_medical_care_pro_posttype_create_post_type' );

function vw_medical_care_pro_posttype_create_post_type() {
  register_post_type( 'department',
    array(
        'labels' => array(
            'name' => __( 'Department','vw-medical-care-pro-posttype' ),
            'singular_name' => __( 'Department','vw-medical-care-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'doctors',
    array(
        'labels' => array(
            'name' => __( 'Doctors','vw-medical-care-pro-posttype-pro' ),
            'singular_name' => __( 'Doctors','vw-medical-care-pro-posttype-pro' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'clients',
  array(
    'labels' => array(
      'name' => __( 'Clients','vw-medical-care-pro-posttype-pro' ),
      'singular_name' => __( 'Clients','vw-medical-care-pro-posttype-pro' )
      ),
    'capability_type' => 'post',
    'menu_icon'  => 'dashicons-businessman',
    'public' => true,
    'supports' => array(
      'title',
      'editor',
      'thumbnail'
      )
    )
  );
  
}
// --------------- Department ------------------
// Serives section
function vw_medical_care_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('vw-medical-care-pro-posttype-pro-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'vw_medical_care_pro_posttype_images_metabox_enqueue');
// Department Meta
function vw_medical_care_pro_posttype_bn_custom_meta_department() {

    add_meta_box( 'bn_meta', __( 'Department Meta', 'vw-medical-care-pro-posttype-pro' ), 'vw_medical_care_pro_posttype_bn_meta_callback_department', 'department', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_medical_care_pro_posttype_bn_custom_meta_department');
}

function vw_medical_care_pro_posttype_bn_meta_callback_department( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_img = get_post_meta( $post->ID, 'meta-img', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_attr($meta_img); ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}


function vw_medical_care_pro_posttype_bn_meta_save_department( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  
}
add_action( 'save_post', 'vw_medical_care_pro_posttype_bn_meta_save_department' );

/* Department shortcode */
function vw_medical_care_pro_posttype_department_func( $atts ) {
  $department = '';
  $department = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'department') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=department');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $department_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-department-url',true !='')){$custom_url =get_post_meta($post_id,'meta-department-url',true); } else{ $custom_url = get_permalink(); }
        $department .= '<div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="department-box">
                          <div class="depart-img">
                              <img src="'.esc_url($thumb_url).'" />
                          </div>
                          <div class="department-img">
                            <div class="department-meta">
                              <img class="" src="'.esc_url($department_image).'">
                             </div>
                          </div>
                        </div>
                        <div class="row department-data">
                          <div class="col-lg-9 col-md-9">
                            <h3 class="department-title"><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h3>
                          </div>
                          <div class="col-lg-3 col-md-3">
                          <a href="'.esc_url($custom_url).'"><span class="department-icon"><i class="fa fa-angle-right"></i></span> .</a>
                       
                          </div>  
                        </div>
                     </div>';


    if($k%2 == 0){
      $department.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $department = '<h2 class="center">'.esc_html__('Post Not Found','vw-medical-care-pro-posttype').'</h2>';
  endif;
  $department .= '</div>';
  return $department;
}

add_shortcode( 'list-department', 'vw_medical_care_pro_posttype_department_func' );


/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function vw_medical_care_pro_posttype_bn_testimonial_meta_box() {
  add_meta_box( 'vw-medical-care-pro-posttype-testimonial-meta', __( 'Enter Designation', 'vw-medical-care-pro-posttype' ), 'vw_medical_care_pro_posttype_bn_testimonial_meta_callback', 'clients', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_medical_care_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function vw_medical_care_pro_posttype_bn_testimonial_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'vw_medical_care_pro_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_desigstory', true );
  $facebook = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_facebookurl', true );
  $twitter = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_twitterurl', true );
  $googleplus = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_googleplusurl', true );
  $pinterest = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_pinteresturl', true );
  $instagram = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_instagramurl', true );
  $linkedin = get_post_meta( $post->ID, 'vw_medical_care_pro_posttype_testimonial_linkedinurl', true );
  ?>
  <div id="clients_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
                  <?php _e( 'Designation', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="vw_medical_care_pro_posttype_testimonial_desigstory" id="vw_medical_care_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
                  <?php _e( 'Facebook Url', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_facebookurl" id="vw_medical_care_pro_posttype_testimonial_facebookurl" value="<?php echo esc_url($facebook); ?>" />
          </td>
        </tr>
        <tr id="meta-5">
          <td class="left">
            <?php esc_html_e( 'Twitter Url', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_twitterurl" id="vw_medical_care_pro_posttype_testimonial_twitterurl" value="<?php echo esc_url( $twitter); ?>" />
          </td>
        </tr>
        <tr id="meta-6">
          <td class="left">
            <?php esc_html_e( 'GooglePlus URL', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_googleplusurl" id="vw_medical_care_pro_posttype_testimonial_googleplusurl" value="<?php echo esc_url($googleplus); ?>" />
          </td>
        </tr>
        <tr id="meta-7">
          <td class="left">
            <?php esc_html_e( 'Pinterest URL', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_pinteresturl" id="vw_medical_care_pro_posttype_testimonial_pinteresturl" value="<?php echo esc_url($pinterest); ?>" />
          </td>
        </tr>
        <tr id="meta-8">
          <td class="left">
            <?php esc_html_e( 'Instagram URL', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_instagramurl" id="vw_medical_care_pro_posttype_testimonial_instagramurl" value="<?php echo esc_url($instagram); ?>" />
          </td>
        </tr>

         <tr id="meta-9">
          <td class="left">
            <?php esc_html_e( 'Linkedin URL', 'vw-medical-care-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_medical_care_pro_posttype_testimonial_linkedinurl" id="vw_medical_care_pro_posttype_testimonial_linkedinurl" value="<?php echo esc_url($linkedin); ?>" />
          </td>
        </tr>
        
      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function vw_medical_care_pro_posttype_bn_metadesig_save( $post_id ) {
  if (!isset($_POST['vw_medical_care_pro_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_medical_care_pro_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Save desig.
  if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_desigstory' ] ) ) {
    update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_medical_care_pro_posttype_testimonial_desigstory']) );
  }
   // Save facebookurl
    if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_facebookurl' ] ) ) {
        update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_facebookurl', esc_url_raw($_POST[ 'vw_medical_care_pro_posttype_testimonial_facebookurl' ]) );
    }
    // Save twitterurl  
    if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_twitterurl' ] ) ) {
        update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_twitterurl', esc_url_raw($_POST[ 'vw_medical_care_pro_posttype_testimonial_twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_googleplusurl', esc_url_raw($_POST[ 'vw_medical_care_pro_posttype_testimonial_googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_pinteresturl', esc_url_raw($_POST[ 'vw_medical_care_pro_posttype_testimonial_pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'vw_medical_care_pro_posttype_testimonial_instagramurl' ] ) ) {
        update_post_meta( $post_id, 'vw_medical_care_pro_posttype_testimonial_instagramurl', esc_url_raw($_POST[ 'vw_medical_care_pro_posttype_testimonial_instagramurl' ]) );
    }

}

add_action( 'save_post', 'vw_medical_care_pro_posttype_bn_metadesig_save' );

/* clients shortcode */
function vw_medical_care_pro_posttype_clients_func( $atts ) {
  $testimonial = '';
  $testimonial = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'clients') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=clients');

  while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
        $post_id = get_the_ID();
        $designation= get_post_meta($post_id,'meta-designation',true);
        $facebookurl= get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_facebookurl',true);
        $twitter=get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_twitterurl',true);
        $googleplus=get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_googleplusurl',true);
        $pinterest=get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_pinteresturl',true);
        $instagram=get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_instagramurl',true);
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $desigstory= get_post_meta($post_id,'vw_medical_care_pro_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
          <div class="col-lg-4 col-sm-6 col-md-6 clients-box-info">
            <div class="row">
              <div class="col-lg-12 col-md-12 clients-class">
                <div class="testimonial-data-srtcd">
                  <div class="clients-img-srtcd">
                    <img src="'.esc_url($thumb_url).'" />
                  </div>
                  <div class="clients-icon-srtcd"><i class="fa fa-quote-left"></i></div>
                  <div class="clients-info">'.$excerpt.'</div>
                  <h5 class="testimonial-title"> <a href="'.$custom_url.'">'.esc_html(get_the_title()) .'</a></h5>
                  <span class="t-desig">'
                    .$desigstory.
                  '</span>
                </div>
                <div class="fb_socialbox">';
                    if($facebookurl != ''){
                      $testimonial .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                    } if($twitter != ''){
                      $testimonial .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                    } if($googleplus != ''){
                      $testimonial .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                    } if($pinterest != ''){
                      $testimonial .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                    }if($instagram != ''){
                      $testimonial .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                    }
                       $testimonial .= '</div>
              </div>
            </div>
          </div>';
           
    if($k%3 == 0){
      $testimonial.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $testimonial = '<h2 class="center">'.esc_html__('Post Not Found','vw-medical-care-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'vw-medical-care-pro-clients', 'vw_medical_care_pro_posttype_clients_func' );

// ------------------- Our Doctors --------------------

function vw_medical_care_pro_posttype_bn_designation_meta() {

  add_meta_box( 'vw_medical_care_pro_posttype_bn_meta', __( 'Enter Designation','vw-medical-care-pro-posttype-pro' ), 'vw_medical_care_pro_posttype_bn_meta_callback', 'doctors', 'normal', 'high' );
}

// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_medical_care_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function vw_medical_care_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_medical_care_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $pinterest = get_post_meta( $post->ID, 'meta-pinteresturl', true );
    $designation = get_post_meta( $post->ID, 'meta-designation', true );
    $email = get_post_meta( $post->ID, 'meta-leader-email', true );
    $call = get_post_meta( $post->ID, 'meta-leader-call', true );
    $facebookurl = get_post_meta( $post->ID, 'meta-facebookurl', true );
    $instagram = get_post_meta( $post->ID, 'meta-instagramurl', true );
    $googleplus = get_post_meta( $post->ID, 'meta-googleplusurl', true );
    $twitter = get_post_meta( $post->ID, 'meta-twitterurl', true );
    ?>
    <div id="doctors_custom_stuff">
        <table id="list-table">         
          <tbody id="the-list" data-wp-lists="list:meta">
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Designation', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                 <td class="left" >
                  <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($designation); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Email', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-leader-email" id="meta-leader-email" value="<?php echo esc_attr($email); ?>" />
                </td>
              </tr>
               <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Phone', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-leader-call" id="meta-leader-call" value="<?php echo esc_attr($call); ?>" />
                </td>
              </tr>
              <tr id="meta-3">
                <td class="left">
                  <?php esc_html_e( 'Facebook Url', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($facebookurl); ?>" />
                </td>
              </tr>
              <tr id="meta-5">
                <td class="left">
                  <?php esc_html_e( 'Twitter Url', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-twitterurl'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-6">
                <td class="left">
                  <?php esc_html_e( 'GooglePlus URL', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_attr($googleplus); ?>" />
                </td>
              </tr>
              <tr id="meta-7">
                <td class="left">
                  <?php esc_html_e( 'Pinterest URL', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-pinteresturl" id="meta-pinteresturl" value="<?php echo esc_attr($pinterest); ?>" />
                </td>
              </tr>
              <tr id="meta-8">
                <td class="left">
                  <?php esc_html_e( 'Instagram URL', 'vw-medical-care-pro-posttype-pro' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-instagramurl" id="meta-instagramurl" value="<?php echo esc_attr($instagram); ?>" />
                </td>
              </tr>
              
          </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function vw_medical_care_pro_posttype_bn_metadesig_doctors_save( $post_id ) {
  
    
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-pinteresturl', esc_url_raw($_POST[ 'meta-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-instagramurl', esc_url_raw($_POST[ 'meta-instagramurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }

    // Save Email
    if( isset( $_POST[ 'meta-leader-email' ] ) ) {
        update_post_meta( $post_id, 'meta-leader-email', sanitize_text_field($_POST[ 'meta-leader-email' ]) );
    }
    // Save Call
    if( isset( $_POST[ 'meta-leader-call' ] ) ) {
        update_post_meta( $post_id, 'meta-leader-call', sanitize_text_field($_POST[ 'meta-leader-call' ]) );
    }
}
add_action( 'save_post', 'vw_medical_care_pro_posttype_bn_metadesig_doctors_save' );

/* doctors shorthcode */
function vw_medical_care_pro_posttype_doctors_func( $atts ) {
    $doctors = ''; 
    $custom_url ='';
    $doctors = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'doctors' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=doctors'); 
    while ($new->have_posts()) : $new->the_post();
      $post_id = get_the_ID();
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      $url = $thumb['0'];
      $excerpt = wp_trim_words(get_the_excerpt(),25);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      $pinterest=get_post_meta($post_id,'meta-pinteresturl',true);
      $instagram=get_post_meta($post_id,'meta-instagramurl',true);
      $call=get_post_meta($post_id,'meta-leader-call',true);
      $email=get_post_meta($post_id,'meta-leader-email',true);
      $doctors .= '<div class="doctors_box col-lg-4 col-md-6 col-sm-6">
                    <div class="row doctor-class">
                      <div class="image-box col-lg-3">
                        <img class="client-img" src="'.esc_url($thumb_url).'" alt="doctors-thumbnail" />
                      </div>
                      <div class="content_box w-100 float-left col-lg-9">
                        <div class="doctors-box w-100 float-left">
                          <h4 class="doctors_name"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
                          <p class="designation">'.esc_html($designation).'</p>
                        </div>
                        <div class="short_text">'.$excerpt.'</div>
                        <div class="about-socialbox">
                           
                          <div class="inst_socialbox">';
                            if($facebookurl != ''){
                              $doctors .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                            } if($twitter != ''){
                              $doctors .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                            } if($googleplus != ''){
                              $doctors .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                            } if($linkedin != ''){
                              $doctors .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                            }if($pinterest != ''){
                              $doctors .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                            }if($instagram != ''){
                              $doctors .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                            }
                          $doctors .= '</div>
                        </div>
                        <p>'.$call.'</p>
                        <p>'.$email.'</p>
                      </div>
                    </div>
                </div>';

      if($k%2 == 0){
          $doctors.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $doctors.= '</div>';
  else :
    $doctors = '<h2 class="center">'.esc_html_e('Not Found','vw-medical-care-pro-posttype-pro').'</h2>';
  endif;
  return $doctors;
}
add_shortcode( 'vw-medical-care-pro-doctors', 'vw_medical_care_pro_posttype_doctors_func' );

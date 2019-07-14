<?php
/**
 * Functions of reviews
 *
 * @package SimpleLeads
 * @since 1.0.0
 */

/*===========================================================================================================================*/


/**
 * Add post type "simple_lead_review"
 */
function sl_review() {
    register_post_type( 'sl_review', array(
        'public'     => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'labels'     => array(
            'name'                => 'Отзывы',
            'all_items'           => 'Все отзывы',
            'add_new'          => 'Добавить отзыв',
            'add_new_item' => 'Добавить новый отзыв',
        ),
        'menu_icon'                   => 'dashicons-thumbs-up',
        'public'                           => false,
        'publicly_queryable'      => true,
        'exclude_from_search' => true,
        'show_ui'                      => true,
        'query_var'                   => false,
        'rewrite'                        => false,
    ) );
}
add_action( 'init', 'sl_review' );


/**
 * Extra fields for reviews
 */
function sl_review_review_fields()
{
    add_meta_box( 'sl_extra_fields', 'Информация об отзыве', 'sl_review_fields_box_func', 'sl_review', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sl_review_review_fields', 1 );


function sl_review_fields_box_func( $post ) {
    ?>
    <div class="simple-leads-admin-fields">
       
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title">ФИО клиента: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="reviewname" value="<?php echo get_post_meta( $post->ID, 'reviewname', 1 ); ?>"></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title">Город: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="reviewcity" value="<?php echo get_post_meta( $post->ID, 'reviewcity', 1 ); ?>" /></div>
        </div>
        
        <div>
            <div class="simple-leads-admin-field-title">Пол: </div>
            <div class="simple-leads-admin-field-input">
                <?php
                    $male = '';
                    $female = '';
                    $gender = get_post_meta( $post->ID, 'reviewgender', 1 );
                    if($gender == 'male') {
                        $male = 'checked';
                    } elseif($gender == 'female') {
                        $female = 'checked';
                    }
                ?>
                <div class="checkbox-options">
                    <label><input name="reviewgender" type="radio" value="male" <?php echo $male; ?> >Мужчина</label>
                </div>
                <div class="checkbox-options">
                    <label><input name="reviewgender" type="radio" value="female" <?php echo $female; ?>> Женщина</label>
                </div>
            </div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-field-title" style="text-align:left;padding:5px;">Дополнительные сведения (не публикуются):</div>
            <textarea type="text" name="reviewdescription" style="width:100%;height:150px;"><?php echo get_post_meta( $post->ID, 'reviewdescription', 1 ); ?></textarea>
        </div>
        
    </div>

    <input type="hidden" name="sl_review_fields_nonce" value="<?php echo wp_create_nonce( __FILE__ ); ?>" />

    <?php
}


/**
 * Save extra fields
 */
function sl_review_fields_update( $post_id ) {
    
    if ( !isset( $_POST['sl_review_fields_nonce'] ) || !wp_verify_nonce( $_POST['sl_review_fields_nonce'], __FILE__ ) ) {
        return false;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return false;
    }
    
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return false;
    }
    
    delete_post_meta( $post_id, 'reviewgender' );
    
    foreach( $_POST as $key => $value ) {
        if ( empty( $value ) ) {
            delete_option( $key );
            continue;
        }
        
        if ( is_string( $value ) ) {
            $value = htmlspecialchars( $value );
        } 
        update_post_meta( $post_id, $key, $value );
    }
    
    return $post_id;
}
add_action( 'save_post', 'sl_review_fields_update', 0 );


/**
 *  Simple review handler
 */
function sl_review_new_review() {
    
    wp_parse_str( $_POST['itemsdata'], $fields );

    if ( empty( $fields ) ) {
        $fields = $_POST;
        $pst = true;
      //  simple_lead_validation( $fields );
    }

    $arrdata = array();

    if ( !isset( $fields ) ) {
        exit;
    }
    
    foreach( $fields as $key => $value ) {
        $prefix = substr( $key, 0, 6);
        
        if ( empty( $value ) || $prefix != 'review' || $key == 'reviewtext' ) {
            continue;
        }
       
        if ( is_string( $value ) ) {
            $value = trim( htmlspecialchars( $value ) );
        }
                
        

        $arrdata[$key] = $value;
    }

    global $wpdb;
    $posts = $wpdb->get_col( "SELECT ID FROM ".$wpdb->prefix."posts ORDER BY ID ASC" );

    $post_data = array(
        'post_title'     => 'Отзыв',
        'post_type'      => 'sl_review',
        'post_content'   => $fields['reviewtext'],
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'post_status'    => 'pending',
        'post_author'    => 1,
        'meta_input'     => $arrdata,
    );
    $post_id = wp_insert_post( wp_slash( $post_data ) ); 
    
    if ( $_FILES ) {
		if ( $_FILES['photo'] ) {
			$attach_id = media_handle_upload( 'photo', $post_id );
			set_post_thumbnail( $post_id, $attach_id );
			unset( $_FILES['photo'] );
		}
    }

    if ( $pst ) {
        header( 'Location: '.site_url( '/thank-you-review/' ) );
        exit;
    }
   
    $text = '<div class="thanks_popup">
        <div class="thanks_popup_text">'.get_option( "thanks_text" ).'</div></div>';
    
    echo $text;
    exit;
}
add_action( "wp_ajax_sl_review_new_review", "sl_review_new_review" );
add_action( "wp_ajax_nopriv_sl_review_new_review", "sl_review_new_review" );
add_action( "admin_post_nopriv_sl_review_new_review", "sl_review_new_review" );
add_action( "admin_post_sl_review_new_review", "sl_review_new_review" );


/**
 * Modify reviews table
 */
function sl_review_add_columns_title( $columns )
{
    $columns['text'] = 'Текст отзыва';
    $columns['info'] = 'Информация об отзыве';
    return $columns;
}
add_filter( 'manage_edit-sl_review_columns', 'sl_review_add_columns_title' );

function sl_review_add_columns( $column )
{
    global $post;
    switch ( $column ) {
            case 'text':
            $review = get_post( $post->ID );
            $content = $review->post_content;
            echo $content ? $content: '';
        break;
        case 'info':
            echo ( $name = get_post_meta( $post->ID, 'reviewname', 1 ) ) ? "ФИО: $name<br>" : '';
            echo ( $city = get_post_meta( $post->ID, 'reviewcity', 1 ) ) ? "Город: $city<br>" : '';
            echo ( $text = get_post_meta( $post->ID, 'reviewdescription', 1 ) ) ? "Дополнительные сведения: $text<br>" : '';
        break;
    }
}
add_action( 'manage_posts_custom_column', 'sl_review_add_columns' );


/**
 * Query for search in admin-panel
 */
function sl_review_search_query( $query )
{
	global $pagenow;
	if ( 'edit.php' === $pagenow && 'sl_review' === $_GET['post_type'] ) {
	    $custom_fields = array(
	        "reviewname",
	        "reviewcity",
	        "reviewdescription"
	    );
	    $searchterm = $query->query_vars['s'];

	    $query->query_vars['s'] = "";

	    if ( "" != $searchterm ) {
	        $meta_query = array( 'relation' => 'OR' );
	        foreach( $custom_fields as $cf ) {
	            array_push( $meta_query, array(
	                'key' => $cf,
	                'value' => $searchterm,
	                'compare' => 'LIKE'
	            ) );
	        }
	        $query->set( "meta_query", $meta_query );
	    };
	}
}
add_filter( "pre_get_posts", "sl_review_search_query" );
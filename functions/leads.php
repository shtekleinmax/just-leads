<?php
/**
 * Functions of plugine
 *
 * @package SimpleLeads
 * @since 1.0.0
 */

/*===========================================================================================================================*/


/**
 * Add post type "simple_lead"
 */
function simple_lead() {
    register_post_type( 'simple_lead', array(
        'public'     => true,
        'supports' => array( 'title' ),
        'labels'     => array(
            'name'                => 'Заявки',
            'all_items'           => 'Все заявки',
            'add_new'          => 'Добавить заявку',
            'add_new_item' => 'Добавить новую заявку',
        ),
        'menu_icon'                   => 'dashicons-welcome-write-blog',
        'public'                           => false,
        'publicly_queryable'      => true,
        'exclude_from_search' => true,
        'show_ui'                      => true,
        'query_var'                   => false,
        'rewrite'                        => false,
    ) );
}
add_action( 'init', 'simple_lead' );


/**
 * Add lead statuses
 */
function simple_lead_add_statuses() {
    
    register_post_status( 'new', array(
        'label'                     => 'Новая',
        'label_count'          => _n_noop( 'Новая <span class="count">(%s)</span>', 'Новые <span class="count">(%s)</span>' ),
        'exclude_from_search'       => true,
        'public'                    => true,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
    ) );
    
    register_post_status( 'performed', array(
        'label'                      => 'В работе',
        'label_count'           => _n_noop( 'В работе <span class="count">(%s)</span>', 'В работе <span class="count">(%s)</span>' ),
        'exclude_from_search'       => true,
        'public'                    => true,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
    ) );

    register_post_status( 'finished', array(
        'label'                      => 'Выполена',
        'label_count'           => _n_noop( 'Выполнена <span class="count">(%s)</span>', 'Выполнены <span class="count">(%s)</span>' ),
        'exclude_from_search'       => true,
        'public'                    => true,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
    ) );

    register_post_status( 'unfinished', array(
        'label'                      => 'Не выполнена',
        'label_count'           => _n_noop( 'Не выполнена <span class="count">(%s)</span>', 'Не выполнены <span class="count">(%s)</span>' ),
        'exclude_from_search'       => true,
        'public'                    => true,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
    ) );
    
}
add_action( 'init', 'simple_lead_add_statuses' );


/**
 * Add entry statuses to status list
 */
function simple_lead_new_status_list() {
    global $post;
    $new_selected = '';
    $payment_selected = '';
    $performed_selected = '';
    $finished_selected = '';
    $unfinished_selected = '';
    if ( 'simple_lead' == $post->post_type ) {

        if ( 'new' == $post->post_status ) {
            $new_selected = ' selected="selected"';
            $statusname = "$( '#post-status-display' ).text( 'Новая' );";
        }

        if ( 'performed' == $post->post_status ) {
            $performed_selected = ' selected="selected"';
            $statusname = "$( '#post-status-display' ).text( 'В работе' );";
        }

        if ( 'finished' == $post->post_status ) {
            $finished_selected = ' selected="selected"';
            $statusname = "$( '#post-status-display' ).text( 'Выполнена' );";
        }

        if ( 'unfinished' == $post->post_status ) {
            $unfinished_selected = ' selected="selected"';
            $statusname = "$( '#post-status-display' ).text( 'Не выполнена' );";
        }

        echo "
            <style>
                #save-action input {
                    background: #0085ba;
                    border-color: #0073aa #006799 #006799;
                    -webkit-box-shadow: 0 1px 0 #006799;
                    box-shadow: 0 1px 0 #006799;
                    color: #fff;
                    text-decoration: none;
                    text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
                }

                #publishing-action {
                    display:none;
                }
            </style>
            <script>
                jQuery(function($){
                    $('select#post_status').html('').append('<option value=\"new\"$new_selected>Новая</option>');
                    $('select#post_status').append('<option value=\"performed\"$performed_selected>В работе</option>');
                    $('select#post_status').append('<option value=\"finished\"$finished_selected>Выполнена</option>');
                    $('select#post_status').append('<option value=\"unfinished\"$unfinished_selected>Не выполнена</option>');
                    $statusname
                });
            </script>";
    }
}
add_action( 'admin_footer-post-new.php', 'simple_lead_new_status_list' );
add_action( 'admin_footer-post.php', 'simple_lead_new_status_list' );


/**
 * Add label for lead statuses
 */
function simple_lead_new_status_lable( $statuses ) {
    global $post;
    if ( 'simple_lead' == $post->post_type ) {

        if ( 'new' != get_query_var( 'post_status' ) ) {
            if ( 'new' == $post->post_status ) {
                return array( 'Новая' );
            }
        }

        if ( 'performed' != get_query_var( 'post_status' ) ) {
            if ( 'performed' == $post->post_status ) {
                return array( 'В работе' );
            }
        }

        if ( 'finished' != get_query_var( 'post_status' ) ) {
            if ( 'finished' == $post->post_status ) {
                return array( 'Выполнена' );
            }
        }

        if ( 'unfinished' != get_query_var( 'post_status' ) ) {
            if ( 'unfinished' == $post->post_status ) {
                return array( 'Не выполнена' );
            }
        }

    }
    return $statuses;
}
add_filter( 'display_post_states', 'simple_lead_new_status_lable' );


/**
 * Add lead fast statuses
 */
function simple_lead_new_fast_status() {
    global $post;
    if ( 'simple_lead' == $post->post_type ) {
        echo "<script>
        jQuery(document).ready( function($) {
            $( 'select[name=\"_status\"]' ).html('').append( '<option value=\"new\">Новая</option>' );
            $( 'select[name=\"_status\"]' ).append( '<option value=\"performed\">В работе</option>' );
            $( 'select[name=\"_status\"]' ).append( '<option value=\"finished\">Выполнена</option>' );
            $( 'select[name=\"_status\"]' ).append( '<option value=\"unfinished\">Не выполнена</option>' );
        });
        </script>";
    }
}
add_action( 'admin_footer-edit.php', 'simple_lead_new_fast_status' );


/**
 * Extra fields for leads
 */
function simple_lead_fields()
{
    add_meta_box( 'extra_fields', 'Сведения о заявке', 'simple_lead_fields_box_func', 'simple_lead', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'simple_lead_fields', 1 );


function simple_lead_fields_box_func( $post ) {
    ?>
    <div class="simple-leads-admin-fields">
       
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title">ФИО клиента: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="leadname" value="<?php echo get_post_meta( $post->ID, 'leadname', 1 ); ?>"></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title">Телефон: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="leadphone" value="<?php echo get_post_meta( $post->ID, 'leadphone', 1 ); ?>" /></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title">Адрес: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="leadaddress" value="<?php echo get_post_meta( $post->ID, 'leadaddress', 1 ); ?>" /></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title" style="text-align:left;padding:px;">Услуга: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="leadservice" value="<?php echo get_post_meta( $post->ID, 'leadservice', 1 ); ?>" /></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-admin-field-title" style="text-align:left;padding:px;">Цена: </div>
            <div class="simple-leads-admin-field-input"><input type="text" name="leadprice" value="<?php echo get_post_meta( $post->ID, 'leadprice', 1 ); ?>" /></div>
        </div>
        
        <div style="margin-bottom:10px;">
            <div class="simple-leads-field-title" style="text-align:left;padding:5px;">Дополнительные сведения:</div>
            <textarea type="text" name="leaddescription" style="width:100%;height:150px;"><?php echo get_post_meta( $post->ID, 'leaddescription', 1 ); ?></textarea>
        </div>
    </div>

    <input type="hidden" name="simple_lead_fields_nonce" value="<?php echo wp_create_nonce( __FILE__ ); ?>" />

    <?php
}


/**
 * Save extra fields
 */
function simple_lead_fields_update( $post_id ) {
    
    if ( !isset( $_POST['simple_lead_fields_nonce'] ) || !wp_verify_nonce( $_POST['simple_lead_fields_nonce'], __FILE__ ) ) {
        return false;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return false;
    }
    
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return false;
    }
    
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
add_action( 'save_post', 'simple_lead_fields_update', 0 );



/**
 *  Add simple lead settings
 */
function simple_lead_settings_page() {
    add_submenu_page( 'edit.php?post_type=simple_lead', 'Настройки заявок', 'Настройки заявок', 'manage_options', 'settings_simple_lead', 'settings_simple_lead_page' );
}
add_action( 'admin_menu', 'simple_lead_settings_page' );


function settings_simple_lead_page()
{
    
    if ( isset( $_POST['save_simple_lead_settings'] ) ) {

        if ( !wp_verify_nonce( $_POST['_wpnonce'] ) ) {
            return false;
        }
        
        if ( !current_user_can( 'manage_options' ) ) {
            return false;
        }
        
        if ( !isset( $_POST['save_simple_lead_settings'] ) ) {
            return false;
        }

        foreach( $_POST as $key => $value ) {
            
            if ( empty( $value ) ) {
                delete_option( $key );
                continue;
            }
            
            if ( is_string( $value ) ) {
                $value = htmlspecialchars( $value );
            }

            update_option( $key, $value );
        }

        echo '<div id="message" class="updated notice is-dismissible"><p>Данные сохранены.</p></div>';
    }

    echo '<div class="wrap"><h1>Настройки заявок</h1></div>
    <form method="post" action="#">
        <table class="form-table">
            <tr>
                <th><label for="thanks_text">Сообщение об успешном оформлнии заявки </label></th>
                <td>
                    <textarea name="thanks_text" style="width:50%">'.get_option( "thanks_text" ).'</textarea>
                    <p class="description">Текст, который видит клиент после оформлления заявки</p>
                </td>
            </tr>
            <tr>
                <th><label for="notification_email">Почта для уведомления</label></th>
                <td>
                    <input type="text" name="notification_email" value=" '.get_option( 'notification_email' ).'">
                    <p class="description">Почта для обработки заявок. Сюда будут приходить уведопления о новых заявках</p>
                </td>
            </tr>
            <tr>
                <th><label for="notification_email_title">Заголовок уведомления</label></th>
                <td>
                    <input type="text" name="notification_email_title" value=" '.get_option( 'notification_email_title' ).'">
                    <p class="description">Заголовок письма-уведомления на email</p>
                </td>
            </tr>
            <tr>
                <th><label for="notification_email_text">Текст email-уведомления о заявке</label></th>
                <td>
                    <textarea name="notification_email_text" style="width:50%">'.get_option( "notification_email_text" ).'</textarea>
                    <p class="description">Используйте следующие шорткоды:<br>
                    {name} - имя клиента<br>
                    {phone} - телефон<br>
                    {date} - текущая дата<br></p>
                </td>
            </tr>
        </table>';
        
        wp_nonce_field();
        
        echo '<p class="submit"><input type="submit" name="save_simple_lead_settings" id="submit" class="button button-primary" value="Сохранить"></p>
    </form>';
}


/**
 *  Simple lead handler
 */
function simple_lead_new_lead() {
    wp_parse_str( $_POST['itemsdata'], $fields );

    if ( empty( $fields ) ) {
        $fields = $_POST;
        $pst = true;
      //  simple_lead_validation( $fields );
    }

    $arrdata = array();
    $i = 0;

    if ( !isset( $fields ) ) {
        exit;
    }
    
    foreach( $fields as $key => $value ) {
        
        $prefix = substr( $key, 0, 4);
        
        if ( empty( $value ) || $prefix != 'lead' ) {
            continue;
        }
       
        if ( is_string( $value ) ) {
            $value = trim( htmlspecialchars( $value ) );
        }

        $arrdata[$key] = $value;
    }

    global $wpdb;
    $posts = $wpdb->get_col( "SELECT ID FROM ".$wpdb->prefix."posts ORDER BY ID ASC" );

    foreach($posts as $post){
        $id = $post+1;
    }

    $post_data = array(
        'post_title'     => 'Заявка #'.$id,
        'post_type'      => 'simple_lead',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'post_status'    => 'new',
        'post_author'    => 1,
        'meta_input'     => $arrdata,
    );
    $post_id = wp_insert_post( wp_slash( $post_data ) ); 

    $notif_email = get_option( 'notification_email' );
    $notif_email_title = get_option( 'notification_email_title' );
    $message = get_option( "notification_email_text" );
    $phone = simple_lead_phone_processing( $arrdata['leadphone'] );
    
    $search = array( '{name}', '{phone}', '{date}' );
    $replace = array( $arrdata['leadname'], $phone, date("d-m-Y H:i") );
    $message_text = str_replace( $search, $replace, $message );
    
    $headers[] = "From: Мастер Ногинска <$notif_email> \r\n";
    $headers[] = 'content-type: text/html';
    $err = wp_mail( 'admin@masternoginska.ru', 'Новая заявка на сайте центр услуг Специалист', $message_text, $headers );

 
    if ( $pst ) {
        header( 'Location: '.site_url( '/thank-you/' ) );
        exit;
    }
    
    $text = '<div class="thanks_popup">
        <div class="thanks_popup_close"><span class="fa fa-times" id="thanks_close_window"></span></div>
        <h1>Благодарим за заявку</h1>
        <div class="thanks_popup_text">'.get_option( "thanks_text" ).'</div></div>';
    
    echo $text;
    exit;
}
add_action( "wp_ajax_simple_lead_new_lead", "simple_lead_new_lead" );
add_action( "wp_ajax_nopriv_simple_lead_new_lead", "simple_lead_new_lead" );
add_action( "admin_post_nopriv_simple_lead_new_lead", "simple_lead_new_lead" );
add_action( "admin_post_simple_lead_new_lead", "simple_lead_new_lead" );


/**
 * Modify leads table
 */

function simple_lead_add_columns_title( $columns )
{
    $columns['info'] = 'Информация о клиенте';
    return $columns;
}
add_filter( 'manage_edit-simple_lead_columns', 'simple_lead_add_columns_title' );

function simple_lead_add_columns( $column )
{
    global $post;
    switch ( $column ) {
        case 'info':
            echo ( $name = get_post_meta( $post->ID, 'leadname', 1 ) ) ? "ФИО: $name<br>" : '';
            echo ( $phone = get_post_meta( $post->ID, 'leadphone', 1 ) ) ? "Телефон: $phone<br>" : '';
            echo ( $address = get_post_meta( $post->ID, 'leadaddress', 1 ) ) ? "Адрес: $address<br>" : '';
            echo ( $price = get_post_meta( $post->ID, 'leadprice', 1 ) ) ? "Цена: $price<br>" : '';
            echo ( $service = get_post_meta( $post->ID, 'leadservice', 1 ) ) ? "Услуга: $service<br>" : '';
            echo ( $text = get_post_meta( $post->ID, 'leaddescription', 1 ) ) ? "Дополнительные сведения: $text<br>" : '';
        break;
    }
}
add_action( 'manage_posts_custom_column', 'simple_lead_add_columns' );


/**
 * Query for search in admin-panel
 */
function simple_lead_search_query( $query )
{
	global $pagenow;
	if ( 'edit.php' === $pagenow && 'simple_lead' === $_GET['post_type'] ) {
	    $custom_fields = array(
	        "leadname",
	        "leadphone",
	        "leadaddress",
	        "leadprice",
	        "leadservice",
	        "leaddescription"
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
add_filter( "pre_get_posts", "simple_lead_search_query" );
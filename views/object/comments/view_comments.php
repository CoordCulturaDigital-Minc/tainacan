<?php
/*
 * View responsavel em mostrar os comentarioas
 */
    global $withcomments;
    global $global_collection_id;
    global $global_data_permissions;
    global $global_term_id;
    $query = new WP_Query( array( 'post_type' => 'socialdb_object', 'post__in' => array( $object_id) ) );
    if ($query->have_posts()) {
        while ($query->have_posts()) :
            $query->the_post();
            $withcomments = "1";
            $global_collection_id = $collection_id;
            $global_data_permissions = $permissions;
           //  comment_form('', $object->ID ); 
            comments_template('/comments.php'); // Get wp-comments.php template 
        endwhile;
    }else
    {
        echo '<p>'.__('NO POSTS!','tainacan').'</p>';
    }
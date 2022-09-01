<?php
add_shortcode('to_do_list', 'to_do_list_fn'); 
function to_do_list_fn($args){
 global $wpdb;
 if(isset($args['user_id']) && $args['user_id']>0){
   global $wpdb;
    $table_name = $wpdb->prefix . '_todo_list';
    $sql = "SELECT * FROM $table_name WHERE user_id=".$args['user_id'];
    $results = $wpdb->get_results($sql);
    $finalResults = '<input type="hidden" name="path_plugin" id="path_plugin" class="path_plugin" value="'.admin_url('admin-ajax.php').'" /><table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead>
    <tr>
        <th scope="col" id="title" class="manage-column column-author">
            <a href="http://localhost/wordpress/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
        </th>
        <th scope="col" id="author" class="manage-column column-title">Description</th>
        <th scope="col" id="author" class="manage-column column-author">Status</th>
        <th scope="col" id="author" class="manage-column column-author">Action</th>        
    </tr>
    </thead>
            <tbody id="the-list">';       
             if(count($results)>0){
             foreach ($results as $key => $result) {
                $finalResults .= '<tr>
                  <td>'.$result->title.'</td>
                  <td>'.$result->description.'</td>
                  <td>';
                   if($result->status==0)
                    $finalResults .= "Pending";
                   else
                    $finalResults .= "Resolved";
                   $finalResults .='</td>
                  <td>';       
                  if($result->status==0){
                  $finalResults .='<button style="background-color:blue;color:#fff;cursor:pointer;padding:5px;border:none;border-radius:5px;font-weight:bold;" class="primary change_status" todo_id="'.$result->id.'" class="change_status">Complete</button>';             
                 } 
                 $finalResults .='</td>
                </tr>';
              } } else { 
              $finalResults .= '<tr>
                  <td colspan="5">No Item Added Yet</td>
              </tr>';         
             } 
            $finalResults .= '</tbody>
        <tfoot>
            <tr>
                <th scope="col" id="title" class="manage-column column-title column-primary">
                    <a href="http://localhost/wordpress/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" id="author" class="manage-column column-author">Description</th>
                <th scope="col" id="author" class="manage-column column-author">Status</th>
                <th scope="col" id="author" class="manage-column column-author">Action</th>
            </tr>
        </tfoot>
    </table>';
  return $finalResults;
  }
}
<?php
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('To Do', 'To Do', 'manage_options', 'todo-menu', 'todo_list' );
    add_submenu_page('todo-menu', 'Add Item', 'Add Item', 'manage_options', 'todo_add_item', 'todo_add_item' );
}

function todo_list(){
    global $wpdb;
    $table_name = $wpdb->prefix . '_todo_list';
    $sql = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($sql);
    ?>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
     <input type="hidden" name="path_plugin" id="path_plugin" class="path_plugin" value="<?php echo admin_url('admin-ajax.php');?>">
    <thead>
    <tr>
        <th scope="col" id="title" class="manage-column column-author">
            <a href="http://localhost/wordpress/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
        </th>
        <th scope="col" id="author" class="manage-column column-title">Description</th>
        <th scope="col" id="author" class="manage-column column-author">Assigned</th>
        <th scope="col" id="author" class="manage-column column-author">Status</th>
        <th scope="col" id="author" class="manage-column column-author">Action</th>
    </tr>
    </thead>
            <tbody id="the-list">       
             <?php if(count($results)>0){
             foreach ($results as $key => $result) {
              ?>
                <tr>
                  <td><?php echo $result->title;?></td>
                  <td><?php echo $result->description;?></td>
                  <td>
                  <?php
                  $user = get_userdata( $result->user_id );
                  if(isset($user->user_nicename))
                   echo $user->user_nicename;
                  ?>                   
                  </td>
                  <td>
                   <?php 
                   if($result->status==0)
                    echo "Pending";
                   else
                    echo "Resolved";
                   ?></td>
                  <td>
                    <a href="javascript:void(0);" class="delete_todo" todo_id="<?php echo $result->id;?>">
                     <img height="15" width="15" src="<?php echo plugin_dir_url(__DIR__).'/assets/images/minus.png';?>" />
                    </a>
                  </td>
                </tr>
             <?php } } else { ?>
              <tr>
                  <td colspan="5">No Item Added Yet</td>
              </tr>         
             <?php } ?>
            </tbody>
    <tfoot>
        <tr>
            <th scope="col" id="title" class="manage-column column-title column-primary">
                <a href="http://localhost/wordpress/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a>
            </th>
            <th scope="col" id="author" class="manage-column column-author">Description</th>
            <th scope="col" id="author" class="manage-column column-author">Assigned</th>
            <th scope="col" id="author" class="manage-column column-author">Status</th>
            <th scope="col" id="author" class="manage-column column-author">Action</th>
        </tr>
    </tfoot>

</table>
    <?php
}

function todo_add_item(){
 global $wpdb;
 $users = get_users( array( 'fields' => array( 'ID','user_login','user_nicename' ) ) );
 if(isset($_POST['submit'])){
  $table_name = $wpdb->prefix . '_todo_list';
  $todo_query = $wpdb->insert($table_name,array(
        'title'=>$_POST['title'],
        'description'=>$_POST['description'],
        'user_id'=>$_POST['user'],
        'status'=>$_POST['status'],
  ));
  if($todo_query){
   echo '<div class="updated"><p>Item Added in TO DO list!</p></div>';
  }
 }
    ?>
     <div class="wrap">
<h1>Add Item</h1>
<form method="post" action="<?php echo admin_url("admin.php?page=todo_add_item");?>">
<input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="63dd044e9e"><input type="hidden" name="_wp_http_referer" value="/wordpress/wp-admin/options-general.php">
<table class="form-table" role="presentation">
<tbody>
 <tr>
    <th scope="row"><label for="blogname">Title</label></th>
    <td><input required="" name="title" type="text" id="title" class="regular-text"></td>
</tr>
<tr>
    <th scope="row"><label for="description">Description</label></th>
    <td><textarea required="" name="description" type="text" id="description" class="regular-text"></textarea></td>
</tr>
<tr>
    <th scope="row"><label for="blogname">Assigned To User</label></th>
    <td>
     <select required="" name="user" type="text" id="user" class="regular-text">
       <option>--Select User--</option>  
       <?php foreach ($users as $key => $user) {
        echo '<option value="'.$user->ID.'">'.$user->user_nicename.'</option>';
       }
       ?>
    </select>
   </td>
</tr>
<tr>
    <th scope="row"><label for="blogname">Status</label></th>
    <td>
      <select required="" name="status" type="text" id="status" class="regular-text">
       <option value="0">Pending</option>
       <option value="1">Resolved</option>  
    </select>
    </td>
</tr>
</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p></form>
</div>       
    <?php
}
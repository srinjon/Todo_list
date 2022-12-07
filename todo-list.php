<?php
/**
 * plugin name: My Todo
 */
register_activation_hook(__FILE__, 'table_creator');
function table_creator(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
     $table_name = $wpdb->prefix . 'todolist';
     $sql = "DROP TABLE IF EXISTS $table_name;
     CREATE TABLE $table_name(
        id mediumint(11) NOT NULL AUTO_INCREMENT,
        todo_add varchar(200) NOT NULL,

        PRIMARY KEY id(id)
        )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
     
}
add_action('admin_menu','display_todo_menu');
function display_todo_menu(){
    add_menu_page('TODO','TODO','manage_options','todo-list','todo_list_callback');
    add_submenu_page('todo-list','Todo List','Todo List','manage_options','todo-list','todo_list_callback');
    add_submenu_page('todo-list','Add Todo','Add Todo','manage_options','add-todo','todo_list_add_callback');
    add_submenu_page(null, 'Update Todo', 'Update Todo','manage_options','update-todo','todo_list_update_call' );
    add_submenu_page(null,'Delete Todo', 'Delete Todo','manage_options','delete-todo','todo_list_delete_menu');
}
function todo_list_add_callback(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'todolist';
    $msg = '';
    if(isset($_REQUEST['submit'])){
        $wpdb->insert("$table_name",[
           'todo_add'=>$_REQUEST['todo_add']
        ]);
        if($wpdb->insert_id > 0){
            $msg ="Saved Successfully";
        } else {
            $msg="Failed to save data";
        }
    }
    ?>
    <h4 id="msg"><?php echo $msg; ?></h4>
    <div class="container">
        <form action="" method="post">
            <div class="input-container">
                <p>
                    <label>ADD TODO</label>
                    <input type="text" name="todo_add" id="inputBox">
                    <button type="submit" name="submit" id="add">ADD</button>
                </p>
                <p>
            
                </p>
                
            </div>
        </form>
    </div>


<?php }
function todo_list_callback(){
    global $wpdb;
    $table_name=$wpdb->prefix . 'todolist';
    $todo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name",""),ARRAY_A);
    if(count($todo_list)>0): ?>
    <div style="margin-top:40px ;">
<table border="1" cellpadding="10">
    <tr>
        <th>S.No.</th>
        <th>Todo</th>
        <th>Action</th>
    </tr>
    <?php $i=1;
    foreach($todo_list as $index => $todo): ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $todo['todo_add']; ?></td>
        <td>
            <a href="admin.php?page=update-todo&id=<?php echo $todo['id'];?>">Edit</a>
            <a href="admin.php?page=delete-todo&id=<?php echo $todo['id'];?>">Delete</a>
        </td>
    </tr>

    <?php endforeach; ?>
</table>
</div>
    <?php else:echo "<h2>Todo Record Not Found</h2>";endif;
}
function todo_list_update_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix. 'todolist';
    $msg = '';
    $id = isset($_REQUEST['id'])?intval($_REQUEST['id']):"";
    if(isset($_REQUEST['update'])){
        if(!empty($id)){
            $wpdb->update("$table_name",["todo_add"=>$_REQUEST['todo_add']],["id"=>$id]);
            $msg= 'Data Updated';
        }
    }
    $todo_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name where id = %d", $id), ARRAY_A);?>
    <h4><?php echo $msg; ?></h4>
    <div class="container">
        <form action="" method="post">
            <div class="input-container">
                <p>
                    <label>ADD TODO</label>
                    <input type="text" name="todo_add" value="<?php echo $todo_details['todo_add']; ?>" id="inputBox">
                    <button type="submit" name="update" id="add">UPDATE</button>
                </p>
                <p>
            
                </p>
                
            </div>
        </form>
    </div>
    <?php
}
function todo_list_delete_menu(){

}
// status tinyint(4) NOT NULL,
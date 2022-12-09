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
    add_submenu_page('todo-list','Todo Page','Todo Page','manage_options','todo-page','todo_page_menu');
}

function todo_page_menu(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'todolist1';
    $msg = '';
    if(isset($_REQUEST['submit'])){
        $wpdb->insert("$table_name",[
           'todo_add1'=>$_REQUEST['todo_add1']
        ]);
        if($wpdb->insert_id > 0){
            $msg ="Saved Successfully";
        } else {
            $msg="Failed to save data";
        }
    }
    ?>
    <h4 id="msg"><?php echo $msg; ?></h4>
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <title>Todo List</title>
    </head>
    <body>
        <div class="container">
            <h1 class="top-heading">TODO List Application</h1>
            <form action="" method="post">
                <div class="input-container">
                    <input type="text" name="todo_add1" id="inputBox">
                    <button type="submit" name="submit" id="add">ADD</button>
                </div>
              <div style="margin-top:40px;">
            <table border="1" cellpadding ="10">
                <tr>
                    <th>S.No</th>
                    <th>Todo</th>
                    <th>Action</th>
                </tr>
                <tr>
                <td>1</td>
                <td>Item 1</td>
                <td>
                    <button type="submit" name="" id="check"><i class="fa-sharp fa-solid fa-square-check"></i></button>
                    <button type="submit" name="" id="check"><i class="fa-solid fa-trash-can"></i></button>
                </td>
                </tr>
                <br>
                <tr>
                <td>2</td>
                <td>Item 2</td>
                <td>
                    <button type="submit" name="" id="check"><i class="fa-sharp fa-solid fa-square-check"></i></button>
                    <button type="submit" name="" id="check"><i class="fa-solid fa-trash-can"></i></button>
                </td>
                </tr>
            </table></div>
            
            </form>
        </div>
    </body>
    </html>
   

<?php }
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
global $wpdb;
$table_name = $wpdb->prefix . 'todolist';
$id= isset($_REQUEST['id']) ? intval($_REQUEST['id']): "";
if(isset($_REQUEST['delete'])){
    if($_REQUEST['conf']=='yes'){
        $row_exits=$wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$id),ARRAY_A);
        if(count($row_exits)>0){
            $wpdb->delete("$table_name",array('id'=>$id,));
        }
    } ?>
    <script>
        location.href = "<?php echo site_url(); ?>/wp-admin/admin.php?page=todo-list";
    </script>
    <?php } ?>
    <form action="" method="post">
        <p>
            <label>Are you sure want delete?</label>
            <input type="radio" name="conf" value="yes">Yes
            <input type="radio" name="conf" value="no" checked>No
        </p>
        <p>
            <button type="submit" name="delete">Delete</button>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </p>
    </form>
    <?php }
    add_shortcode('todolist','todo_list_add_callback');
    add_shortcode('todolisttable','todo_list_callback');
    add_shortcode('todolistupdate','todo_list_update_call');
?>


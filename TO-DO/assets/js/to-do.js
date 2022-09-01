jQuery(document).ready(function(){
    jQuery(document).on("click",".delete_todo",function(){
        if(confirm("Are you sure to delete this task ?")){
            var currentElement = jQuery(this);
            var plugin_url = jQuery("#path_plugin").val();
            var todo_id = jQuery(this).attr('todo_id');
            jQuery.ajax({  
            url: plugin_url,  
            type: 'POST',  
            data: {
              action:"remove_todo_item",
              todo_id: todo_id
            },
            success: function(data) {  
                currentElement.parents("tr").remove();
              }  
            });  
        }
    })

    jQuery(document).on("click",".change_status",function(){
        var todo_id = jQuery(this).attr('todo_id');
        var plugin_url = jQuery("#path_plugin").val();
        if(confirm("Are you sure to change status ?")){
        jQuery.ajax({  
            url: plugin_url,  
            type: 'POST',  
            data: {
              action:"change_status_todo",
              todo_id: todo_id
            },
            success: function(data) {  
                location.reload(true);
              }  
            });
        }
    })
})
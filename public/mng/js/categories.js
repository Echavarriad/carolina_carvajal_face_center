(function(){
var idCategory = null;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var treeData = $.parseJSON(json_tree);
$('#display-tree').tree({
    data: treeData,
    dragAndDrop: true
});

$('#tree').show();

$('#display-tree').on(
    'tree.move',
    function(event) {

        var moved_node = event.move_info.moved_node;
        var target_node = event.move_info.target_node;
        var position = event.move_info.position;
        var previous_parent = event.move_info.previous_parent;

       var data = {
          id : moved_node.id,
          position : position,
          target : target_node.id
       };

        $.ajax({
                url : baseRoot + "/admin/ajax/update-category-position",
                type : 'POST',
                dataType : 'json',
                data : data,
                success : function(response){
        
                },
                error:function(err){
                    console.log(err)
                }
        });
    }
);

$('#display-tree').on(
    'tree.select',
    function(event) {
        idCategory = event['node']['id'];
        parentId = event['node']['parent_id'];
        $('#btnEditCategory').removeAttr('disabled');
        if (parentId == null) {            
            $('#btnFeaturedCategory').removeAttr('disabled');
        }else{
            $('#btnFeaturedCategory').attr('disabled', 'disabled');
        }
        $('#delete-category').removeAttr('disabled');
});

    $('#btnEditCategory').click(function(event) {
        event.preventDefault();
        $('#id_type').val('edit');
        $('#id_manage').val(idCategory);
        $('#frmManage').submit();
    });

    $('#btnFeaturedCategory').click(function(event) {
        event.preventDefault();
        $('#id_type').val('featured');
        $('#id_manage').val(idCategory);
        $('#frmManage').submit();
    });

    $('#btnGalleryCategory').click(function(event) {
        event.preventDefault();
        $('#id_type').val('gallery');
        $('#id_manage').val(idCategory);
        $('#frmManage').submit();
    });

    $('#delete-category').click(function(event) {
        event.preventDefault();
        $('#id_type').val('del');
        $('#id_manage').val(idCategory);
        $('#frmManage').submit();
    });
})();


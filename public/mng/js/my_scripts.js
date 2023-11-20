$(function() { 

    $('.copy-url').click(function(event) {
      var url = $(this).data('url');
      navigator.clipboard.writeText(url);
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.success('La URL ha sido copiada al portapapeles');
    });

    $( "#sortable" ).sortable({
       handle: '.drag-handle',
       update : function (event , ui){
        let url = $(this).data('url');
        updateOrder(ui.item.index(), url);
       }
    });

    $( "#sortable" ).disableSelection();
    
    function updateOrder(order, url){
       var dataIds = $("#sortable").sortable("toArray");
       $.ajax({
         url: url,
         type: 'POST',
         dataType: 'json',
         async : false,
         data: {data_images : dataIds , order : order , "_token": token },
         beforeSend: function(){
          $("#sortable").css('opacity','0.6');
         },
         success: function(response){
           $("#sortable").css('opacity','1');
         },
         error:function(){
          $("#sortable").css('opacity','1');
         }
       });
  }  

    
});


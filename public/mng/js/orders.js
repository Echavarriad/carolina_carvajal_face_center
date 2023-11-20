(function(){

getOrders();

function getOrders(){
         var html = ''; 
         $.ajax({
             url : baseRoot +'/admin/ajax/news-orders',
             type : 'GET',
             dataType : 'json',
             data : {},
             success : function(response){
               var html = '';
               $('#spnCountOrders').text(response.count);

               $('#liTextNewOrders').text('Hay '+response.count+' pedidos nuevos');

               $.each(response.orders , function(index, val) {
                 html += ' <li>\
                    <a href="'+baseRoot+'/admin/orders/'+val.id+'">\
                      <h4>'+val.user.name+'</h4>\
                      <p>'+val.created_at+'</p>\
                      <p>Total: $'+val.total+'</p>\
                    </a>\
                  </li>';  
               });
               $('#ulContOrders').html(html);
             }
         });       

}



})();

$(document).ready(function (){    
    $.typeahead({
    	input: '.js-typeahead-user-coupon',
       	minLength: 1,
        maxItem: 20, 	 	
        emptyTemplate: 'No se encontraron resultados para "{{query}}"',
        cancelButton : true,
        dynamic:true,
        accent : true,
        debug: false,
       	template: function (query, item) {
            var color = "#777";
            return '<span class="row">' +
                '<span>{{email}}</span>'  +
            "</span>"
        },
        source: {
            Usuarios: {
            		display: "email",
        		      ajax: function (query) {
                        return {
                            type: "get",
                            url: baseRoot +'/admin/ajax/search-customer',
                            dataType : 'json',
                           	path: "data.users",
                            data: {
                                query: "{{query}}"
                            }                  
                        }
                }            
            },
              
        },
        callback: {
            onNavigateAfter: function (node, lis, a, item, query, event) {
                if (~[38,40].indexOf(event.keyCode)) {
                    var resultList = node.closest(".header_br1").find("ul.typeahead__list"),
                        activeLi = lis.filter("li.active"),
                        offsetTop = activeLi[0] && activeLi[0].offsetTop - (resultList.height() / 2) || 0;
     
                    resultList.scrollTop(offsetTop);
                }
     
            },
            onClickAfter: function (node, a, item, event) {
     						
                event.preventDefault();
                if (item.href != undefined) {
                	window.location.href=(item.href);
                }
                
                $('#result-container').text('');
     
            },
            onResult: function (node, query, result, resultCount) {
                if (query == ""){
                	$('.typeahead__result').text('');
                }
     
            }
        }
    });      
});
      
      
      
      
      
@push('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@push('js')
   	<script src="{{ asset('mng/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
   	<script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
   	<script type="text/javascript">
        $('.upload-file').click(function(event){
            let url = $(this).data('url');
          Swal.fire({
              title:"Seleccione el archivo excel para sincronizar",
              input: "file",
              showCancelButton:true,        
              confirmButtonColor: '#45835E',
              confirmButtonText: "Cargar",
              cancelButtonText: "Cancelar",
              inputAttributes: {
                'accept': '.xls, .xlsx',
                'aria-label': 'Cargar un archivo excel'
              },
              inputValidator: file => {
                // Si el valor es válido, debes regresar undefined. Si no, una cadena
                if (!file) {
                    return "Debe seleccionar un archivo.";
                } else {
                    return undefined;
                }
            }
          }).then(isConfirm => {
                if (isConfirm.value) {
                    var formData = new FormData();
                    var file = $('.swal2-file')[0].files[0];
                    if(!validateFile($('.swal2-file'), ['xls', 'xlsx'])){
                        return false;
                    }
                    formData.append("file", file);
                    formData.append("_token", token);
                    $.ajax({
                        url: url,
                        type : 'POST',
                        dataType : 'json',
                        cache: false,
                        data : formData,
                        processData: false,
                        contentType: false,
                        beforeSend : function(){
                        $('.loader').fadeIn();
                        },
                        success : function(res){
                        toastr.options.fadeOut = 4000;
                        toastr.options.positionClass = 'toast-bottom-right';
                        $('.loader').fadeOut();
                        if(res.status == true){                        
                            toastr.success(res.message);
                            setTimeout(function(){
                                location.reload();
                            }, 2000)
                        }else{
                            toastr.error(res.message);
                        }
                        } 
                    });
                }
            });
        }); 
        
        $('.export-file').click(function(event){
            let url= $(this).data('url');
            $.ajax({
                url: url,
                type : 'POST',
                data : {_token : token},
                dataType : 'json',
                success : function(res){
                } 
            });
        });

        function validateFile(object, exts){
            let valid = true;
            var file = object.val();
            var fileSize = object[0].files[0].size;
            title_swal = '';
            var ext = file.slice((file.lastIndexOf(".") - 1 >>> 0) + 2);
            if (jQuery.inArray( ext, exts) == -1) {
            title_swal = 'Solo se permiten archivos con extensión (' + exts + ')'; 
            }
            if (title_swal != '') {
                Swal.fire({
                    title:title_swal,
                    type: "error",
                    confirmButtonText: "Cerrar",
                    confirmButtonColor: '#45835E'
                });
            object.val('');
            valid = false;
            }
            return valid;
        }
      </script>
@endpush
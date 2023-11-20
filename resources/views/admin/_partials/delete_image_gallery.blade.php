@push('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
     <script>

  var baseRoot = "{{ url('/') }}";
  $(document).on('click', '.btn-delete_', function(event) {
      event.preventDefault();
      var table = $(this).data('table');
      var id = $(this).data('id');
      var obj = $(this);
      Swal.fire({
          title:"¿Está seguro de eliminar " + table + "?",
          showCancelButton:true,        
          confirmButtonColor: '#45835E',
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          }).then(isConfirm => {
            if (isConfirm.value) {
              $.ajax({
                type: "post",
                url: baseRoot + '/admin/ajax/delete-image-product',
                data: {id : id, "_token": "{{ csrf_token() }}"},
                dataType: "json",
                success: function (response) { 
                  toastr.options.fadeOut = 4000;    
                  if (response.status) {
                        $('.image-'+id).remove();
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.success('Imagen eliminada exitósamente');
                  }                         
                }
              });
            }
          });    
  });
  </script>
@endpush

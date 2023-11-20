@push('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@push('js')
  <script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
  <script>
    function copyToClipboard(title, element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.success(title +  ' copiado en el portapapeles');
    }
  </script>
@endpush
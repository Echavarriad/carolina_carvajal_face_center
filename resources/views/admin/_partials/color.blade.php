@push('css')
   <link rel="stylesheet" href="{{ url('mng/plugins/color/jquery.minicolors.css') }}">
@endpush
@push('js')
  <script src="{{url('/mng/plugins/color/jquery.minicolors.js')}}"></script>
  <script>
    $(document).ready(function(){
      $('.color').each( function() {
        $(this).minicolors({
          control: $(this).attr('data-control') || 'default',
          change: function(hex, opacity) {
            var log;
            try {
              log = hex ? hex : 'transparent';
              if( opacity ) log += ', ' + opacity;
            } catch(e) {}
          },
          theme: 'default'
        });

      });
    });
  </script> 
@endpush
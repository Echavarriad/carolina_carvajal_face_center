@push('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@push('js')
	<script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
  <script>
  $( function() {
    $('#sendFilter').click(function(event) {
      let message = '';
      if ($( "#date_initial").val() == '') {
        message = 'Seleccione la fecha inicial';
      }else if($( "#date_final").val() == ''){
         message = 'Seleccione la fecha final';
      }
      if (message != '') {
         toastr.options.positionClass = 'toast-bottom-right';
          toastr.error(message);
      }else{
        $('#formFilter').submit();
      }
    });
      var dateFormat = "mm/dd/yy";
      var today = new Date();
      from = $( "#date_initial")
        .datepicker({
          maxDate: today,
          defaultDate: "-1m",
          changeMonth: true,
          changeYear: true,
          numberOfMonths: 2
        })
        .on( "change", function() {
          var lockDate = new Date($('#date_initial').datepicker('getDate'));
          var aux =  $('#date_initial').datepicker('getDate');
          aux.setDate(aux.getDate() + 1);
          date_finish = [new Date(aux)];
          lockDate.setDate(lockDate.getDate());
          to.datepicker( "option", "minDate", lockDate);
        }),
      to = $( "#date_final" ).datepicker({
        maxDate: today,
        defaultDate: today,
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>
@endpush
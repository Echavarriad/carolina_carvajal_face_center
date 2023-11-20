<form action="{{ route($name.'.export') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="date_initial" value="{{ isset($field_form['date_initial']) ? $field_form['date_initial'] : '' }}">      
    <input type="hidden" name="date_final" value="{{ isset($field_form['date_final']) ? $field_form['date_final'] : '' }}">      
    @if(count($records) > 0)
      <button type="submit" class="btn btn-primary" ><i class="fa fa-download"></i> Exportar</button>
    @endif
</form>
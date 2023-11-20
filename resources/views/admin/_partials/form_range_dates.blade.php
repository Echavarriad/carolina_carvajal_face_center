<div class="box-header">
    <form action="{{ url()->current() }}" method="get" id="formFilter">
      <div class="row">
        <div class="col-sm-12 col-lg-4">
          <input type="text" name="date_initial" id
          ="date_initial" class="form-control" placeholder="Fecha inicial" value="{{ isset($field_form['date_initial']) ? $field_form['date_initial'] : '' }}" autocomplete="off"> 
        </div>
        <div class="col-sm-12 col-lg-4">
          <input type="text" name="date_final" id
          ="date_final" class="form-control"  placeholder="Fecha final" value="{{ isset($field_form['date_final']) ? $field_form['date_final'] : '' }}" autocomplete="off"> 
        </div>
        <div class="col-sm-12 col-lg-4">
          <button type="button" class="btn btn-primary" id="sendFilter"><i class="fa fa-search"></i> Buscar</button> 
          <a href="{{ route($name . '.index') }}" class="btn btn-default"><i class="fa fa-times"></i> Limpiar</a> 
        </div>
      </div> 
    </form>           
  </div>
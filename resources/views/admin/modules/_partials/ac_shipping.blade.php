<h2>Información para producción</h2>
<label for="">Costo envío</label>

<label for="">Información</label>
  <input type="text" class="form-control" name="_values[info]" value="{{ $settings['info'] }}">
  <br>  
<br>

<div class="form-check form-check-inline">
  <input class="form-check-input" name="_values[test]" type="checkbox" {{ $settings['test'] == 1 ? 'checked' : '' }}>
  <label class="form-check-label" for="inlineCheckbox1">Modo de pruebas</label>
</div>
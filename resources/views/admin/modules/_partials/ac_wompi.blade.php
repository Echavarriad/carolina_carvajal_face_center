<h2>Información para producción</h2>

<label for="">Llave pública</label>
<input type="text" class="form-control" name="_values[key_prod]" value="{{ $settings['key_prod'] }}">
<br>  

<label for="">Llave privada</label>
<input type="text" class="form-control" name="_values[key_priv_prod]" value="{{ $settings['key_priv_prod'] }}">
<br>

<label for="">URL</label>
<input type="text" class="form-control" name="_values[url_prod]" value="{{ $settings['url_prod'] }}">
<br>
<hr>
<h2>Información para pruebas</h2>
<label for="">Llave pública</label>
<input type="text" class="form-control" name="_values[key_test]" value="{{ $settings['key_test'] }}">
<br>  

<label for="">Llave privada</label>
<input type="text" class="form-control" name="_values[key_priv_test]" value="{{ $settings['key_priv_test'] }}">
<br>  

<label for="">URL</label>
<input type="text" class="form-control" name="_values[url_test]" value="{{ $settings['url_test'] }}">
<br>
<div class="form-check form-check-inline">
  <input class="form-check-input" name="_values[test]" type="checkbox" {{ $settings['test'] == 1 ? 'checked' : '' }}>
  <label class="form-check-label" for="inlineCheckbox1">Modo de pruebas</label>
</div>
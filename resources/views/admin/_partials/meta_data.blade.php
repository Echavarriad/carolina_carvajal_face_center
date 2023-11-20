@if ($action == 'edit')
		<label>Meta title</label>
    <textarea name="meta_title" class="form-control" rows="1">{{ $record->meta_title }}</textarea>
    @if ($errors->has('meta_title'))
       <label class="text-red">{{ $errors->first('meta_title') }}</label>
    @endif
    <br>

    <label>Meta description</label>
    <textarea name="meta_description" class="form-control" rows="5">{{ $record->meta_description }}</textarea>
    @if ($errors->has('meta_description'))
       <label class="text-red">{{ $errors->first('meta_description') }}</label>
    @endif
    <br>

    <label>Meta keywords</label>
    <textarea name="meta_keywords" class="form-control" rows="5">{{ $record->meta_keywords }}</textarea>
    @if ($errors->has('meta_keywords'))
       <label class="text-red">{{ $errors->first('meta_keywords') }}</label> <<strong></strong>
    @endif
    <br>

    @if ($metas_img)
    	<div class="row">
	      <div class="col-sm-12 col-lg-6">
	        <label>Title de la imagen</label>
	        <input type="text" name="tit" class="form-control" value="{{ $record->tit }}">
	      </div>
	      <div class="col-sm-12 col-lg-6">
	        <label>Alt de la imagen</label>
	        <input type="text" name="alt" class="form-control" value="{{ $record->alt }}">
	      </div>
	    </div>
    @endif    
@else
	<label>Meta title</label>
  <textarea name="meta_title" class="form-control" rows="1">{{ old('meta_title') }}</textarea>
  <br>

  <label>Meta description</label>
  <textarea name="meta_description" class="form-control" rows="5">{{ old('meta_description') }}</textarea>
  <br>

  <label>Meta keywords</label>
  <textarea name="meta_keywords" class="form-control" rows="5">{{ old('meta_keywords') }}</textarea>
  <br>
 	@if ($metas_img)
	  <div class="row">
	    <div class="col-sm-12 col-lg-6">
	      <label>Title de la imagen</label>
	      <input type="text" name="tit" class="form-control" value="{{ old('tit') }}">
	    </div>
	    <div class="col-sm-12 col-lg-6">
	      <label>Alt de la imagen</label>
	      <input type="text" name="alt" class="form-control" value="{{ old('alt') }}">
	    </div>
	  </div>
  @endif  
@endif
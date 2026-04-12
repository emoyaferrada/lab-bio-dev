<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
  <form>
    <div class="form-group row">
      <label for="cliente" class="col-4 col-form-label">Cliente</label> 
      <div class="col-8">
        <select id="cliente" name="cliente" class="custom-select">
          <option value="rabbit">Rabbit</option>
          <option value="duck">Duck</option>
          <option value="fish">Fish</option>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="predio" class="col-4 col-form-label">Predio</label> 
      <div class="col-8">
        <select id="predio" name="predio" class="custom-select">
          <option value="rabbit">Rabbit</option>
          <option value="duck">Duck</option>
          <option value="fish">Fish</option>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="fecha_ingreso" class="col-4 col-form-label">Fecha Ingreso</label> 
      <div class="col-8">
      <input type="date" class="form-control" name="fecha_ingreso" access="false" id="fecha_ingreso" required="required" aria-required="true">
      </div>
    </div>
    <div class="form-group row">
      <label for="tipo_analisis" class="col-4 col-form-label">Tipo de analisis</label> 
      <div class="col-8">
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value="rabbit">Rabbit</option>
          <option value="duck">Duck</option>
          <option value="fish">Fish</option>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4">Checkboxes</label> 
      <div class="col-8">
        <div class="custom-control custom-checkbox custom-control-inline">
          <input name="checkbox" id="checkbox_0" type="checkbox" checked="checked" class="custom-control-input" value="rabbit"> 
          <label for="checkbox_0" class="custom-control-label">Rabbit</label>
        </div>
        <div class="custom-control custom-checkbox custom-control-inline">
          <input name="checkbox" id="checkbox_1" type="checkbox" class="custom-control-input" value="duck"> 
          <label for="checkbox_1" class="custom-control-label">Duck</label>
        </div>
        <div class="custom-control custom-checkbox custom-control-inline">
          <input name="checkbox" id="checkbox_2" type="checkbox" class="custom-control-input" value="fish"> 
          <label for="checkbox_2" class="custom-control-label">Fish</label>
        </div>
      </div>
    </div> 
    <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="nuevo_id" type="button" class="btn btn-primary">Obtener ID Muestra</button>
        <button name="guardar" type="button" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>

</div>
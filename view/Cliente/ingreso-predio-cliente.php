<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<form>
  <div class="form-group row">
    <label for="nombre_cliente" class="col-4 col-form-label">Nombre Cliente</label> 
    <div class="col-8">
      <select id="nombre_cliente" name="nombre_cliente" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="nombre_predio" class="col-4 col-form-label">Nombre del predio</label> 
    <div class="col-8">
      <input id="nombre_predio" name="nombre_predio" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="comuna" class="col-4 col-form-label">Comuna</label> 
    <div class="col-8">
      <select id="comuna" name="comuna" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="direccion" class="col-4 col-form-label">Dirección</label> 
    <div class="col-8">
      <input id="direccion" name="direccion" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="sector" class="col-4 col-form-label">Sector</label> 
    <div class="col-8">
      <input id="sector" name="sector" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="lat" class="col-4 col-form-label">LAT</label> 
    <div class="col-8">
      <input id="lat" name="lat" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="lon" class="col-4 col-form-label">LON</label> 
    <div class="col-8">
      <input id="lon" name="lon" type="text" class="form-control">
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </div>
</form>
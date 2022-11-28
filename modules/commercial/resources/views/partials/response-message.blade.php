@if(Session::has('success'))       
  <div class="alert text-center alert-success response-message"> {{ Session::get('success') }} </div>
@elseif(Session::has('error'))
  <div class="alert text-center alert-danger response-message"> {{ Session::get('error') }} </div>
@endif 
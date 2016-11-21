<div class="alert alert-{{ session('alert-type') == null ? 'success' : session('alert-type') }} alert-dismissible fade in" role="alert" style="display:non">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <span class="fa fa-check"></span> {{ session('status') }}
</div>
@if( session('status') != null)
<div class="alert alert-{{ session('alert_type') }} alert-dismissible fade in" id="alert-notif" role="alert" style="display:non">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	 <span class="fa fa-check"></span> {{ session('status') }}
</div>
@endif
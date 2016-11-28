@if(isset($status))
<div class="alert alert-{{ $alert_type }} alert-dismissible fade in" id="alert-notif" role="alert" style="display:non">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	 <span class="fa fa-check"></span> {{ $status }}
</div>
@endif

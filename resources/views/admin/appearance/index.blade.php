@extends('layouts.admin')

@section('content')

<div class="container">

	<div class="row">

		<div class="col-md-12">

			<div class="row">

				<div class="col-md-12">

					<div class = "page-header">

						<h1>Appearance</h1>
						
					</div>

				</div>

				<div class="col-md-8">

					<h4>Theme</h4>

					<div name="themes">

						@foreach($themes as $theme)

							<div class="radio" style="margin-left:10px">

                                <label>

									<input type="radio" name="theme" id="{{ $theme->id }}" {{ $change_theme ? 'disabled' : null }}>
									{{ $theme->name }}

								</label>

							</div>

						@endforeach

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">

	var csrf  = "{{ csrf_token()}}";
	var theme = $('input[name=theme]');
	
	$(function(){

		$.ajax({
			type:"GET",
			url:"{{ url('/admincontrol/appearance/usertheme') }}",
			data :{
				"_token": csrf
			},
			dataType:"JSON",
			success:function(data){

				$('div[name=themes]').find('input#'+data['id']).prop('checked', true);

			}
		});

		theme.on('change', function(){

			var selectedtheme=$(this).prop('id');
			
			$.ajax({
				type:"POST",
				url:"{{ url('/admincontrol/appearance/changetheme') }}",
				data:{
					"_token": csrf,
					"theme_id": selectedtheme
				},
				dataType:"JSON",
				success:function(data){

					//console.log(data);
				
				}
			});

		});

	});

</script>

@endsection
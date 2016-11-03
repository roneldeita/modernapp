@extends('layouts.admin')

@section('styles')
	
	<link rel="stylesheet" type="text/css" href="{{ url('assets/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
	
@endsection

@section('content')

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class = "page-header">

					<h1>Manage Access</h1>
					
				</div>

			</div>
			
			<div class="col-md-4">

				<div class="form-goup">
					<label for="module">Module</label>
					<select name="module" id="module" class="form-control">
						<option value="0">Select a module</option>

						@foreach($modules as $module)

							<option value="{{ $module->id }}">{{ $module->name }}</option>

						@endforeach

					</select>
				</div>

			</div>

			<div class="col-md-4">

				<div class="form-goup">
					<label for="user">User</label>
					<select name="user" id="user" class="form-control">
						<option value="0">Select a user</option>

						@foreach($users as $user)

							<option value="{{ $user->id }}">{{ $user->name }}</option>

						@endforeach

					</select>
				</div>

			</div>

			<div class="col-md-12">
				<br>
				<table class="table table-hover">
					<thead>
						<th style="width:600px">Methods</th>
						<th>Grant</th>
					</thead>
					<tbody class="methods">
						
					</tbody>
				</table>

			</div>

		</div>
		
	</div>

@endsection

@section('scripts')
	
	<script src="{{ url('assets/bootstrap-switch-master/dist/js/bootstrap-switch.min.js') }}"></script>

	<script type="text/javascript">

	var csrf = "{{ csrf_token()}}";
	var select_module = $('select[name=module]');
	var select_user = $('select[name=user]');
	var button_save = $('button[name=save]');


	$(function(){

		select_module.on('change', function(){

			//$('select[name=user] option:selected').prop('selected', false);
			//$('select[name=user] option:first').prop('selected', 'selected');

			var id = $(this).val();
			$.ajax({

				type:"GET",
				url: "{{ url('/admincontrol/access/methods') }}",
				data : { 
					"id": id,
					"_token": csrf

				},
				dataType : "JSON",
				success : function(data){

					$('.methods').empty();

					$.each(data, function(key, value){

					 	var methods = $('.methods');
					 	var tr  = $('<tr></tr>');
					 	var td = $('<td></td>', { text:value['name'] });
					 	var td2 = $('<td></td>');
					 	var checkbox = $('<input/>', { id:value['id'], type:"checkbox", class:"method-checkbox" } );
					 	methods.append(tr);
					 	tr.append(td);
					 	td2.insertAfter(td);
					 	td2.append(checkbox);

					});

					$(".method-checkbox").bootstrapSwitch({
						'size':'small',
						'onColor':'success',
						'offColor':'default',
						'onText' :'Yes',
						'offText':'No',
						'disabled':true
					});

				},
				complete: function(){
					//trigger select user
					var selected = $('select[name=user] option:selected').val();
					$('select[name=user] option:first').prop('selected', 'selected');
					$('select[name=user] option[value='+ selected +']').prop('selected','selected').change();
				}
			});

		});

		select_user.on('change', function(){
			
			var id = $(this).val();
			
			$.ajax({
				type:"GET",
				url: "{{ url('/admincontrol/access/user') }}",
				data :{
					"id": id,
					"_token": csrf
				},
				dataType : "JSON",
				success: function(data){

					//enable switch
					$('.method-checkbox').bootstrapSwitch('disabled', false);
					//reset switch
					$('.methods').find('input').prop('checked', false).bootstrapSwitch('state', false, true);

					$.each(data[0], function(key, value){
						//turn the switch to yes/true if found access
					 	$('.methods').find('input#'+value['id']).prop('checked', true).bootstrapSwitch('state', true, true);

					});
					
					//disable switch if disable is true
					if(data[1]['disable'] === true){
						$('.method-checkbox').bootstrapSwitch('disabled',true);
					}

				}
			});

		});

		$(document).on('switchChange.bootstrapSwitch','.method-checkbox', function(event, state) {
			var userId=$('select[name=user] option:selected').val();
			var methodId=$(this).attr('id');

			if(state === true){
				$.ajax({
					type:"POST",
					url:"{{ url('/admincontrol/access/add') }}",
					data :{
						"user_id":userId,
						"method_id":methodId,
						"_token": csrf
					},
					dataType:"JSON",
					success: function(data){

						//console.log("done!")

					}

				});
			}else{
				$.ajax({
					type:"POST",
					url:"{{ url('/admincontrol/access/remove') }}",
					data :{
						"user_id":userId,
						"method_id":methodId,
						"_token": csrf
					},
					dataType:"JSON",
					success: function(data){
						 		
						 //console.log("done!")

					}

				});
			}
		});

	});

	</script>

@endsection

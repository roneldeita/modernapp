@extends('layouts.admin')

@section('content')
	
	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Manange Post category</h1>

				</div>

			</div>

			<div class="col-md-3">
				
				<div>

					<button type="button" class="btn btn-sm btn-primary" name="new-user" {{ $access->create ? null : 'disabled' }}>
						<span class="fa fa-plus-circle"></span> New Category
					</button>
				
				</div>

			</div>

			<div class="col-md-12">

				<table class="table table-hover" name="postcategory-table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Created</th>
							<th>Updated</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>

		</div>

	</div>

	@include('admin.modal.modal')

@endsection

@section('scripts')

	<script type="text/javascript" src="{{ url('assets/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
	
	<script type="text/javascript">
		/*
		* for the Users Table
		*/
		var tbody 	= $('table[name=postcategory-table]').find('tbody');
		/*
		 * for the Modal
		 */
	 	var modal 		= $('#adminModal').modal({show:false, backdrop:'static'});
	 	var modalDiag   = $('.modal-dialog').draggable({handle: ".modal-header" });
		var mTitle 		= $('h4.modal-title');
		var mBody 		= $('div.modal-body');
		var mNotif 		= $('div.modal-notification');
		var mFooter 	= $('div.modal-footer');
		var mform		= $('<form></form>', {name:"form-user"} );
		var csrf 		= '{{ csrf_field() }}';

		$(function(){
			//load data
			loadPostCategory();

			$('button[name=new-user]').on('click', function(){

				modal.modal('show');

			});

			function loadPostCategory(){

				$.ajax({
					type:"GET",
					url:"{{ url('/admincontrol/post/category/data') }}",
					data:{
						"_token":"{{ csrf_token() }}"
					},
					dataType:"JSON",
					success:function(data){
						tbody.empty();

						$.each(data, function(key, value){

							tbody.append($('<tr></tr>').append(
								$('<td></td>',{ text:value['name']}),
								$('<td></td>',{ text:value['created'] }),
								$('<td></td>',{ text:value['updated'] })
							));

						});
					}

				});

			}


		});

	</script>

@endsection
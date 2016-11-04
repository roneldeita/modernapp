@extends('layouts.admin')

@section('content')

	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Manage Posts</h1>

				</div>

			</div>

			<div class="col-md-12">

				<table class="table table-hover" name="posts-table">
					<thead>
						<tr>
							<th>Title</th>
							<th>Owner</th>
							<th>Category</th>
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

@endsection

@section('scripts')

	<script type="text/javascript">
	/*
	* for the Users Table
	*/
	var tbody 	= $('table[name=posts-table]').find('tbody');

	$(function(){
		//load posts
		loadPosts();


		function loadPosts(){

			$.ajax({
				type:"GET",
				url: "{{ url('admincontrol/post/data') }}",
				data:{
					"_token":"{{ csrf_token() }}"
				},
				dataType:"JSON",
				beforeSend: function(){
					tbody.append($('<tr></tr>').append(
						$('<td></td>',{ text:"Loading...", colspan:"100%", align:"center" })));
				},
				success: function(data){
					
					tbody.empty();

					$.each(data, function(key, value){

						tbody.append($('<tr></tr>').append(
							$('<td></td>',{ text:value['title']}),
							$('<td></td>',{ text:value['owner']}),
							$('<td></td>',{ text:value['category'] }),
							$('<td></td>',{ text:value['created'] }),
							$('<td></td>',{ text:value['updated'] })
						));

					});
				},
				error: function(){

					tbody.empty();

					tbody.append($('<tr></tr>').append(
						$('<td></td>',{ text:"Error", colspan:"100%", align:"center" })));
				}

			});

		}

	});

	</script>

@endsection
@extends('layouts.admin')

@section('styles')

<link rel="stylesheet" type="text/css" href="{{ url('assets/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css') }}">

<style type="text/css">

	.center-checkbox{
	    vertical-align: middle;
	}

</style>

@endsection

@section('content')

	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Manage Posts</h1>

				</div>

			</div>

			<div class="col-md-3">
				
				<div class="form-group">

					<button class="btn btn-sm btn-default" name="multiple-delete" {{ $delete ? null : 'disabled' }}><span class="fa fa-trash"></span> Delete selected</button>

				</div>

			</div>

			<div class="col-md-12">

				<table class="table table-hover" name="posts-table">
					<thead>
						<tr>
							<th width="3%">&nbsp;</th>
							<th style="width:35%">Body</th>
							<th style="width:10%">Owner</th>
							<th style="width:10%">Category</th>
							<th style="width:15%">Created</th>
							<th style="width:15%">Updated</th>
							<th style="width:10%">Display</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>

			<div class="col-md-12">
				<div id="pagination" data-active="" data-last=""></div>
			</div>

		</div>

	</div>

	@include('admin.modal.modal')

@endsection

@section('scripts')
	
	<script src="{{ url('assets/bootstrap-switch-master/dist/js/bootstrap-switch.min.js') }}"></script>

	<script type="text/javascript" src="{{ url('assets/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>

	<script type="text/javascript">
	/*
	* for the Users Table
	*/
	var tbody 			= $('table[name=posts-table]').find('tbody');
	var pagination 		= $('div#pagination');

	var multiDelete 	= $('button[name=multiple-delete]');
	/*
	 * for the Modal
	 */
 	var modal 			= $('#adminModal').modal({show:false, backdrop:'static'});
 	var modalDiag  		= $('.modal-dialog').draggable({handle: ".modal-header" });
	var mTitle 			= $('h4.modal-title');
	var mBody 			= $('div.modal-body');
	var mNotif 			= $('div.modal-notification');
	var mFooter 		= $('div.modal-footer');
	var mform			= $('<form></form>', {name:"form-user"} );
	var csrf 			= '{{ csrf_field() }}';

	var deleteBtn 		= $('<button></button>',{type:"button", name:"delete", text:"Delete", class:"btn btn-danger"});
	var cancelBtn 		= $('<button></button>',{type:"button", name:"cancel", text:"Cancel", class:"btn btn-secondary",'data-dismiss':"modal"});

	$(function(){
		//load posts
		loadPosts();


		//pagination
		$(document).on('click', 'ul.pagination a', function(e){
			e.preventDefault();

			var page = $(this).attr('href').split('page=')[1];

			loadPosts(page);

		});

		$(document).on('switchChange.bootstrapSwitch','.display-checkbox', function(event, state) {
			var postdId=$(this).attr('id');

			$.ajax({
				type: "POST",
				url: "{{ url('/admincontrol/post/switchdisplay') }}",
				data:{
					"_token":"{{ csrf_token() }}",
					"id":postdId,
					"display":state
				},
				dataType:"JSON",
				success:function(data){
					//console.log(data);
				}
			});

		});

		multiDelete.on('click', function(){

			cleanModal();

			var postIds = $('input.selected-checkbox').filter(':checked').map(function(){
				return this.id 
			}).get();

			var question = $('<p></p>', {class:"text-center", text:"You are about to delete selected posts, this procedure is irreversible."});
			mTitle.text('Confirm Delete');
			mBody.append(question);
			mFooter.append(cancelBtn, deleteBtn);
			modal.modal('show');

			deleteBtn.on('click', function(){

				$.ajax({
					type:"POST",
					url:"{{ url('/admincontrol/post/deleteposts') }}",
					data:{
						"_token": "{{ csrf_token() }}",
						"ids"	:postIds
					},
					dataType:"JSON",
					success:function(data){
						loadPosts(pagination.attr('data-active'));
						modal.modal('hide');
					}
				});
			});
			
		});


		//load posts
		function loadPosts(page){

			$.ajax({
				type:"GET",
				url: "{{ url('admincontrol/post/data?page=') }}"+page,
				data:{
					"_token":"{{ csrf_token() }}"
				},
				dataType:"JSON",
				beforeSend: function(){
					tbody.empty();
					tbody.append($('<tr></tr>').append(
						$('<td></td>',{colspan:"100%", align:"center"}).prepend($('<div class="loader"></div>'))));
				},
				success: function(data){
					
					tbody.empty();

					if(data['data']['data'].length > 0){

						$.each(data['data']['data'], function(key, value){

							//set pagination active page
							pagination.attr('data-active', data['data']['current_page']);

							var dispChckbox	= $('<input/>',{type:"checkbox", id:value['id'], class:"display-checkbox"});
							var selectCheckbox	= $('<input/>',{type:"checkbox", id:value['id'], class:"selected-checkbox"});

							if(value['display']){

								dispChckbox.prop('checked', true);

							}

							tbody.append($('<tr></tr>').append(
								$('<td></td>',{class:"center-checkbox"}).append(selectCheckbox),
								$('<td></td>',{ text:value['shortbody']}),
								$('<td></td>',{ text:value['owner']}),
								$('<td></td>',{ text:value['category'] }),
								$('<td></td>',{ text:value['created'] }),
								$('<td></td>',{ text:value['updated'] }),
								$('<td></td>',{ class:"action"}).append(dispChckbox)
							));
						});
					}else{

						tbody.append($('<tr></tr>').append(
						$('<td></td>',{ text:"No results", colspan:"100%", align:"center", class:"text-danger"  })));

					}

					//boostrap switch
					$(".display-checkbox").bootstrapSwitch({
						'size':'mini',
						'onColor':'success',
						'offColor':'default',
						'onText' :'Yes',
						'offText':'No',
						'disabled':"{{ $update ? false : true }}"
					});
					//pagination
					pagination.html(data['pagination']);
				},
				error: function(){

					tbody.empty();

					tbody.append($('<tr></tr>').append(
						$('<td></td>',{ text:"Error", colspan:"100%", align:"center" })));
				}

			});

		}

		function cleanModal(){

			mTitle.text('');
			mBody.empty();
			mFooter.empty();
		}

	});

	</script>

@endsection
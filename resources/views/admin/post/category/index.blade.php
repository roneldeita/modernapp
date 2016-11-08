@extends('layouts.admin')

@section('content')
	
	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Manange Post Category</h1>

				</div>

			</div>

			<div class="col-md-3">
				
				<div class="form-group">
					
					<button type="button" class="btn btn-sm {{ $create ? 'btn-primary' : 'btn-default' }}" name="new-postcategory" {{ $create ? null : 'disabled' }}>
						<span class="fa fa-plus-circle"></span> New Category
					</button>
	
				</div>

			</div>

			<div class="col-md-3 col-md-offset-6">

				<div class="form-group">

					<div class="input-group input-group-sm">

						<input type="text" name="search" class="form-control">
						<div class="input-group-btn">
							<button class="btn btn-default" name="search-btn">Search</button>
						</div>

					</div>

				</div>
				
			</div>

			<div class="col-md-12">

				<table class="table table-condensed table-hover" name="postcategory-table">
					<thead>
						<tr>
							<th style="width:30%">Name</th>
							<th style="width:30%">Created</th>
							<th style="width:30%">Updated</th>
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

	<script type="text/javascript" src="{{ url('assets/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
	
	<script type="text/javascript">
		/*
		* for the Postcategory Table
		*/
		var tbody 		= $('table[name=postcategory-table]').find('tbody');
		var newBtn		= $('button[name=new-postcategory]');
		var srchBtn		= $('button[name=search-btn]');
		var srchField	= $('input[name=search]');
		var pagination 	= $('div#pagination');
		/*
		 * for the Modal
		 */
	 	var modal 		= $('#adminModal').modal({show:false, backdrop:'static'});
	 	var modalDiag   = $('.modal-dialog').draggable({handle: ".modal-header" });
		var mTitle 		= $('h4.modal-title');
		var mBody 		= $('div.modal-body');
		var mNotif 		= $('div.modal-notification');
		var mFooter 	= $('div.modal-footer');
		var mform		= $('<form></form>', {name:"form-postcategory"} );
		var csrf 		= '{{ csrf_field() }}';
		/*
		 * CRUD
		 */
		var saveBtn 	= $('<button></button>',{type:"button", text:"Save", class:"btn btn-primary"});
		var updateBtn 	= $('<button></button>',{type:"button", text:"Update", class:"btn btn-primary"});
		var cancelBtn 	= $('<button></button>',{type:"button", name:"cancel", text:"Cancel", class:"btn btn-secondary",'data-dismiss':"modal"});
		var nameFrmGrp 	= $('<div></div>',{class:"form-group"}).append($('<label></label>', {for:"name", text:"Name"}));
		var nameTxt 	= $('<input></input', {type:"text", name:"name", id:"name", class:"form-control"});
		var idFrmGrp 	= $('<div></div>',{class:"form-group"});
		var idField		= $('<input></input', {type:"hidden", name:"postcategory_id", id:"postcategory_id", class:"form-control"});
		/*
		 * For Edit
		 */
		 var editIcon	= $('<span></span>',{class:"fa fa-pencil"});
		 var btnEdit	= $('<button></button>', { name:"edit-btn", class:"btn btn-sm {{ $update ? 'btn-primary' : 'btn-default disabled' }}"}).prop('disabled', '{{ $update ? false : true }}').append(editIcon);
		 
		
		$(function(){
			//load data
			loadPostCategory();
			//prevent form submission
			mform.on('submit', function(e){
				e.preventDefault();
			});

			newBtn.on('click', function(){

				cleanModal();
				//set modal
				mTitle.text('Add Post Category');
				mBody.append(mform.append(
						csrf, 
						nameFrmGrp.append(nameTxt)
					));
				mFooter.append(saveBtn);
				modal.modal('show');

				saveBtn.on('click', function(){
					
					$.ajax({
						type: "POST",
						url: "{{ url('/admincontrol/post/category/create') }}",
						data: mform.serializeArray(),
						dataType: "JSON",
						beforeSend: function(){

							cleanNotification();

						},
						success: function(data, textStatus , jqXHR){

							mNotif.append($('<ul></ul>', {name:"success-msgs", class:"text-success text-center", style:"list-style:none"}));

							$.each(data, function(key, value){

								$('ul[name=success-msgs]').append($('<li></li>',{text: value}));

							});

							loadPostCategory(1);
							mform.trigger('reset');

						},
						error: function(xhr, status, error){

							var errors = $.parseJSON(xhr.responseText);

							mNotif.append($('<ul></ul>', {name:"error-msgs", class:"text-danger"}));

							$.each(errors, function(key, value){

								$('ul[name=error-msgs]').append($('<li></li>',{text: value}));

							});

					
						}
					});

				});

			});

			$(document).on('click','button[name=edit-btn]', function(){
				//clean modal
				cleanModal();
				//set data
				var id = $(this).parents('td[name=action]').data('id');
				var name = $(this).parents('td[name=action]').data('name');

				//set modal
				mTitle.text('Edit Post Category');
				mBody.append(mform.append(
						csrf, 
						nameFrmGrp.append(nameTxt.val(name)),
						idFrmGrp.append(idField.val(id))
					));
				mFooter.append(updateBtn);
				modal.modal('show');

				updateBtn.on('click', function(){


					$.ajax({
						type: "POST",
						url: "{{ url('/admincontrol/post/category/update') }}",
						data: mform.serializeArray(),
						dataType: "JSON",
						beforeSend: function(){

							cleanNotification();

						},
						success: function(data, textStatus , jqXHR){

							mNotif.append($('<ul></ul>', {name:"success-msgs", class:"text-success text-center", style:"list-style:none"}));

							$.each(data, function(key, value){

								$('ul[name=success-msgs]').append($('<li></li>',{text: value}));

							});

							loadPostCategory(pagination.attr('data-active'), srchField.val());

						},
						error: function(xhr, status, error){

							var errors = $.parseJSON(xhr.responseText);

							mNotif.append($('<ul></ul>', {name:"error-msgs", class:"text-danger"}));

							$.each(errors, function(key, value){

								$('ul[name=error-msgs]').append($('<li></li>',{text: value}));

							});

					
						}

					});

				});

			});

			//search
			srchBtn.on('click', function(){

				loadPostCategory(1, srchField.val());

			});			
			
			//pagination
			$(document).on('click', 'ul.pagination a', function(e){
				e.preventDefault();

				var page = $(this).attr('href').split('page=')[1];


				loadPostCategory(page, srchField.val());

			});

			//load data
			function loadPostCategory(page, search){

				$.ajax({
					type:"GET",
					url:"{{ url('/admincontrol/post/category/data?page=') }}"+page,
					data:{
						"_token":"{{ csrf_token() }}",
						"search":search
					},
					dataType:"JSON",
					beforeSend: function(){
						tbody.empty();
						tbody.append($('<tr></tr>').append(
							$('<td></td>',{ text:"Loading...", colspan:"100%", align:"center", class:"text-success" })));
					},
					success:function(data){

						tbody.empty();

						pagination.attr('data-active', data['data']['current_page']);
						pagination.attr('data-last', data['data']['current_page']);

						if(data['data']['data'].length > 0){

							$.each(data['data']['data'], function(key, value){

								tbody.append($('<tr></tr>').append(
									$('<td></td>',{ text:value['name'], style:'font-weight:bold'}),
									$('<td></td>',{ text:value['created'] }),
									$('<td></td>',{ text:value['updated'] }),
									$('<td></td>',{
										'class':'text-right',
										'name':"action",
										'data-id':value['id'],
										'data-name':value['name']
									})
								));

							});

						}else{

							tbody.append($('<tr></tr>').append(
							$('<td></td>',{ text:"No results", colspan:"100%", align:"center", class:"text-danger"  })));

						}

						

						$('td[name=action]').append(btnEdit);

						pagination.html(data['pagination']);
						
					}

				});

			}

			function cleanModal(){

				mTitle.text('');
				mBody.empty();
				mform.trigger('reset');
				mNotif.empty();
				mform.empty();
				mFooter.empty();
			}

			function cleanNotification(){
				mNotif.empty();
			}	

		});

	</script>

@endsection
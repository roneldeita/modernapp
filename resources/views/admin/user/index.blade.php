@extends('layouts.admin')

@section('content')
	
	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Manage Users</h1>

				</div>

			</div>

			<div class="col-md-3">
				
				<div>

					<button type="button" class="btn btn-sm btn-primary" name="new-user" {{ $create ? null : 'disabled' }}>
						<span class="fa fa-plus-circle"></span> New User
					</button>
				
				</div>

			</div>

			<div class="col-md-12">

				<table class="table table-hover" name="users-table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Updated</th>
							<th>Created</th>
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

	<script type="text/javascript">

		/*
		 * for the Users Table
		 */
		var tbody 		= $('table[name=users-table]').find('tbody');
		/*
		 * for the Modal
		 */
	 	var modal 		= $('#adminModal');
		var mTitle 		= $('h4.modal-title');
		var mBody 		= $('div.modal-body');
		var mNotif 		= $('div.modal-notification');
		var mFooter 	= $('div.modal-footer');
		var mform		= $('<form></form>', {name:"form-user"} );
		var csrf 		= '{{ csrf_field() }}';
		/*
		 * For creating a user
		 */
		var saveBtn 	= $('<button></button>',{type:"button", name:"save", text:"Save", class:"btn btn-primary"});
		var updateBtn 	= $('<button></button>',{type:"button", name:"update", text:"Update", class:"btn btn-primary"});
		var deleteBtn 	= $('<button></button>',{type:"button", name:"delete", text:"Delete", class:"btn btn-danger"});
		var cancelBtn 	= $('<button></button>',{type:"button", name:"cancel", text:"Cancel", class:"btn btn-secondary",'data-dismiss':"modal"});
		var nameFrmGrp 	= $('<div></div>',{class:"form-group"}).append($('<label></label>', {for:"name", text:"Name"}));
		var nameTxt 	= $('<input></input', {type:"text", name:"name", id:"name", class:"form-control"});
		var emailFrmGrp = $('<div></div>',{class:"form-group"}).append($('<label></label>', {for:"email", text:"Email"}));
		var emailTxt 	= $('<input></input', {type:"text", name:"email", id:"email", class:"form-control"});
		var pwFrmGrp 	= $('<div></div>',{class:"form-group"}).append($('<label></label>', {for:"password", text:"Password"}));
		var pwField		= $('<input></input', {type:"password", name:"password", id:"password", class:"form-control"});
		var pwCfFrmGrp 	= $('<div></div>',{class:"form-group"}).append($('<label></label>', {for:"password_confirmation", text:"Confirm Password"}));
		var pwCfField	= $('<input></input', {type:"password", name:"password_confirmation", id:"password_confirmation", class:"form-control"});
		var idFrmGrp 	= $('<div></div>',{class:"form-group"});
		var idField		= $('<input></input', {type:"hidden", name:"user_id", id:"user_id", class:"form-control"});
		/*
		 * For dropdown overlay table cell
		 * 
		 */
		 var caret		=$('<span></span', {class:"caret"});
		 var sr 	 	=$('<span></span', {class:"sr-only", text:"Toggle Dropdown"});
		 var btnLeft 	=$('<button></button>', { type:"button", class:"btn btn-sm btn-default", text:"Action"});
		 var btnRight	=$('<button></button>', { type:"button", class:"btn btn-sm btn-info dropdown-toggle", 'data-toggle':"dropdown"}).append(caret, sr);
		 var aViewInfo 	=$('<a></a>',{ href:"javascript:;", text:"View Information", name:"view-info"});
		 var liViewInfo =$('<li></li>').append(aViewInfo);
		 var liDivider	=$('<li></li>',{class:"divider"});
		 var aEdit	 	=$('<a></a>',{ href:"javascript:;", text:"Edit", name:"{{ $update ? 'edit-user' : null }}"});
		 var liEdit  	=$('<li class="{{ $update ? null : "disabled" }}"></li>').append(aEdit);
		 var aDelete 	=$('<a></a>',{ href:"javascript:;", text:"Delete", name:"{{ $delete ? 'delete-user' : null }}"});
		 var liDelete	=$('<li class="{{ $delete ? null : "disabled" }}"></li>').append(aDelete);
		 var ul 	 	=$('<ul></ul>',{class:"dropdown-menu", role:"menu"}).append(liEdit , liDelete, liDivider, liViewInfo);
		 var btnGrp  	=$('<div></div>', { class:"btn-group"}).append(btnLeft, btnRight, ul);

		$(function(){

			loadData();

			$('button[name=new-user]').on('click', function(){
				
				cleanModal();
				
				mTitle.text('Add User');
				mBody.append(
						mform.append(csrf,
						nameFrmGrp.append(nameTxt),
						emailFrmGrp.append(emailTxt),
						pwFrmGrp.append(pwField),
						pwCfFrmGrp.append(pwCfField)
					));
				mFooter.append(saveBtn);
				modal.modal('show');

				saveBtn.on('click', function(){
					
					$.ajax({
						type:"POST",
						url:"{{ url('/admincontrol/user/create') }}",
						data: $('form[name=form-user]').serializeArray(),
						dataType: "JSON",
						beforeSend: function(){

							cleanNotification();
						},
						success: function(data, textStatus , jqXHR){

							mNotif.append($('<ul></ul>', {name:"success-msgs", class:"text-success", style:"list-style:none"}));

							$.each(data, function(key, value){

								$('ul[name=success-msgs]').append($('<li></li>',{text: value}));

							});

							loadData();
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

			$(document).on('click','a[name=edit-user]', function(){
				cleanModal();

				var id =$(this).parents('td').data('id');
				var email = $(this).parents('td').data('email');
				var name =$(this).parents('td').data('name');
				
				mTitle.text('Edit User');
				mBody.append(
						mform.append(csrf,
						nameFrmGrp.append(nameTxt.val(name)),
						emailFrmGrp.append(emailTxt.val(email)),
						pwFrmGrp.append(pwField),
						pwCfFrmGrp.append(pwCfField),
						idFrmGrp.append(idField.val(id))
					));
				mFooter.append(updateBtn);
				modal.modal('show');

				updateBtn.on('click', function(){

					$.ajax({
						type:"POST",
						url:"{{ url('/admincontrol/user/update') }}",
						data: $('form[name=form-user]').serializeArray(),
						dataType: "JSON",
						beforeSend: function(){
							cleanNotification();
						},
						success: function(data, textStatus , jqXHR){

							mNotif.append($('<ul></ul>', {name:"success-msgs", class:"text-success", style:"list-style:none"}));

							$.each(data, function(key, value){

								$('ul[name=success-msgs]').append($('<li></li>',{text: value}));

							});

							loadData();

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

			$(document).on('click','a[name=delete-user]', function(){

				cleanModal();

				var id =$(this).parents('td').data('id');

				var question = $('<p></p>', {text:"You are about to delete one user, this procedure is irreversible."});
				mTitle.text('Confirm Delete');
				mBody.append(
						mform.append(csrf,
						question, 
						idFrmGrp.append(idField.val(id))
					));
				mFooter.append(cancelBtn, deleteBtn);
				modal.modal('show');

				deleteBtn.on('click', function(){

					$.ajax({
						type:"POST",
						url:"{{ url('/admincontrol/user/remove') }}",
						data: $('form[name=form-user]').serializeArray(),
						dataType: "JSON",
						beforeSend: function(){

							cleanNotification();

						},
						success: function(data){

							loadData();

							modal.modal('hide');
							
						}
					});

				});


			});

			function loadData(){

				$.ajax({
					type:"GET",
					url:"{{ url('/admincontrol/user/data') }}",
					data:{ 
						"_token" : "{{ csrf_token() }}"
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
								$('<td></td>',{ text:value['name']}),
								$('<td></td>',{ text:value['email']}),
								$('<td></td>',{ text:value['updated'] }),
								$('<td></td>',{ text:value['created'] }),
								$('<td></td>',{ 
									'name':"action", 
									'data-id':value['id'], 
									'data-name':value['name'],
									'data-email':value['email'] 
								})
							));
						});

						$('td[name=action]').append(btnGrp);

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

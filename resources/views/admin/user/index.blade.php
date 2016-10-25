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
					<button type="button" class="btn btn-primary"><span class="fa fa-plus-circle"></span> New User</button>
				</div>

			</div>

			<div class="col-md-12">

				<table class="table table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Updated</th>
							<th>Created</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@if($users)

							@foreach($users as $user)

								<tr>
									<td>{{ $user->name }}</td>
									<td>{{ $user->email }}</td>
									<td>{{ $user->updated_at->diffForHumans() }}</td>
									<td>{{ $user->created_at->diffForHumans() }}</td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-info">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
										    	<span class="caret"></span>
										    	<span class="sr-only">Toggle Dropdown</span>
										  	</button>
										  	<ul class="dropdown-menu" role="menu">
										    	<li><a href="#">Change Password</a></li>
										    	<li class="divider"></li>
										    	<li><a href="#">Edit User</a></li>
										    	<li><a href="#">Delete User</a></li>
										  	</ul>
										</div>
									</td>
								</tr>

							@endforeach
							
						@endif
					</tbody>
				</table>

			</div>

		</div>

	</div>

@endsection

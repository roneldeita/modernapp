@extends('layouts.admin')

@section('styles')

<style type="text/css">
	
	.icon{

		font-size: 70px;
		float: right;
		position:static;
		margin-top: -50px;

	}

</style>

@endsection

@section('content')
	
	<div class="container">

		<div class="row">
		
			<div class="col-md-12">
				
				<div class = "page-header">

					<h1>Dashboard</h1>

				</div>

			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h1>{{ $info['users'] }}</h1>
						<p>{{ $info['users'] > 1 ? 'Users' : 'User' }} <span class="fa fa-users icon"></span></p>
					</div>
					<div class="panel-body">
				    	<a href="{{ url('admincontrol/user') }}">View Details <span class="pull-right fa fa-chevron-circle-right"></span></a>
				  	</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h1>{{ $info['posts'] }}</h1>
						<p>{{ $info['posts'] > 1 ? 'Posts' : 'Post' }} <span class="fa fa-comments icon"></span></p>
					</div>
					<div class="panel-body">
				    	<a href="{{ url('admincontrol/post') }}">View Details <span class="pull-right fa fa-chevron-circle-right"></span></a>
				  	</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h1>{{ $info['modules'] }}</h1>
						<p>{{ $info['modules'] > 1 ? 'Modules' : 'Module' }} <span class="fa fa-cubes icon"></span></p>
					</div>
					<div class="panel-body">
				    	<a href="{{ url('admincontrol/access') }}">View Details <span class="pull-right fa fa-chevron-circle-right"></span></a>
				  	</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h1>{{ $info['themes'] }}</h1>
						<p>{{ $info['themes'] > 1 ? 'Themes' : 'Theme' }} <span class="fa fa-paint-brush icon"></span></p>
					</div>
					<div class="panel-body">
				    	<a href="{{ url('admincontrol/appearance') }}">View Details <span class="pull-right fa fa-chevron-circle-right"></span></a>
				  	</div>
				</div>
			</div>

		</div>

	</div>

@endsection
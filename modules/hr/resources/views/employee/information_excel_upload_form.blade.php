<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>HRKIT</title>

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

	<!-- Styles -->
	<style>
		html, body {
			background-color: #fff;
			color: #636b6f;
			font-family: 'Nunito', sans-serif;
			font-weight: 200;
			height: 100vh;
			margin: 0;
		}

		.full-height {
			height: 100vh;
		}

		.flex-center {
			align-items: center;
			display: flex;
			justify-content: center;
		}

		.position-ref {
			position: relative;
		}

		.top-right {
			position: absolute;
			right: 10px;
			top: 18px;
		}

		.content {
			text-align: center;
		}

		.title {
			font-size: 84px;
		}

		.links > a {
			color: #636b6f;
			padding: 0 25px;
			font-size: 13px;
			font-weight: 600;
			letter-spacing: .1rem;
			text-decoration: none;
			text-transform: uppercase;
		}

		.m-b-md {
			margin-bottom: 30px;
		}

		.text-danger {
			color: #dc222a;
		}
		.callout-success {
			color: #16682d;
			background-color: #affeb6;
		}
		.callout-danger {
			color: #dc222a;
			background-color: #fe9ca4;
		}
	</style>
</head>
<body>
<div class="flex-center position-ref full-height">

	<div class="content">
		<div>
			<h2>Upload Employee Information Excel</h2>
		</div>
		<div class="row">
			<div class="flash-message">
				@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					@if(Session::has('alert-' . $msg))
						<div class="callout callout-{{ $msg }}">
							<p class="text-left">{{ Session::get('alert-' . $msg) }}</p>
						</div>
					@endif
				@endforeach
			</div>
			<form action="{{ url('/employee-information-excel-upload') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="col-md-offset-4 col-md-4">
					<input type="file" name="employee_excel" class="form-control">
					<button type="submit" class="btn btn-default">Submit</button>
					@if($errors->any())
						<ul>
							{!! implode('', $errors->all('<li class="text-danger">:message</li>'))  !!}
						</ul>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>

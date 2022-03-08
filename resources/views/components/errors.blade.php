@if ($errors->any())
	<div class = "card mb-2">
		<div class = "alert alert-danger m-0">
			@foreach ($errors->all() as $error)
				<div>{{$error}}</div>
			@endforeach
		</div>
	</div>
@endif
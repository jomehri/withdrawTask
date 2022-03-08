<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ali Jomehri Tax Calculator Task</title>

	    <!-- Styles -->
	    <link media="all" rel="stylesheet" href="{{ asset("css/app.css") }}" />

	    <!-- JS -->
	    <script defer="defer" type="application/javascript"  src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>

	    <div class="container mt-5">

		    @include("components.errors")

		    <div class="h4">
			    Import your Money transfer CSV sheet to calculate taxes
		    </div>
		    <div class="h5">
			    This assignment is done by ajomehri@gmail.com
		    </div>
		    <div class="card">
			    <div class="card-header">
				    CSV Import
			    </div>
			    <div class="card-body">
				    <div class="row">
					    <form method="POST" action="{{ route('tax.store') }}" enctype="multipart/form-data">
						    @csrf
						    <div class="form-group">
							    <input type="file" name="file" class="form-control-file">
						    </div>

						    <button type="submit" class="btn btn-primary mt-2">Process</button>
					    </form>
				    </div>
			    </div>
		    </div>

		    @include("tax.result", ['rows' => Session('rows')])

	    </div>
    </body>
</html>

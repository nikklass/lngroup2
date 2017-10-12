

@if (session('error') || count($errors))
  
	@if (session('error'))

	  <div class="alert alert-danger text-center">
	      {!! session('error') !!}
	  </div>

	@else
	  
	  <div class="alert alert-danger text-center">
	      <ul>
		      @foreach ($errors->all() as $error)
				<li>{!! $error !!}</li>
		      @endforeach
	      </ul>
	  </div>

	@endif

@endif
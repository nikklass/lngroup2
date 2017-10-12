@if (session('success'))
	
	<script>

		$( document ).ready(function() {
		    //show message if any

		    $.toast().reset('all');
				$("body").removeAttr('class');
				$.toast({
		            heading: 'Success',
		            text: '{!! session('success') !!}',
		            position: 'top-right',
		            loaderBg:'#fec107',
		            icon: 'success',
		            hideAfter: 15500, 
		            stack: 6
		        });
				return false;
		});

	</script>

@endif

@if (session('errorz'))

	<script>

		$( document ).ready(function() {
		    //show message if any

			$.toast().reset('all');
				$("body").removeAttr('class');
				$.toast({
		            heading: 'An Error Occured',
		            text: "{!! $error_msg !!}",
		            position: 'top-right',
		            loaderBg:'#fec107',
		            icon: 'error',
		            hideAfter: 15500, 
		            stack: 6
		        });
				return false;

		});

	</script>
	
@endif
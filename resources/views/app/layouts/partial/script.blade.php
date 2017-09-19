<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>
<script src="{{ asset('assets/app/vue/components/sidebar-detail.js') }}"></script>

<script type="text/javascript">
	// refreshToken
	var csrfToken = $('[name="csrf_token"]').attr('content');

	setInterval(refreshToken, 3600000); // 1 hour 

	function refreshToken(){
		$.get('refresh-csrf').done(function(data){
			csrfToken = data; // the new token
		});
	}
	setInterval(refreshToken, 3600000); // 1 hour 
</script>

<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>
<script src="{{ asset('assets/app/vue/components/sidebar-detail.js') }}"></script>

<script type="text/javascript">
	function refreshToken(){
		$.get('refresh-csrf').done(function(data){
			$('input[name="_token"]').val(data); // the new token
		});
	}

	setInterval(refreshToken, 300000); // every 5 min
</script>

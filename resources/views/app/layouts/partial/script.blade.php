<?php
	Cookie::queue('main_js', asset('assets/app/js/kotakarsip.min.js'));
	Cookie::queue('sidebar', asset('assets/app/vue/components/sidebar-detail.js'));
?>

<script src="{{ !empty(Cookie::get('main_js')) ? Cookie::get('main_js') : asset('assets/app/js/kotakarsip.min.js') }}"></script>
<script src="{{ !empty(Cookie::get('sidebar')) ? Cookie::get('sidebar') : asset('assets/app/vue/components/sidebar-detail.js') }}"></script>
<script>
	$(".nav-toggle").clicktoggle(navShow, navClose);
</script>
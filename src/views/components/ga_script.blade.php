@if($gaId = setting('analytic_id', null))
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script src="https://www.googletagmanager.com/gtag/js?id={{$gaId}}"
	        async></script>
	<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {dataLayer.push(arguments);}

  gtag('js', new Date());

  gtag('config', '{{$gaId}}');
</script>
@endif

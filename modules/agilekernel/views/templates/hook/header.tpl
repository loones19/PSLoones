{if isset($ak_gmap_apikey) && !empty($ak_gmap_apikey)}
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={$ak_gmap_apikey}"></script>
{/if}

{if isset($ak_grc_site_key) && !empty($ak_grc_site_key)}
	<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=AgileCaptchaCallback&render=explicit&hl={$ak_grc_language}"></script>
	<script type="text/javascript">
		var AgileCaptchaCallback = function () {
			$.each($("[id^=AgileCaptcha_]"), function (idx) {
				grecaptcha.render(this.id, { "sitekey": "{$ak_grc_site_key}" });
			});
		};
	</script>
{/if}

{* =========================================
{if isset($ak_hreflangs) && count($ak_hreflangs) > 0}
	{foreach from=$ak_hreflangs item=href}
		<link rel="alternate" hreflang="{$href['code']}" href="{$href['url']}" />
	{/foreach}
{/if} 

============================================*}

{**** for back office we are not able to use this because it is rendered after the AdminControlelr SetMedia() *******
{if isset($ak_gmap_apikey) && !empty($ak_gmap_apikey)}
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={$ak_gmap_apikey}"></script>
{/if}
*****************************************************************}
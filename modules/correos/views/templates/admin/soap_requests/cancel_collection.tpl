{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://www.correos.es/ServicioPuertaAPuertaBackOffice">
   <soapenv:Header/>
   <soapenv:Body>
      <ser:AnulacionRecogidaPaPRequest>
         <FechaOperacion>{$smarty.now|date_format:"%d-%m-%Y %H:%M:%S"|escape:'html':'UTF-8'}</FechaOperacion>
         <NumContrato>{$correos_config.contract_number|escape:'htmlall':'UTF-8'}</NumContrato>
         <NumDetallable>{$correos_config.client_number|escape:'htmlall':'UTF-8'}</NumDetallable>
         <CodUsuario>{$correos_config.correos_vuser|escape:'htmlall':'UTF-8'}</CodUsuario>
         <CodSolicitud>{$confirmation_code|escape:'htmlall':'UTF-8'}</CodSolicitud>
         <ReferenciaRecogida>{$confirmation_code|escape:'htmlall':'UTF-8'}</ReferenciaRecogida>
      </ser:AnulacionRecogidaPaPRequest>
   </soapenv:Body>
</soapenv:Envelope>
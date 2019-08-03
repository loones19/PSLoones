{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <prer:info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:prer="http://www.correos.es/iris6/services/preregistroetiquetas">
<soapenv:Header/>
   <soapenv:Body>
        <prer:PreregistroEnvioMultibulto>
            <prer:FechaOperacion>{$smarty.now|date_format:"%d-%m-%Y %H:%M:%S"|escape:'html':'UTF-8'}</prer:FechaOperacion>
            <prer:CodEtiquetador>{$correos_config.correos_key|escape:'html':'UTF-8'}</prer:CodEtiquetador>
            <prer:ModDevEtiqueta>2</prer:ModDevEtiqueta>
            <prer:Care>000000</prer:Care>
            <prer:TotalBultos>{$shipping_val.parcel_info|count}</prer:TotalBultos>
            <prer:Remitente>
                <prer:Identificacion>
                    <prer:Nombre>{$shipping_val.sender_firstname|escape:'html':'UTF-8'}</prer:Nombre>
                    <prer:Apellido1>{$shipping_val.sender_lastname1|escape:'html':'UTF-8'}</prer:Apellido1>
                    <prer:Apellido2>{$shipping_val.sender_lastname2|escape:'html':'UTF-8'}</prer:Apellido2>
                    <prer:Nif>{$shipping_val.sender_dni|escape:'html':'UTF-8'}</prer:Nif>
                    <prer:Empresa>{$shipping_val.sender_company|escape:'html':'UTF-8'}</prer:Empresa>
                    <prer:PersonaContacto>{$shipping_val.sender_contact_person|escape:'html':'UTF-8'}</prer:PersonaContacto>
                </prer:Identificacion>
                <prer:DatosDireccion>
                    <prer:Direccion>{$shipping_val.sender_address|escape:'html':'UTF-8'}</prer:Direccion>
                    <prer:Localidad>{$shipping_val.sender_city|escape:'html':'UTF-8'}</prer:Localidad>
                    <prer:Provincia>{$shipping_val.sender_state|escape:'html':'UTF-8'}</prer:Provincia>
                </prer:DatosDireccion>
                <prer:CP>{$shipping_val.sender_cp|escape:'html':'UTF-8'}</prer:CP>
                <prer:Telefonocontacto>{$shipping_val.sender_phone|escape:'html':'UTF-8'}</prer:Telefonocontacto>
                <prer:Email>{$shipping_val.sender_email|escape:'html':'UTF-8'}</prer:Email>
                <prer:DatosSMS>
                    <prer:NumeroSMS>{$shipping_val.sender_mobile|escape:'html':'UTF-8'}</prer:NumeroSMS>
                    <prer:Idioma>1</prer:Idioma>
                </prer:DatosSMS>
            </prer:Remitente> 
            <prer:Destinatario> 
                <prer:Identificacion> 
                    {if empty($shipping_val.customer_company)}
                    <prer:Nombre>{$shipping_val.customer_firstname|escape:'html':'UTF-8'}</prer:Nombre>
                    <prer:Apellido1>{$shipping_val.customer_lastname1|escape:'html':'UTF-8'}</prer:Apellido1>
                    <prer:Apellido2>{$shipping_val.customer_lastname2|escape:'html':'UTF-8'}</prer:Apellido2>
                    {/if}
                    {if !empty($shipping_val.customer_company)}
                    <prer:Empresa>{$shipping_val.customer_company|escape:'html':'UTF-8'}</prer:Empresa>
                    <prer:PersonaContacto>{$shipping_val.customer_firstname|escape:'html':'UTF-8'} {$shipping_val.customer_lastname1|escape:'html':'UTF-8'} {$shipping_val.customer_lastname2|escape:'html':'UTF-8'}</prer:PersonaContacto>
                    {/if}
                </prer:Identificacion>
                <prer:DatosDireccion>
                    <prer:Direccion>{$shipping_val.delivery_address|escape:'html':'UTF-8'}</prer:Direccion>
                    <prer:Localidad>{$shipping_val.delivery_city|escape:'html':'UTF-8'}</prer:Localidad>
                    <prer:Provincia>{$shipping_val.delivery_state|escape:'html':'UTF-8'}</prer:Provincia>
                </prer:DatosDireccion>
                <prer:CP>{$shipping_val.delivery_postcode|escape:'html':'UTF-8'}</prer:CP>
                <prer:ZIP>{$shipping_val.delivery_zip|escape:'html':'UTF-8'}</prer:ZIP>
                <prer:Pais>{$shipping_val.country_iso|escape:'html':'UTF-8'}</prer:Pais>
                <prer:Telefonocontacto>{$shipping_val.phone|escape:'html':'UTF-8'}</prer:Telefonocontacto>
                <prer:Email>{$shipping_val.email|escape:'html':'UTF-8'}</prer:Email>
                <prer:DatosSMS>
                    <prer:NumeroSMS>{$shipping_val.mobile|escape:'html':'UTF-8'}</prer:NumeroSMS>
                    <prer:Idioma>{$shipping_val.mobile_lang|escape:'html':'UTF-8'}</prer:Idioma>
                </prer:DatosSMS>
            </prer:Destinatario> 
            <prer:Envios>
            
            {foreach from=$shipping_val.parcel_info item=item name=shipping}
              <prer:Envio>
                  <prer:NumBulto>{$smarty.foreach.shipping.index|intval + 1}</prer:NumBulto>
                  <prer:ReferenciaCliente>{$item.package_reference|escape:'html':'UTF-8'}</prer:ReferenciaCliente>
                  <prer:Pesos>
                      <prer:Peso>
                          <prer:TipoPeso>R</prer:TipoPeso>
                          <prer:Valor>{$item.weight|escape:'html':'UTF-8'}</prer:Valor>
                      </prer:Peso>
                  </prer:Pesos>
                  <prer:Largo>{$item.long|escape:'html':'UTF-8'}</prer:Largo>
                  <prer:Alto>{$item.height|escape:'html':'UTF-8'}</prer:Alto>
                  <prer:Ancho>{$item.width|escape:'html':'UTF-8'}</prer:Ancho>
                  <prer:Aduana>
                      <prer:TipoEnvio>{$item.customs_type|escape:'html':'UTF-8'}</prer:TipoEnvio>
                      <prer:EnvioComercial>{$item.customs_comercial|escape:'html':'UTF-8'}</prer:EnvioComercial>
                      <prer:FacturaSuperiora500>{$item.customs_fra500|escape:'html':'UTF-8'}</prer:FacturaSuperiora500>
                      <prer:DUAConCorreos>{$item.customs_duacorreos|escape:'html':'UTF-8'}</prer:DUAConCorreos>
                      <prer:DescAduanera>
                          <prer:DATOSADUANA>
                              <prer:Cantidad>{$item.customs_product_qty|escape:'html':'UTF-8'}</prer:Cantidad>
                              <prer:Descripcion>{$item.customs_description|escape:'html':'UTF-8'}</prer:Descripcion>
                              <prer:Pesoneto>{$item.customs_product_weight|escape:'html':'UTF-8'}</prer:Pesoneto>
                              <prer:Valorneto>{$item.customs_product_value|escape:'html':'UTF-8'}</prer:Valorneto>
                          </prer:DATOSADUANA>
                      </prer:DescAduanera>
                  </prer:Aduana>
                  <prer:Observaciones1>{$item.package_observations|escape:'html':'UTF-8'}</prer:Observaciones1>
                  <prer:InstruccionesDevolucion>D</prer:InstruccionesDevolucion>
              </prer:Envio>
              {/foreach}
              </prer:Envios>
            <prer:EntregaParcial>{$shipping_val.partial_delivery|escape:'html':'UTF-8'}</prer:EntregaParcial>
           <prer:CodProducto>{$carrier_code|escape:'html':'UTF-8'}</prer:CodProducto>
           <prer:ReferenciaExpedicion>{$shipping_val.order_reference|escape:'html':'UTF-8'}</prer:ReferenciaExpedicion>
           <prer:ModalidadEntrega>{$shipping_val.delivery_mode|escape:'html':'UTF-8'}</prer:ModalidadEntrega>
           <prer:TipoFranqueo>FP</prer:TipoFranqueo>
           <prer:OficinaElegida>{$shipping_val.id_office|escape:'html':'UTF-8'}</prer:OficinaElegida>
           <prer:AdmisionHomepaq>{$shipping_val.homepaq_admission|escape:'html':'UTF-8'}</prer:AdmisionHomepaq>
           <prer:CodigoHomepaq>{$shipping_val.homepaq_code|escape:'html':'UTF-8'}</prer:CodigoHomepaq>
           <prer:ToquenIdCorPaq>{$shipping_val.homepaq_token|escape:'html':'UTF-8'}</prer:ToquenIdCorPaq>
           <prer:ValoresAnadidos>
            <prer:ImporteSeguro>{$shipping_val.insurance_value|escape:'html':'UTF-8'}</prer:ImporteSeguro>
            <prer:Reembolso> 
                <prer:TipoReembolso>{$shipping_val.cashondelivery_type|escape:'html':'UTF-8'}</prer:TipoReembolso>
                <prer:Importe>{$shipping_val.cashondelivery_value|escape:'html':'UTF-8'}</prer:Importe>
                <prer:NumeroCuenta>{$shipping_val.cashondelivery_bankac|escape:'html':'UTF-8'}</prer:NumeroCuenta>
                <prer:Transferagrupada>{if $shipping_val.cashondelivery_value}S{/if}</prer:Transferagrupada>
            </prer:Reembolso>
            <prer:FranjaHorariaConcertada>{$shipping_val.id_schedule|escape:'html':'UTF-8'}</prer:FranjaHorariaConcertada>
          </prer:ValoresAnadidos>
                  
        </prer:PreregistroEnvioMultibulto>
    </soapenv:Body>
</soapenv:Envelope>
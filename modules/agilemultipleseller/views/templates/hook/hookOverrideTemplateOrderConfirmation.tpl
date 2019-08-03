{extends file='page.tpl'}

{block name='page_content_container' prepend}
    <section id="content-hook_order_confirmation" class="card">
      <div class="card-block">
        <div class="row">
          <div class="col-md-12">
            <h3 class="h1 card-title">
              <i class="material-icons done">&#xE876;</i>{l s='Your order is confirmed' mod='agilemultipleseller'}
            </h3>
            <p>
              {l s='An email has been sent to your mail address' mod='agilemultipleseller'}&nbsp;{$customer.email}
            {$HOOK_ORDER_CONFIRMATION nofilter}
          </div>
        </div>
      </div>
    </section>
{/block}

{block name='page_content_container'}
  {foreach from=$orders item=order}
  <section id="content" class="page-content page-order-confirmation card">
    <div class="card-block">
      <div class="row">

        {block name='order_confirmation_table'}
          {include
            file='checkout/_partials/order-confirmation-table.tpl'
            products=$order.products
            subtotals=$order.subtotals
            totals=$order.totals
            labels=$order.labels
            add_product_link=false
          }
        {/block}

        <div id="order-details" class="col-md-4">
          <h3 class="h3 card-title">{l s='Order details' mod='agilemultipleseller'}:</h3>
          <ul>
            <li>{l s='Order reference:' mod='agilemultipleseller'}&nbsp;{$order.details.reference}</li>
            <li>{l s='Payment method:' mod='agilemultipleseller'}&nbsp;{$order.details.payment}</li>
            {if !$order.details.is_virtual}
              <li>
                {l s='Shipping method:' mod='agilemultipleseller'}&nbsp;{$order.carrier.name}<br>
                <em>{$order.carrier.delay}</em>
              </li>
            {/if}
          </ul>
        </div>

      </div>
    </div>
  </section>
  {/foreach}

  {if ! empty($HOOK_PAYMENT_RETURN)}
  <section id="content-hook_payment_return" class="card definition-list">
    <div class="card-block">
      <div class="row">
        <div class="col-md-12">
          {$HOOK_PAYMENT_RETURN nofilter}
        </div>
      </div>
    </div>
  </section>
  {/if}

  {if $customer.is_guest}
    <div id="registration-form" class="card">
      <div class="card-block">
        <h4 class="h4">{l s='Save time on your next order, sign up now' mod='agilemultipleseller'}</h4>
        {render file='customer/_partials/customer-form.tpl' ui=$register_form}
      </div>
    </div>
  {/if}

  {hook h='displayOrderConfirmation1'}

  <section id="content-hook-order-confirmation-footer">
    {hook h='displayOrderConfirmation2'}
  </section>
{/block}

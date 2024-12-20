<?php use Core\FH; ?>
<?php $this->setSiteTitle('Checkout')?>

<?php $this->start('head')?>
  <script src="https://js.braintreegateway.com/web/dropin/1.17.2/js/dropin.min.js"></script>
<?php $this->end()?>

<?php $this->start('body')?>
<div class="grid-width">
  <span class="br-row"></span>
  <div class="row">
      <div class="col-md-8">
        <div class="shipping-info-wrapper">
          <h3 class="kanit-medium dark form-heading">Purchase Details</h3>
          <form id="braintreeForm" action="<?=PROOT?>cart/checkout/<?=$this->cartId?>" method="post" >
            <?=FH::csrfInput()?>
            <input type="hidden" name="step" value="2" />
            <input type="hidden" name="payment_method_nonce" id="payment_method_nonce" value="" />
            <input type="hidden" name="name" value="<?=$this->tx->name?>" />
            <input type="hidden" name="shipping_address1" value="<?=$this->tx->shipping_address1?>" />
            <input type="hidden" name="shipping_address2" value="<?=$this->tx->shipping_address2?>" />
            <input type="hidden" name="shipping_city" value="<?=$this->tx->shipping_city?>" />
            <input type="hidden" name="shipping_state" value="<?=$this->tx->shipping_state?>" />
            <input type="hidden" name="shipping_zip" value="<?=$this->tx->shipping_zip?>" />
            <div id="dropin-container"></div>
        </div>
        <span class="br-form"></span>
        <button id="submit-button" class="btn btn-lg btn-chkout btn-block dark kanit-medium">Submit Payment</button>
        </form>
      </div>

      <div class="col-md-4"><?php $this->partial('cart','product_preview')?></div>
    </div>
    <span class="br-row"></span>
</div>



  <script>
      var button = document.querySelector('#submit-button');
      var form = document.getElementById("braintreeForm");
      form.addEventListener('submit',function(event){
        event.preventDefault();
      });
      braintree.dropin.create({
        authorization: '<?=$this->gatewayToken?>',
        container: '#dropin-container'
      }, function (createErr, instance) {
        button.addEventListener('click', function () {
          instance.requestPaymentMethod(function (err, payload) {
            var nonceInput = document.getElementById("payment_method_nonce");
            nonceInput.value = payload.nonce;
            console.log(form);
            form.submit();
          });
        });
      });
    </script>
<?php $this->end()?>

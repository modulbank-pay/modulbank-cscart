<div class="control-group">
    <label class="control-label" for="modulbankpayment_merchant">{__("modulbankpayment_merchant")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchant]" id="modulbankpayment_merchant" value="{$processor_params.merchant}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_secret_key">{__("modulbankpayment_secret_key")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][secret_key]" id="modulbankpayment_secret_key" value="{$processor_params.secret_key}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_test_secret_key">{__("modulbankpayment_test_secret_key")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][test_secret_key]" id="modulbankpayment_test_secret_key" value="{$processor_params.test_secret_key}"  size="60">
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="modulbankpayment_mode">{__("modulbankpayment_mode")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][mode]" id="modulbankpayment_mode">
            <option value="test" {if $processor_params.mode == "test"}selected="selected"{/if}>{__("modulbankpayment_mode_test")}</option>
            <option value="prod" {if $processor_params.mode == "prod"}selected="selected"{/if}>{__("modulbankpayment_mode_prod")}</option>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="modulbankpayment_preauth">{__("modulbankpayment_preauth")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][preauth]" id="modulbankpayment_preauth">
            <option value="0" {if $processor_params.preauth == "0"}selected="selected"{/if}>{__("modulbankpayment_preauth_no")}</option>
            <option value="1" {if $processor_params.preauth == "1"}selected="selected"{/if}>{__("modulbankpayment_preauth_yes")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_success_url">{__("modulbankpayment_success_url")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][success_url]" id="modulbankpayment_success_url" value="{$processor_params.success_url}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_fail_url">{__("modulbankpayment_fail_url")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][fail_url]" id="modulbankpayment_fail_url" value="{$processor_params.fail_url}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_cancel_url">{__("modulbankpayment_cancel_url")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][cancel_url]" id="modulbankpayment_cancel_url" value="{$processor_params.cancel_url}"  size="60">
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="modulbankpayment_sno">{__("modulbankpayment_sno")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][sno]" id="modulbankpayment_sno">
            <option value="osn" {if $processor_params.sno == "osn"}selected="selected"{/if}>{__("modulbankpayment_sno_osn")}</option>

            <option value="usn_income" {if $processor_params.sno == "usn_income"}selected="selected"{/if}>{__("modulbankpayment_sno_usn_income")}</option>

            <option value="usn_income_outcome" {if $processor_params.sno == "usn_income_outcome"}selected="selected"{/if}>{__("modulbankpayment_sno_usn_income_outcome")}</option>

            <option value="envd" {if $processor_params.sno == "envd"}selected="selected"{/if}>{__("modulbankpayment_sno_envd")}</option>

            <option value="esn" {if $processor_params.sno == "esn"}selected="selected"{/if}>{__("modulbankpayment_sno_esn")}</option>

            <option value="patent" {if $processor_params.sno == "patent"}selected="selected"{/if}>{__("modulbankpayment_sno_patent")}</option>

        </select>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="modulbankpayment_product_vat">{__("modulbankpayment_product_vat")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][product_vat]" id="modulbankpayment_product_vat">
            <option value="0" {if $processor_params.product_vat == "0"}selected="selected"{/if}>{__("modulbankpayment_vat_catalog")}</option>

            <option value="none" {if $processor_params.product_vat == "none"}selected="selected"{/if}>{__("modulbankpayment_vat_none")}</option>

            <option value="vat0" {if $processor_params.product_vat == "vat0"}selected="selected"{/if}>{__("modulbankpayment_vat_vat0")}</option>

            <option value="vat10" {if $processor_params.product_vat == "vat10"}selected="selected"{/if}>{__("modulbankpayment_vat_vat10")}</option>

            <option value="vat20" {if $processor_params.product_vat == "vat20"}selected="selected"{/if}>{__("modulbankpayment_vat_vat20")}</option>

            <option value="vat110" {if $processor_params.product_vat == "vat110"}selected="selected"{/if}>{__("modulbankpayment_vat_vat110")}</option>

            <option value="vat120" {if $processor_params.product_vat == "vat120"}selected="selected"{/if}>{__("modulbankpayment_vat_vat120")}</option>


        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_delivery_vat">{__("modulbankpayment_delivery_vat")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][delivery_vat]" id="modulbankpayment_delivery_vat">
            <option value="0" {if $processor_params.delivery_vat == "0"}selected="selected"{/if}>{__("modulbankpayment_vat_selivery_settings")}</option>

            <option value="none" {if $processor_params.delivery_vat == "none"}selected="selected"{/if}>{__("modulbankpayment_vat_none")}</option>

            <option value="vat0" {if $processor_params.delivery_vat == "vat0"}selected="selected"{/if}>{__("modulbankpayment_vat_vat0")}</option>

            <option value="vat10" {if $processor_params.delivery_vat == "vat10"}selected="selected"{/if}>{__("modulbankpayment_vat_vat10")}</option>

            <option value="vat20" {if $processor_params.delivery_vat == "vat20"}selected="selected"{/if}>{__("modulbankpayment_vat_vat20")}</option>

            <option value="vat110" {if $processor_params.delivery_vat == "vat110"}selected="selected"{/if}>{__("modulbankpayment_vat_vat110")}</option>

            <option value="vat120" {if $processor_params.delivery_vat == "vat120"}selected="selected"{/if}>{__("modulbankpayment_vat_vat120")}</option>


        </select>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="modulbankpayment_payment_method">{__("modulbankpayment_payment_method")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][payment_method]" id="modulbankpayment_payment_method">
            <option value="full_prepayment" {if $processor_params.payment_method == "full_prepayment"}selected="selected"{/if}>{__("modulbankpayment_payment_method_full_prepayment")}</option>

            <option value="partial_prepayment" {if $processor_params.payment_method == "partial_prepayment"}selected="selected"{/if}>{__("modulbankpayment_payment_method_partial_prepayment")}</option>

            <option value="advance" {if $processor_params.payment_method == "advance"}selected="selected"{/if}>{__("modulbankpayment_payment_method_advance")}</option>

            <option value="full_payment" {if $processor_params.payment_method == "full_payment"}selected="selected"{/if}>{__("modulbankpayment_payment_method_full_payment")}</option>

            <option value="partial_payment" {if $processor_params.payment_method == "partial_payment"}selected="selected"{/if}>{__("modulbankpayment_payment_method_partial_payment")}</option>

            <option value="credit" {if $processor_params.payment_method == "credit"}selected="selected"{/if}>{__("modulbankpayment_payment_method_credit")}</option>

            <option value="credit_payment" {if $processor_params.payment_method == "credit_payment"}selected="selected"{/if}>{__("modulbankpayment_payment_method_credit_payment")}</option>

        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_payment_object">{__("modulbankpayment_payment_object")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][payment_object]" id="modulbankpayment_payment_object">
            <option value="commodity" {if $processor_params.payment_object == "commodity"}selected="selected"{/if}>{__("modulbankpayment_payment_object_commodity")}</option>

            <option value="excise" {if $processor_params.payment_object == "excise"}selected="selected"{/if}>{__("modulbankpayment_payment_object_excise")}</option>

            <option value="job" {if $processor_params.payment_object == "job"}selected="selected"{/if}>{__("modulbankpayment_payment_object_job")}</option>

            <option value="service" {if $processor_params.payment_object == "service"}selected="selected"{/if}>{__("modulbankpayment_payment_object_service")}</option>

            <option value="gambling_bet" {if $processor_params.payment_object == "gambling_bet"}selected="selected"{/if}>{__("modulbankpayment_payment_object_gambling_bet")}</option>

            <option value="gambling_prize" {if $processor_params.payment_object == "gambling_prize"}selected="selected"{/if}>{__("modulbankpayment_payment_object_gambling_prize")}</option>

            <option value="lottery" {if $processor_params.payment_object == "lottery"}selected="selected"{/if}>{__("modulbankpayment_payment_object_lottery")}</option>

            <option value="lottery_prize" {if $processor_params.payment_object == "lottery_prize"}selected="selected"{/if}>{__("modulbankpayment_payment_object_lottery_prize")}</option>

            <option value="intellectual_activity" {if $processor_params.payment_object == "intellectual_activity"}selected="selected"{/if}>{__("modulbankpayment_payment_object_intellectual_activity")}</option>

            <option value="payment" {if $processor_params.payment_object == "payment"}selected="selected"{/if}>{__("modulbankpayment_payment_object_payment")}</option>

            <option value="agent_commission" {if $processor_params.payment_object == "agent_commission"}selected="selected"{/if}>{__("modulbankpayment_payment_object_agent_commission")}</option>

            <option value="composite" {if $processor_params.payment_object == "composite"}selected="selected"{/if}>{__("modulbankpayment_payment_object_composite")}</option>

            <option value="another" {if $processor_params.payment_object == "another"}selected="selected"{/if}>{__("modulbankpayment_payment_object_another")}</option>


        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_delivery_payment_object">{__("modulbankpayment_payment_object_delivery")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][delivery_payment_object]" id="modulbankpayment_payment_object">
            <option value="commodity" {if $processor_params.delivery_payment_object == "commodity"}selected="selected"{/if}>{__("modulbankpayment_payment_object_commodity")}</option>

            <option value="excise" {if $processor_params.delivery_payment_object == "excise"}selected="selected"{/if}>{__("modulbankpayment_payment_object_excise")}</option>

            <option value="job" {if $processor_params.delivery_payment_object == "job"}selected="selected"{/if}>{__("modulbankpayment_payment_object_job")}</option>

            <option value="service" {if $processor_params.delivery_payment_object == "service"}selected="selected"{/if}>{__("modulbankpayment_payment_object_service")}</option>

            <option value="gambling_bet" {if $processor_params.delivery_payment_object == "gambling_bet"}selected="selected"{/if}>{__("modulbankpayment_payment_object_gambling_bet")}</option>

            <option value="gambling_prize" {if $processor_params.delivery_payment_object == "gambling_prize"}selected="selected"{/if}>{__("modulbankpayment_payment_object_gambling_prize")}</option>

            <option value="lottery" {if $processor_params.delivery_payment_object == "lottery"}selected="selected"{/if}>{__("modulbankpayment_payment_object_lottery")}</option>

            <option value="lottery_prize" {if $processor_params.delivery_payment_object == "lottery_prize"}selected="selected"{/if}>{__("modulbankpayment_payment_object_lottery_prize")}</option>

            <option value="intellectual_activity" {if $processor_params.delivery_payment_object == "intellectual_activity"}selected="selected"{/if}>{__("modulbankpayment_payment_object_intellectual_activity")}</option>

            <option value="payment" {if $processor_params.delivery_payment_object == "payment"}selected="selected"{/if}>{__("modulbankpayment_payment_object_payment")}</option>

            <option value="agent_commission" {if $processor_params.delivery_payment_object == "agent_commission"}selected="selected"{/if}>{__("modulbankpayment_payment_object_agent_commission")}</option>

            <option value="composite" {if $processor_params.delivery_payment_object == "composite"}selected="selected"{/if}>{__("modulbankpayment_payment_object_composite")}</option>

            <option value="another" {if $processor_params.delivery_payment_object == "another"}selected="selected"{/if}>{__("modulbankpayment_payment_object_another")}</option>


        </select>
    </div>
</div>


{assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses}
<div class="control-group">
    <label class="control-label" for="elm_modulbankpayment_status_success">{__("modulbankpayment_status_success")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][status_success]" id="elm_modulbankpayment_status_success">
            {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($processor_params.status_success) && $processor_params.status_success == $k) || (!isset($processor_params.status_success) && $k == 'P')}selected="selected"{/if}>{$s}</option>
            {/foreach}
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="elm_modulbankpayment_status_capture">{__("modulbankpayment_status_capture")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][status_capture]" id="elm_modulbankpayment_status_capture">
            {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($processor_params.status_capture) && $processor_params.status_capture == $k) || (!isset($processor_params.status_capture) && $k == 'C')}selected="selected"{/if}>{$s}</option>
            {/foreach}
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="elm_modulbankpayment_status_refund">{__("modulbankpayment_status_refund")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][status_refund]" id="elm_modulbankpayment_status_refund">
            {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($processor_params.status_refund) && $processor_params.status_refund == $k) || (!isset($processor_params.status_refund) && $k == 'E')}selected="selected"{/if}>{$s}</option>
            {/foreach}
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_show_custom_pm">{__("modulbankpayment_show_custom_pm")}:</label>
    <div class="controls">
    <input type="checkbox" name="payment_data[processor_params][show_custom_pm]" id="modulbankpayment_show_custom_pm" value="1" {if $processor_params.show_custom_pm == "1"}checked{/if}>
    <p>{__("modulbankpayment_show_custom_pm_desc")}</p>
    </div>
</div>

<div class="control-group" id="modulbankpayment_custom_pm_list_box" style="display:none">
    <label class="control-label"></label>
    <div class="controls">
    <input type="checkbox" name="payment_data[processor_params][card]" value="1" {if $processor_params.card == "1"}checked{/if}>
    &nbsp;{__("modulbankpayment_card_method")}<br>
    <input type="checkbox" name="payment_data[processor_params][sbp]" value="1" {if $processor_params.sbp == "1"}checked{/if}>
    &nbsp;{__("modulbankpayment_sbp_method")}<br>
    <input type="checkbox" name="payment_data[processor_params][googlepay]" value="1" {if $processor_params.googlepay == "1"}checked{/if}>
    &nbsp;{__("modulbankpayment_googlepay_method")}<br>
    <input type="checkbox" name="payment_data[processor_params][applepay]" value="1" {if $processor_params.applepay == "1"}checked{/if}>
    &nbsp;{__("modulbankpayment_applepay_method")}<br>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_logging">{__("modulbankpayment_logging")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][logging]" id="modulbankpayment_logging">
            <option value="0" {if $processor_params.logging == "0"}selected="selected"{/if}>Нет</option>
            <option value="1" {if $processor_params.logging == "1"}selected="selected"{/if}>Да</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="modulbankpayment_log_size_limit">{__("modulbankpayment_log_size_limit")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][log_size_limit]" id="modulbankpayment_log_size_limit" value="{$processor_params.log_size_limit}"  size="60">
        {assign var="modulbank_download_link" value="modulbank.downloadlog"|fn_url:'A'}
<p><a href="{$modulbank_download_link}">{__("modulbankpayment_download_logs")}</a></p>
    </div>

</div>
{literal}
<script>
  jQuery(document).ready(function(){
    var checkbox = jQuery('#modulbankpayment_show_custom_pm');
    var block = jQuery('#modulbankpayment_custom_pm_list_box');
    if (checkbox.attr('checked')) {
      block.show();
    }
    checkbox.change(function(){
      if (this.checked) {
        block.show();
      } else {
        block.hide();
      }
    });
  });
</script>
{/literal}

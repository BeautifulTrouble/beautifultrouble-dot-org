<!--

Pricing

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Show currency code', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Enabling will show the currency code next to product prices. For example, $19.99 turns into $19.99 USD.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_price_with_currency]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_price_with_currency" type="checkbox" <?php echo $general->price_with_currency ? 'checked' : ''; ?>>
        </td>

      </tr>

    </tbody>
  </table>

</div>

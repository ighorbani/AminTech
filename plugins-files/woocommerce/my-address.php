<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __('Billing address', 'woocommerce'),
			'shipping' => __('Shipping address', 'woocommerce'),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __('Billing address', 'woocommerce'),
		),
		$customer_id
	);
}

$oldcol = 1;
$col = 1;
?>

<p>
	<?php echo apply_filters('woocommerce_my_account_my_address_description', esc_html__('The following addresses will be used on the checkout page by default.', 'woocommerce')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</p>

<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()): ?>
	<div class="u-columns woocommerce-Addresses col2-set addresses">
	<?php endif; ?>

	<?php foreach ($get_addresses as $name => $address_title): ?>
		<?php
		$address = wc_get_account_formatted_address($name);
		$col = $col * -1;
		$oldcol = $oldcol * -1;
		?>

		<div class="woo-account-address">
			<div class="header">
				<h2><?php echo esc_html($address_title); ?></h2>
				<a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit">
					<?php
					printf(
						/* translators: %s: Address title */
						$address ? esc_html__('Edit %s', 'woocommerce') : esc_html__('Add %s', 'woocommerce'),
						esc_html($address_title)
					);
					?>
				</a>
			</div>
			<div class="address-box">
				<?php
				$billing_state_code = WC()->customer->get_billing_state();
				$states = WC()->countries->get_states(WC()->customer->get_billing_country()); // دریافت لیست استان‌ها بر اساس کشور
				$billing_state_name = isset($states[$billing_state_code]) ? $states[$billing_state_code] : $billing_state_code;
				?>

				<p>
					<span class="title"><?php esc_html_e('تحویل گیرنده:', 'woocommerce'); ?></span>
					<span class="value">
						<?php echo esc_html(WC()->customer->get_billing_first_name() . ' ' . WC()->customer->get_billing_last_name()); ?>
					</span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('استان:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html($billing_state_name); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('شهر:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_city()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('کدپستی:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_postcode()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('تلفن:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_phone()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('خیابان:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_address_1()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('آپارتمان/واحد:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_address_2()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('ایمیل:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_email()); ?></span>
				</p>

				<p>
					<span class="title"><?php esc_html_e('نام شرکت:', 'woocommerce'); ?></span>
					<span class="value"><?php echo esc_html(WC()->customer->get_billing_company()); ?></span>
				</p>

			</div>
		</div>

	<?php endforeach; ?>

	<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()): ?>
	</div>
	<?php
	endif;

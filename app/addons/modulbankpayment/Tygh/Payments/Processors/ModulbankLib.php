<?php
namespace Tygh\Payments\Processors;


class ModulbankLib
{
	const TAX_TYPE_PRODUCT = 'P';

    const TAX_TYPE_SHIPPING = 'S';

    const TAX_TYPE_PAYMENT_SURCHARGE = 'PS';

    static $tax_types_map = null;

	static public function init() {
		include_once __DIR__.'/modulbanklib/ModulbankHelper.php';
		include_once __DIR__.'/modulbanklib/ModulbankReceipt.php';
	}

    public static function convertSum($price)
    {
        if (CART_PRIMARY_CURRENCY != 'RUB') {
            $price = fn_format_price_by_currency($price, CART_PRIMARY_CURRENCY, 'RUB');
        }

        $price = fn_format_rate_value($price, 'F', 2, '.', '', '');

        return $price;
    }

	static public function prepareOrder($order)
    {
        if (!isset($order['subtotal_discount'])) {
            $order['subtotal_discount'] = 0;
        } else {
            $order['subtotal_discount'] = (float) $order['subtotal_discount'];
        }

        if (!isset($order['total'])) {
            $order['total'] = 0;
        } else {
            $order['total'] = (float) $order['total'];
        }

        if (!isset($order['shipping_cost'])) {
            $order['shipping_cost'] = 0;
        } else {
            $order['shipping_cost'] = (float) $order['shipping_cost'];
        }

        if (!isset($order['payment_surcharge'])) {
            $order['payment_surcharge'] = 0;
        } else {
            $order['payment_surcharge'] = (float) $order['payment_surcharge'];
        }

        if (empty($order['taxes'])) {
            return $order;
        }

        foreach ($order['taxes'] as $tax_id => $tax) {
            $is_included_tax = $tax['price_includes_tax'] != 'N';

            if (isset($tax['applies']['items'])) { // calculate tax on subtotal
                foreach ($tax['applies']['items'] as $item_type => $items) {
                    if (empty($items)) {
                        continue;
                    }

                    if (!isset($tax['applies'][$item_type])) {
                        $tax['applies'][$item_type] = 0;
                    }

                    switch ($item_type) {
                        case self::TAX_TYPE_PRODUCT:
                            $cart_ids = array_keys($items);
                            $products_total = 0;

                            foreach ($cart_ids as $cart_id) {
                                if (!isset($order['products'][$cart_id])) {
                                    continue;
                                }

                                if (!isset($order['products'][$cart_id]['receipt_tax_ids'])) {
                                    $order['products'][$cart_id]['receipt_tax_sum'] = 0;
                                    $order['products'][$cart_id]['receipt_tax_ids'] = array();
                                }

                                $order['products'][$cart_id]['receipt_tax_ids'][] = $tax_id;
                                $products_total += $order['products'][$cart_id]['price'] * $order['products'][$cart_id]['amount'];
                            }

                            foreach ($cart_ids as $cart_id) {
                                if (!isset($order['products'][$cart_id])) {
                                    continue;
                                }

                                $product = & $order['products'][$cart_id];

                                $tax_sum = round($tax['applies'][$item_type] * $product['price'] / $products_total,2);
                                $product['receipt_tax_sum'] += $tax_sum;

                                if (!$is_included_tax) {
                                    $product['price'] += $tax_sum;
                                }

                                unset($product);
                            }

                            break;
                        case self::TAX_TYPE_SHIPPING:
                            if (!isset($order['receipt_shipping_tax_ids'])) {
                                $order['receipt_shipping_tax_sum'] = 0;
                                $order['receipt_shipping_tax_ids'] = array();
                            }

                            $order['receipt_shipping_tax_ids'][] = $tax_id;
                            $order['receipt_shipping_tax_sum'] += $tax['applies'][$item_type];

                            if (!$is_included_tax) {
                                $order['shipping_cost'] += $tax['applies'][$item_type];
                            }
                            break;
                        case self::TAX_TYPE_PAYMENT_SURCHARGE:
                            if (!isset($order['receipt_payment_surcharge_tax_ids'])) {
                                $order['receipt_payment_surcharge_tax_sum'] = 0;
                                $order['receipt_payment_surcharge_tax_ids'] = array();
                            }

                            $order['receipt_payment_surcharge_tax_ids'][] = $tax_id;
                            $order['receipt_payment_surcharge_tax_sum'] += $tax['applies'][$item_type];

                            if (!$is_included_tax) {
                                $order['payment_surcharge'] += $tax['applies'][$item_type];
                            }
                            break;
                    }
                }
            } elseif (!empty($tax['applies'])) { // calculate tax on unit price
                foreach ($tax['applies'] as $key => $tax_sum) {
                    list($item_type, $cart_id) = explode('_', $key, 2);

                    switch ($item_type) {
                        case self::TAX_TYPE_PRODUCT:
                            if (!isset($order['products'][$cart_id])) {
                                continue;
                            }

                            if (!isset($order['products'][$cart_id]['receipt_tax_ids'])) {
                                $order['products'][$cart_id]['receipt_tax_sum'] = 0;
                                $order['products'][$cart_id]['receipt_tax_ids'] = array();
                            }

                            $sum = round($tax_sum / $order['products'][$cart_id]['amount'], 2);
                            $order['products'][$cart_id]['receipt_tax_sum'] += $sum;
                            $order['products'][$cart_id]['receipt_tax_ids'][] = $tax_id;

                            if (!$is_included_tax) {
                                $order['products'][$cart_id]['price'] += $sum;
                            }
                            break;
                        case self::TAX_TYPE_SHIPPING:
                            if (!isset($order['receipt_shipping_tax_ids'])) {
                                $order['receipt_shipping_tax_sum'] = 0;
                                $order['receipt_shipping_tax_ids'] = array();
                            }

                            $order['receipt_shipping_tax_sum'] += $tax_sum;
                            $order['receipt_shipping_tax_ids'][] = $tax_id;
                            break;
                        case self::TAX_TYPE_PAYMENT_SURCHARGE:
                            if (!isset($order['receipt_payment_surcharge_tax_ids'])) {
                                $order['receipt_payment_surcharge_tax_sum'] = 0;
                                $order['receipt_payment_surcharge_tax_ids'] = array();
                            }

                            $order['receipt_payment_surcharge_tax_ids'][] = $tax_id;
                            $order['receipt_payment_surcharge_tax_sum'] += $tax_sum;

                            if (!$is_included_tax) {
                                $order['payment_surcharge'] += $tax_sum;
                            }
                            break;

                    }
                }
            }
        }

        return $order;
    }


    static public function getTaxTypeByTaxIds(array $tax_ids)
    {
        $result = 'none';
        self::getMap();

        foreach ($tax_ids as $tax_id) {
            if (isset(self::$tax_types_map[$tax_id])) {
                $result = self::$tax_types_map[$tax_id];
            }

            if ($result !== 'none') {
                break;
            }
        }

        return $result;
    }

    public static function getMap()
    {
        if (self::$tax_types_map === null) {
            self::$tax_types_map = db_get_hash_single_array(
                'SELECT tax_id, tax_type FROM ?:taxes',
                array('tax_id', 'tax_type')
            );
        }

        return self::$tax_types_map;
    }

}

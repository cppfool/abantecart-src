<?php 
/*------------------------------------------------------------------------------
  $Id$

  AbanteCart, Ideal OpenSource Ecommerce Solution
  http://www.AbanteCart.com

  Copyright © 2011-2015 Belavier Commerce LLC

  This source file is subject to Open Software License (OSL 3.0)
  License details is bundled with this package in the file LICENSE.txt.
  It is also available at this URL:
  <http://www.opensource.org/licenses/OSL-3.0>

 UPGRADE NOTE:
   Do not edit or add to this file if you wish to upgrade AbanteCart to newer
   versions in the future. If you wish to customize AbanteCart for your
   needs please refer to http://www.AbanteCart.com for more information.
------------------------------------------------------------------------------*/
if (! defined ( 'DIR_CORE' )) {
	header ( 'Location: static_pages/' );
}
class ControllerPagesCheckoutConfirm extends AController {
	private $error = array();
	public $data = array();

	public function main() {

        //init controller data
        $this->extensions->hk_InitData($this,__FUNCTION__);

	   	if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->html->getSecureURL('checkout/cart'));
    	}		
		
		//validate if order min/max are met
		if (!$this->cart->hasMinRequirement() || !$this->cart->hasMaxRequirement()) {
			$this->redirect($this->html->getSecureURL('checkout/cart'));
		}
		
    	if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->html->getSecureURL('checkout/shipping');

	  		$this->redirect($this->html->getSecureURL('account/login'));
    	}

    	if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address_id']) || !$this->session->data['shipping_address_id']) {
	  			$this->redirect($this->html->getSecureURL('checkout/shipping'));
    		}
			
			if (!isset($this->session->data['shipping_method'])) {
	  			$this->redirect($this->html->getSecureURL('checkout/shipping'));
    		}
		} else {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$this->tax->setZone($this->session->data['country_id'], $this->session->data['zone_id']);

		}

    	if (!isset($this->session->data['payment_address_id']) || !$this->session->data['payment_address_id']) { 
	  		$this->redirect($this->html->getSecureURL('checkout/payment'));
    	}  
		
		if (!isset($this->session->data['payment_method'])) {
	  		$this->redirect($this->html->getSecureURL('checkout/payment'));
    	}

		if($this->request->get['balance']=='disapply'){
			unset($this->session->data[ 'used_balance' ],$this->request->get['balance'],$this->session->data[ 'used_balance_full' ]);
		}
		$this->data = array();
	
		$order = new AOrder( $this->registry );
		$this->data = $order->buildOrderData( $this->session->data );
		$order_id = $order->saveOrder();
		if($order_id===false){
			// preventing rebuilding order of already processed orders
			//(by "back" button via browser history from external payment page(paypal, google_checkout etc))
			$this->redirect($this->html->getSecureURL('checkout/success'));
		}
		$this->session->data['order_id'] = $order_id;


    	$this->document->setTitle( $this->language->get('heading_title') ); 
		
		$this->document->resetBreadcrumbs();

      	$this->document->addBreadcrumb( array ( 
        	'href'      => $this->html->getURL('index/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	 )); 

      	$this->document->addBreadcrumb( array ( 
        	'href'      => $this->html->getURL('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	 ));
		
		if ($this->cart->hasShipping()) {
      		$this->document->addBreadcrumb( array ( 
        		'href'      => $this->html->getURL('checkout/shipping'),
        		'text'      => $this->language->get('text_shipping'),
        		'separator' => $this->language->get('text_separator')
      		 ));
		}
		
      	$this->document->addBreadcrumb( array ( 
        	'href'      => $this->html->getURL('checkout/payment', '&mode=edit',true),
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	 ));

      	$this->document->addBreadcrumb( array ( 
        	'href'      => $this->html->getURL('checkout/confirm'),
        	'text'      => $this->language->get('text_confirm'),
        	'separator' => $this->language->get('text_separator')
      	 ));
						 	
		$this->data['error_warning'] = $this->error['warning'];
		$this->data['success'] = $this->session->data['success'];
		if (isset($this->session->data['success'])) {
    		unset($this->session->data['success']);
		}

		//balance
		$balance_def_currency = $this->customer->getBalance();
		$balance = $this->currency->convert($balance_def_currency,$this->config->get('config_currency'),$this->session->data['currency']);

		if($balance!=0 || ($balance==0 && $this->config->get('config_zero_customer_balance')) && (float)$this->session->data['used_balance']!=0){

			$this->data['balance'] = $this->language->get('text_balance_checkout').' '.$this->currency->format($balance,$this->session->data['currency'],1);

			if((float)$this->session->data['used_balance']>0){

				$this->data['disapply_balance'] = array('href'=> $this->html->getSecureURL('checkout/payment','&mode=edit&balance=disapply',true),
														'text' => $this->language->get('button_disapply_balance'));
				$this->data['balance'] .=  ' ('.$this->currency->format($balance_def_currency-(float)$this->session->data['used_balance']).')';
				$this->data['balance'] .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->currency->format((float)$this->session->data['used_balance']).' '.$this->language->get('text_applied_balance');
			}elseif((float)$this->session->data['used_balance']==0 && $balance>0){
				$this->data['disapply_balance'] = array('href'=> $this->html->getSecureURL('checkout/payment','&mode=edit&balance=apply',true),
														'text' => $this->language->get('button_apply_balance'));
			}
		}

		$this->loadModel('account/address');
		$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);	
		if ($this->cart->hasShipping()) {
			$this->data['shipping_address'] = $this->customer->getFormatedAdress($shipping_address, $shipping_address[ 'address_format' ] );
		} else {
			$this->data['shipping_address'] = '';
		}
		
        $this->data['shipping_method'] = $this->session->data['shipping_method']['title'];
        $this->data['shipping_method_price'] = $this->session->data['shipping_method']['title'];
		$this->data['checkout_shipping_edit'] = $this->html->getSecureURL('checkout/shipping', '&mode=edit',true);
    	$this->data['checkout_shipping_address'] = $this->html->getSecureURL('checkout/address/shipping');

		$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		if ($payment_address) {
			$this->data['payment_address'] = $this->customer->getFormatedAdress($payment_address, $payment_address[ 'address_format' ] );
		} else {
			$this->data['payment_address'] = '';
		}
		if($this->session->data['payment_method']['id'] != 'no_payment_required'){
			$this->data['payment_method'] = $this->session->data['payment_method']['title'];
		}else{
			$this->data['payment_method'] = '';
		}

		$this->data['checkout_payment_edit'] = $this->html->getSecureURL('checkout/payment', '&mode=edit',true);
		$this->data['checkout_payment_address'] = $this->html->getSecureURL('checkout/address/payment');

		$this->loadModel('tool/seo_url');
		$this->loadModel('tool/image');

		//Format product data specific for confirmation page
        $resource = new AResource('image');
        for($i = 0; $i < sizeof( $this->data['products'] ); $i++){
        	$product_id = $this->data['products'][$i]['product_id'];
			$opts = $this->data['products'][$i]['option'];
	        $options = array();
	        foreach ($opts as $option) {
                if($option['element_type']=='H'){ continue;} //hide hidden options

                $value = $option['value'];
                // hide binary value for checkbox
                if($option['element_type']=='C' && in_array($value, array(0,1))){
                    $value = '';
                }
                $options[] = array(
                    'name'  => $option['name'],
                    'value' => $value
                );
            }

	        $this->data['products'][$i]['option'] = $options;

	        $thumbnail = $resource->getMainThumb('products',
			                                    $product_id,
												(int)$this->config->get('config_image_cart_width'),
												(int)$this->config->get('config_image_cart_height'),
												true);
			$tax = $this->tax->calcTotalTaxAmount($this->data['products'][$i]['total'], $this->data['products'][$i]['tax_class_id']);
      		$this->data['products'][$i] = array_merge( 
      			$this->data['products'][$i], 
      			array(
        		'thumb'    => $thumbnail,
				'tax'        => $this->currency->format($tax),
        		'price'      => $this->currency->format($this->data['products'][$i]['price']),
        		'total'      => $this->currency->format($this->data['products'][$i]['total']),
				'href'       => $this->html->getSEOURL('product/product', '&product_id=' . $product_id, true )
      		)); 
        }

		$display_totals = $this->cart->buildTotalDisplay();
		$this->data['totals'] = $display_totals['total_data'];

		$this->data['cart'] = $this->html->getSecureURL('checkout/cart');

        if ($this->config->get('config_checkout_id')) {
			$this->loadModel('catalog/content');
			$content_info = $this->model_catalog_content->getContent($this->config->get('config_checkout_id'));
			if ($content_info) {
				$this->data['text_accept_agree'] = $this->language->get('text_accept_agree');
				$this->data['text_accept_agree_href'] = $this->html->getSEOURL('r/content/content/loadInfo','&content_id=' . $this->config->get('config_checkout_id'),true);
				$this->data['text_accept_agree_href_link'] = $content_info['title'];
			} else {
				$this->data['text_accept_agree'] = '';
			}
		} else {
			$this->data['text_accept_agree'] = '';
		}
		if($this->session->data['payment_method']['id'] != 'no_payment_required'){
        	$this->addChild('responses/extension/' . $this->session->data['payment_method']['id'], 'payment');
		}else{
			$this->addChild('responses/checkout/no_payment', 'payment');
		}

		$this->view->batchAssign( $this->data );
        $this->processTemplate('pages/checkout/confirm.tpl' );

        //update data before render
        $this->extensions->hk_UpdateData($this,__FUNCTION__);
  	}
}

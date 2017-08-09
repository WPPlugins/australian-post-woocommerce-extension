<?php

class WC_Australian_Post_Shipping_Method extends WC_Shipping_Method
{
    public $postageParcelURL = 'https://digitalapi.auspost.com.au/postage/parcel/domestic/calculate.json';
    public $postage_intl_url = 'https://digitalapi.auspost.com.au/postage/parcel/international/service.json';
    public $api_key = '20b5d076-5948-448f-9be4-f2fd20d4c258';
    public $supported_services = array(
        'AUS_PARCEL_REGULAR' => 'Parcel Post',
        'AUS_PARCEL_EXPRESS' => 'Express Post'
    );

    public function __construct($instance_id = 0)
    {
        $this->id = 'auspost';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Australia Post', 'australian-post');
        $this->title = __('Australia Post', 'australian-post');

        $this->supports  = array(
            'shipping-zones',
            'instance-settings',
        );
        $this->init_form_fields();
        $this->init_settings();


        $this->enabled = $this->get_option('enabled');
        $this->title = $this->get_option('title');
        $this->api_key = $this->get_option('api_key');
        $this->shop_post_code = $this->get_option('shop_post_code');


        $this->default_weight = $this->get_option('default_weight');
        $this->default_width = $this->get_option('default_width');
        $this->default_length = $this->get_option('default_length');
        $this->default_height = $this->get_option('default_height');
        $this->show_duration = $this->get_option('show_duration');


        $this->debug_mode = $this->get_option('debug_mode');


        add_action('woocommerce_update_options_shipping_'.$this->id, array($this, 'process_admin_options'));
    }


    public function init_form_fields()
    {
        $dimensions_unit = strtolower(get_option('woocommerce_dimension_unit'));
        $weight_unit = strtolower(get_option('woocommerce_weight_unit'));

        $this->instance_form_fields = array(
            'title' => array(
                'title'        => __('Method Title', 'woocommerce'),
                'type'            => 'text',
                'description'    => __('This controls the title', 'woocommerce'),
                'default'        => __('Australia Post Shipping', 'woocommerce'),
                'desc_tip'        => true,
            ),
            'api_key' => array(
                    'title'             => __('API Key', 'australian-post'),
                    'type'              => 'text',
                    'description'       => __('Get your key from <a target="_blank" href="https://developers.auspost.com.au/apis/pacpcs-registration">https://developers.auspost.com.au/apis/pacpcs-registration</a>', 'australian-post'),
                    'default'           => $this->api_key
            ),
            'shop_post_code' => array(
                    'title'             => __('Shop Origin Post Code', 'australian-post'),
                    'type'              => 'text',
                    'description'       => __('Enter your Shop postcode.', 'australian-post'),
                    'default'           => '2000',
                    'css'                => 'width:100px;',
            ),
            'default_weight' => array(
                    'title'             => __('Default Package Weight', 'australian-post'),
                    'type'              => 'text',
                    'default'           => '0.5',
                    'description'       => __($weight_unit, 'australian-post'),
                    'css'                => 'width:100px;',
            ),
            'default_width' => array(
                    'title'             => __('Default Package Width', 'australian-post'),
                    'type'              => 'text',
                    'default'           => '5',
                    'description'       => __($dimensions_unit, 'australian-post'),
                    'css'                => 'width:100px;',
            ),
            'default_height' => array(
                    'title'             => __('Default Package Height', 'australian-post'),
                    'type'              => 'text',
                    'default'           => '5',
                    'description'       => __($dimensions_unit, 'australian-post'),
                    'css'                => 'width:100px;',
            ),
            'default_length' => array(
                    'title'             => __('Default Package Length', 'australian-post'),
                    'type'              => 'text',
                    'default'           => '10',
                    'description'       => __($dimensions_unit, 'australian-post'),
                    'css'                => 'width:100px;',
            ),
            'debug_mode' => array(
                'title'        => __('Enable Debug Mode', 'woocommerce'),
                'type'            => 'checkbox',
                'label'        => __('Enable ', 'woocommerce'),
                'default'        => 'no',
                'description'    => __('If debug mode is enabled, the shipping method will be activated just for the administrator.'),
            ),
            'show_duration' => array(
                'title'        => __('Delivery Time', 'woocommerce'),
                'type'            => 'checkbox',
                'label'        => __('Enable ', 'woocommerce'),
                'default'        => 'yes',
                'description'    => __('Show Delivery Time Estimation in the Checkout page.', 'woocommerce'),
            ),
     );
    }

    /**
     * Admin Panel Options
     * - Options for bits like 'title' and availability on a country-by-country basis
     *
     * @since 1.0.0
     * @return void
     */
    public function admin_options()
    {
        ?>
		<h3><?php _e('Austrlia Post Settings', 'woocommerce'); ?></h3>
			<?php if ($this->debug_mode == 'yes'): ?>
				<div class="updated woocommerce-message">
			    	<p><?php _e('Austrlia Post debug mode is activated, only administrators can use it.', 'australian-post'); ?></p>
			    </div>
			<?php endif; ?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<table class="form-table">
							<?php echo $this->get_admin_options_html(); ?>
						</table><!--/.form-table-->
					</div>
					<div id="postbox-container-1" class="postbox-container">
	                        <div id="side-sortables" class="meta-box-sortables ui-sortable">

	                            <div class="postbox ">
	                                <div class="handlediv" title="Click to toggle"><br></div>
	                                <h3 class="hndle"><span><i class="dashicons dashicons-update"></i>&nbsp;&nbsp;Upgrade to Pro</span></h3>
	                                <div class="inside">
	                                    <div class="support-widget">
	                                        <ul>
	                                            <li>» International Shipping</li>
	                                            <li>» Customizable Domestic Shipping</li>
	                                            <li>» Pre-Paid Domestic Satchels</li>
	                                            <li>» Letters Shipping</li>
	                                            <li>» Courier Shipping</li>
	                                            <li>» Handling Fees and Discounts</li>
	                                            <li>» Extra Cover</li>
	                                            <li>» Signature On Delivery</li>
	                                            <li>» Display the Cheapest option</li>
																							<li>» Dropshipping Support</li>
																							<li>» Fallback Price</li>
	                                            <li>» Renaming Shipping Options</li>
																							<li>» Custom Boxes <span style="color:red;">(new)</span></li>
																							<li>» Australia Post Tracking <span style="color:red;">(new)</span></li>
																							<li>» Auto Hassle-Free Updates</li>
	                                            <li>» High Priority Customer Support</li>
	                                        </ul>
											<a href="https://wpruby.com/plugin/australia-post-woocommerce-extension-pro/" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> Upgrade Now</a>
	                                    </div>
	                                </div>
	                            </div>


	                            <div class="postbox ">
	                                <div class="handlediv" title="Click to toggle"><br></div>
	                                <h3 class="hndle"><span><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Plugin Support</span></h3>
	                                <div class="inside">
	                                    <div class="support-widget">
	                                        <p>
	                                        <img style="width:100%;" src="https://wpruby.com/wp-content/uploads/2016/03/wpruby_logo_with_ruby_color-300x88.png">
	                                        <br/>
	                                        Got a Question, Idea, Problem or Praise?</p>
	                                        <ul>
												<li>» <a target="_blank" href="http://auspost.com.au/parcels-mail/size-and-weight-guidelines.html">Weight and Size Guidlines</a> from Australia Post.</li>
	                                            <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank">Support Request</a></li>
	                                            <li>» <a href="https://wpruby.com/knowledgebase_category/woocommerce-australia-post-shipping-method-pro/" target="_blank">Documentation and Common issues</a></li>
	                                            <li>» <a href="https://wpruby.com/plugins/" target="_blank">Our Plugins Shop</a></li>
	                                       		<li>» If you like the plugin please leave us a <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/australian-post-woocommerce-extension?filter=5#postform">★★★★★</a> rating.</li>
	                                        </ul>

	                                    </div>
	                                </div>
	                            </div>

	                            <div class="postbox rss-postbox">
	    							<div class="handlediv" title="Click to toggle"><br></div>
	    								<h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
	    								<div class="inside">
											<div class="rss-widget">
												<?php
                                                    wp_widget_rss_output(array(
                                                            'url' => 'https://wpruby.com/feed/',
                                                            'title' => 'WPRuby Blog',
                                                            'items' => 3,
                                                            'show_summary' => 0,
                                                            'show_author' => 0,
                                                            'show_date' => 1,
                                                    )); ?>
	    									</div>
	    								</div>
	    						</div>

	                        </div>
	                    </div>
                    </div>
				</div>
				<div class="clear"></div>
				<style type="text/css">
					.wpruby_button{
						background-color:#4CAF50 !important;
						border-color:#4CAF50 !important;
						color:#ffffff !important;
						width:100%;
						padding:5px !important;
						text-align:center;
						height:35px !important;
						font-size:12pt !important;
					}
				</style>
		<?php

    }

    public function is_available($package)
    {
        // The lite version doesn't support international shipping
        if ($package['destination']['country'] != 'AU') {
            return false;
        }

        // Debug mode
        if ($this->debug_mode === 'yes') {
            return current_user_can('administrator');
        }
        return true;
    }

    public function calculate_shipping($package = array())
    {
        $package_details  =  $this->get_package_details($package);
        $this->rates = array();
        // since 1.4.2 enhancing the debug mode.
        $this->debug('Packing Details: <pre>' . print_r($package_details, true) . '</pre>');
        $weight = 0;
        $length = 0;
        $width = 0;
        $height = 0;
        $rates = array();
        foreach ($package_details as  $pack) {
            $weight = $pack['weight'];
            $height = $pack['height'];
            $width    = $pack['width'];
            $length = $pack['length'];


            $rates = $this->get_rates($rates, $pack['item_id'], $weight, $height, $width, $length, $package['destination']['postcode']);
        }

        if (!empty($rates)) {
            foreach ($rates as $key => $rate) {
                if (is_array($rate)) {
                    $rate['package'] = $package;
                }
                //info @since 1.5.6 Adding shipping rate filter to allow users to modify the shipping price.
                $rates[$key]['cost'] = apply_filters('australia_post_shipping_rate', $rates[$key]['cost']);
                $this->add_rate($rates[$key]);
            }
        }
    }




    private function get_rates($old_rates, $item_id, $weight, $height, $width, $length, $destination)
    {
        $query_params['from_postcode'] = $this->shop_post_code;
        $query_params['to_postcode'] = $destination;
        $query_params['length'] = $length;
        $query_params['width'] = $width;
        $query_params['height'] = $height;
        $query_params['weight'] = $weight;
        foreach ($this->supported_services as $service_key => $service_name):
                    $query_params['service_code'] = $service_key;
        $this->debug('Packing Request: <pre>' . print_r($this->postageParcelURL.'?'.http_build_query($query_params), true) . '</pre>');

        $response = wp_remote_get($this->postageParcelURL.'?'.http_build_query($query_params), array('headers' => array('AUTH-KEY'=> $this->api_key)));
                    // since 1.4.2 enhancing the debug mode.
                    $this->debug('Australia Post RESPONSE: <pre>' . print_r($response, true) . '</pre>');

        if (is_wp_error($response)) {
            return array('error' => 'Unknown Problem. Please Contact the admin');
        }

        $aus_response = json_decode(wp_remote_retrieve_body($response));



        if (!isset($aus_response->error) && $aus_response != null) {
            $duration = '';
            if ($this->show_duration === 'yes') {
                $duration = ' ('. $aus_response->postage_result->delivery_time .')';
            }

            $old_rate = (isset($old_rates[$service_key]['cost']))?$old_rates[$service_key]['cost']:0;
                        // add the rate if the API request succeeded

                        $rates[$service_key] = array(
                                'id' => $service_key,
                                'label' => $this->title. ' ' . $aus_response->postage_result->service.' ' . $duration,
                                'cost' =>  ($aus_response->postage_result->total_cost) + $old_rate,

                        );

                    // if the API returned any error, show it to the user
        } else {
            return array('error' => $aus_response->error->errorMessage);
        }

        endforeach;

        return $rates;
    }


    /**
     * get_min_dimension function.
     * get the minimum dimension of the package, so we multiply it with the quantity
     * @access private
     * @param number $width
     * @param number $length
     * @param number $height
     * @return string $result
     */
    private function get_min_dimension($width, $length, $height)
    {
        $dimensions = array('width'=>$width,'length'=>$length,'height'=>$height);
        $result = array_keys($dimensions, min($dimensions));
        return $result[0];
    }


/**
     * get_package_details function.
     *
     * @access private
     * @param mixed $package
     * @return void
     */
    private function get_package_details($package)
    {
        global $woocommerce;

        $parcel   = array();
        $requests = array();
        $weight   = 0;
        $volume   = 0;
        $value    = 0;
        $products = array();
        // Get weight of order
        foreach ($package['contents'] as $item_id => $values) {
            $weight += wc_get_weight((floatval($values['data']->get_weight())<=0)?$this->default_weight:$values['data']->get_weight(), 'kg') * $values['quantity'];
            $value  += $values['data']->get_price() * $values['quantity'];

            $length = wc_get_dimension(($values['data']->get_length()=='')?$this->default_length:$values['data']->get_length(), 'cm');
            $height = wc_get_dimension(($values['data']->get_height()=='')?$this->default_height:$values['data']->get_height(), 'cm');
            $width = wc_get_dimension(($values['data']->get_width()=='')?$this->default_width:$values['data']->get_width(), 'cm');
            $min_dimension = $this->get_min_dimension($width, $length, $height);
            $products[] = array('weight'=> wc_get_weight((floatval($values['data']->get_weight())<=0)?$this->default_weight:$values['data']->get_weight(), 'kg'),
                                'quantity'=> $values['quantity'],
                                'length'=> $length,
                                'height'=> $height,
                                'width'=> $width,
                                'item_id'=> $item_id,
                            );
            $volume += ($length * $height * $width);
        }

        $max_weight = $this->get_max_weight($package);

        $pack = array();
        $packs_count = 1;
        $pack[$packs_count]['weight'] = 0;
        $pack[$packs_count]['length'] = 0;
        $pack[$packs_count]['height'] = 0;
        $pack[$packs_count]['width'] = 0;
        foreach ($products as $product) {
            while ($product['quantity'] != 0) {
                if (!isset($pack[$packs_count]['weight'])) {
                    $pack[$packs_count]['weight'] = 0;
                }
                $pack[$packs_count]['weight'] += $product['weight'];
                $pack[$packs_count]['length'] = $product['length'];
                $pack[$packs_count]['height'] = $product['height'];
                $pack[$packs_count]['width']  = $product['width'];
                $pack[$packs_count]['item_id'] =  $product['item_id'];

                if ($pack[$packs_count]['weight'] > $max_weight) {
                    $pack[$packs_count]['weight'] -=  $product['weight'];
                    $packs_count++;
                    $pack[$packs_count]['weight'] = $product['weight'];
                    $pack[$packs_count]['length'] =1;// $product['length'];
                        $pack[$packs_count]['height'] =1;// $product['height'];
                        $pack[$packs_count]['width'] =1;// $product['width'];
                        $pack[$packs_count]['item_id'] =  $product['item_id'];
                }
                $product['quantity']--;
            }
        }

        return $pack;
    }



    private function get_max_weight($package)
    {
        $max = ($package['destination']['country'] == 'AU')? 22:20;
        $store_unit = strtolower(get_option('woocommerce_weight_unit'));

        if ($store_unit == 'kg') {
            return $max;
        }
        if ($store_unit == 'g') {
            return $max * 1000;
        }
        if ($store_unit == 'lbs') {
            return $max * 0.453592;
        }
        if ($store_unit == 'oz') {
            return $max * 0.0283495;
        }

        return $max;
    }

    /**
     * Output a message
     */
    public function debug($message, $type = 'notice')
    {
        if ($this->debug_mode == 'yes' && current_user_can('manage_options')) {
            if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')) {
                wc_add_notice($message, $type);
            } else {
                global $woocommerce;
                $woocommerce->add_message($message);
            }
        }
    }
}

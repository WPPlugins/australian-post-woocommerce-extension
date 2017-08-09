<?php 

class WC_Australian_Post_Shipping_Method_Legacy extends WC_Australian_Post_Shipping_Method
{
    public function __construct()
    {
        parent::__construct(0);
        $this->id = 'auspost';
        $this->method_title = __('Australia Post', 'australian-post');
        $this->title = __('Australia Post', 'australian-post');
        
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
        
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable Australia Post', 'woocommerce'),
                'default' => 'no',
            ),
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
							<?php $this->generate_settings_html(); ?>
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
}

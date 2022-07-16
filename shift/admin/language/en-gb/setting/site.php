<?php

// Heading
$_['heading_title']                = 'Sites';

// Text
$_['text_settings']                = 'Settings';
$_['text_success']                 = 'Success: You have modified Sites!';
$_['text_list']                    = 'Site List';
$_['text_add']                     = 'Add Site';
$_['text_edit']                    = 'Edit Site';
$_['text_items']                   = 'Items';
$_['text_tax']                     = 'Taxes';
$_['text_account']                 = 'Account';
$_['text_checkout']                = 'Checkout';
$_['text_stock']                   = 'Stock';
$_['text_shipping']                = 'Shipping Address';
$_['text_payment']                 = 'Payment Address';

// Column
$_['column_name']                  = 'Site Name';
$_['column_url']                   = 'Site URL';
$_['column_action']                = 'Action';

// Entry
$_['entry_url']                    = 'Site URL';
$_['entry_ssl']                    = 'SSL URL';
$_['entry_meta_title']             = 'Meta Title';
$_['entry_meta_description']       = 'Meta Tag Description';
$_['entry_meta_keyword']           = 'Meta Tag Keywords';
$_['entry_layout']                 = 'Default Layout';
$_['entry_theme']                  = 'Theme';
$_['entry_name']                   = 'Site Name';
$_['entry_owner']                  = 'Site Owner';
$_['entry_address']                = 'Address';
$_['entry_geocode']                = 'Geocode';
$_['entry_email']                  = 'E-Mail';
$_['entry_telephone']              = 'Telephone';
$_['entry_fax']                    = 'Fax';
$_['entry_image']                  = 'Image';
$_['entry_open']                   = 'Opening Times';
$_['entry_comment']                = 'Comment';
$_['entry_location']               = 'Site Location';
$_['entry_country']                = 'Country';
$_['entry_zone']                   = 'Region / State';
$_['entry_language']               = 'Language';
$_['entry_currency']               = 'Currency';
$_['entry_tax']                    = 'Display Prices With Tax';
$_['entry_tax_default']            = 'Use Site Tax Address';
$_['entry_tax_customer']           = 'Use Customer Tax Address';
$_['entry_customer_group']         = 'Customer Group';
$_['entry_customer_group_display'] = 'Customer Groups';
$_['entry_customer_price']         = 'Login Display Prices';
$_['entry_account']                = 'Account Terms';
$_['entry_cart_weight']            = 'Display Weight on Cart Page';
$_['entry_checkout_guest']         = 'Guest Checkout';
$_['entry_checkout']               = 'Checkout Terms';
$_['entry_order_status']           = 'Order Status';
$_['entry_stock_display']          = 'Display Stock';
$_['entry_stock_checkout']         = 'Stock Checkout';
$_['entry_logo']                   = 'Site Logo';
$_['entry_icon']                   = 'Icon';
$_['entry_secure']                 = 'Use SSL';

// Help
$_['help_url']                     = 'Include the full URL to your Site. Make sure to add \'/\' at the end. Example: http://www.yourdomain.com/path/<br /><br />Don\'t use directories to create a new Site. You should always point another domain or sub domain to your hosting.';
$_['help_ssl']                     = 'SSL URL to your Site. Make sure to add \'/\' at the end. Example: http://www.yourdomain.com/path/<br /><br />Don\'t use directories to create a new Site. You should always point another domain or sub domain to your hosting.';
$_['help_geocode']                 = 'Please enter your Site location geocode manually.';
$_['help_open']                    = 'Fill in your sites opening times.';
$_['help_comment']                 = 'This field is for any special notes you would like to tell the customer i.e. Site does not accept cheques.';
$_['help_location']                = 'The different Site locations you have that you want displayed on the contact us form.';
$_['help_currency']                = 'Change the default currency. Clear your browser cache to see the change and reset your existing cookie.';
$_['help_tax_default']             = 'Use the Site address to calculate taxes if customer is not logged in. You can choose to use the Site address for the customer\'s shipping or payment address.';
$_['help_tax_customer']            = 'Use the customers default address when they login to calculate taxes. You can choose to use the default address for the customer\'s shipping or payment address.';
$_['help_customer_group']          = 'Default customer group.';
$_['help_customer_group_display']  = 'Display customer groups that new customers can select to use such as wholesale and business when signing up.';
$_['help_customer_price']          = 'Only show prices when a customer is logged in.';
$_['help_account']                 = 'Forces people to agree to terms before an account can be created.';
$_['help_checkout_guest']          = 'Allow customers to checkout without creating an account. This will not be available when a downloadable product is in the shopping cart.';
$_['help_checkout']                = 'Forces people to agree to terms before an a customer can checkout.';
$_['help_order_status']            = 'Set the default order status when an order is processed.';
$_['help_stock_display']           = 'Display stock quantity on the product page.';
$_['help_stock_checkout']          = 'Allow customers to still checkout if the products they are ordering are not in stock.';
$_['help_icon']                    = 'The icon should be a PNG that is 16px x 16px.';
$_['help_secure']                  = 'To use SSL check with your host if a SSL certificate is installed.';

// Error
$_['error_warning']                = 'Warning: Please check the form carefully for errors!';
$_['error_permission']             = 'Warning: You do not have permission to modify Sites!';
$_['error_url']                    = 'Site URL required!';
$_['error_meta_title']             = 'Title must be between 3 and 32 characters!';
$_['error_name']                   = 'Site Name must be between 3 and 32 characters!';
$_['error_owner']                  = 'Site Owner must be between 3 and 64 characters!';
$_['error_address']                = 'Site Address must be between 10 and 256 characters!';
$_['error_email']                  = 'E-Mail Address does not appear to be valid!';
$_['error_telephone']              = 'Telephone must be between 3 and 32 characters!';
$_['error_customer_group_display'] = 'You must include the default customer group if you are going to use this feature!';
$_['error_default']                = 'Warning: You can not delete your default Site!';
$_['error_site']                  = 'Warning: This Site cannot be deleted as it is currently assigned to %s orders!';

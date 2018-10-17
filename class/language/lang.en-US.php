<?php
$lng = array(
	'contact' => array(
		'title' => 'Contact',
		'rewrite' => 'contact-us',
		'name' => 'Full name',
		'address' => 'Address',
		'short_address' => 'Add',
		'city' => 'City',
		'phone' => 'Phone',
		'short_phone' => 'Tel',
		'mobile' => 'Mobile',
		'hotline' => 'Hotline',
		'email' => 'Email',		
		'website' => 'Website',
		'content' => 'Content',	
		'note' => 'Note',	
		'hotline1' => 'Technical',	
		'hotline2' => 'Sales',
		'code' => array(
			'title' => 'Security code',
			'refresh' => 'Get an other captcha'
		),
		'button' => array(
			'send' => 'Send',
			'clear' => 'Reset'
		),		
		'required' => 'Information required',
		'inprocess' => 'Please waiting..',
		'errors' => array(
			'note' => 'Please make problems below',
			'name' => 'Fullname required.',
			'address' => 'Address required.',
			'email' => 'Email incorrect format.',
			'content' => 'Content required.',
			'code' => 'Invalid security code.',
			'phone' => 'Phone is a number.',
			'mobile' => 'Mobile is a number.',
			'missing' => 'Please fill in the form.',
		),
		'success' => array(
			'text' => 'Mail was sent to %%SITE_NAME%%!. Thank you for contact us.'
		)
	),
	
	'product' => array(
		'title' => 'Products',
		'rewrite' => 'products',
		'currency' => 'VND',
		'outofstock' => 'Out of Stock',
		'instock' => 'In Stock',
		'availability' => 'Availability',
		'updating' => 'Product is updating..',
		'updating2' => ' is updating..',
		'description' => array(
			'title' => 'Product Description',
			'updating' => 'Product description is updating..',
		),
		'related' => array(
			'title' => 'Related Products'
		),
		'price' => array(
			'title' => 'Price',
			'promotion' => 'Promotion price',
			'save' => 'Save'
		),
		'category' => array(
			'title' => 'Flowers by Category',
			'all' => 'All products',
			'short' => 'Category',
			'view_more' => 'View more'
		),
		'brand' => array(
			'title' => 'Brands',
			'rewrite' => 'brand',
			'all' => 'All brands',
			'toolbar' => array(
				'pager' => array(
					'title' => 'Brand from',
					'item' => 'brand(s)'
				)
			)
		),
		'search' => array(
			'title' => 'Search',
			'button' => 'Search',
			'placeholder' => 'Type in your keywords',
			'noresult' => 'Your search did not match any data.'
		),
		'new' => array(
			'title' => 'New Products',
			'rewrite' => 'new-products'
		),
		'promotion' => array(
			'title' => 'Promotion Products'
		),
		'favorite' => array(
			'title' => 'Favorite Products'
		),
		'hot' => array(
			'title' => 'Best Seller'
		),
		'featured' => array(
			'title' => 'featured Products',
			'rewrite' => 'featured-products'
		),
		'button' => array(
			'buy' => 'Add to Cart',
			'buynow' => 'Buy Now',
			'add' => 'Add to Cart',
			'detail' => 'Detail'
		),
		'tag' => array(
			'title' => 'Tags'
		),
		'incategory' => array(
			'title' => 'Other Products'
		),
		'outofstock' => array(
			'title' => 'Out of Stock'
		),
		'viewall' => 'View All',
		'toolbar' => array(
			'pager' => array(
				'title' => 'Product from',
				'item' => 'item(s)'
			),
			'sorting' => array(
				'view' => 'View',
				'grid' => 'Grid',
				'list' => 'List',
				'orderby' => array(
					'price-ascending' 		=> 'Price: Low to Hight',
					'price-descending' 		=> 'Price: Hight to Low',
					'name-ascending' 		=> 'Name: A - Z',
					'name-descending' 		=> 'Name: Z - A',
					'created-descending' 	=> 'Product: Newest',
					'created-ascending' 	=> 'Product: Oldest'
				)
			)
		),
		'button' => array(
			'add2cart' => 'Add to Cart'
		),
		'callme' => 'Contact Us',
		'review' => 'Write a review'
	),
	
	'cms' => array(
		'posted' => 'Posted',
		'others' => array(
			'title' => 'Others'
		),
		'updating' => 'Content id upadting..'
	),
	
	'cart' => array(
		'title' => 'Cart',
		'rewrite' => 'cart',
		'yourcart' => 'Your cart',
		'viewcart' => 'View cart',	
		'cartdetail' => 'Your Cart Detail',	
		'total' => 'Total',
		'item' => 'item(s)',		
		'remove' => 'Remove cart',
		'no' => 'NO',
		'itempicture' => 'Picture',
		'itemname' => 'Item Name',
		'code' => 'Code',
		'unitprice' => 'Unit Price',
		'qty' => 'Qty',
		'unittotal' => 'Unit Total',
		'subtotal' => 'Sub Total',
		'grandtotal' => 'Grand total',
		'addone' => 'Add one',
		'removeone' => 'Remove one',
		'removeitem' => 'Remove this item',
		'backto' => 'Back to Shopping',
		'empty' => '<strong>Your shopping cart is empty!</strong><br/><br/>To add items to your shopping cart, browse product catalog you\'ll find an \'Add to Cart\' button on the product page for any part that is stocked at %%SITE_NAME%% .'
	),		
	
	'checkout' => array(
		'title' => 'Checkout',
		'rewrite' => 'checkout',
		'billing_title' => 'Billing Information',
		'billing_note' => 'Please enter the details for the order is delivered quickly and accurately to you.',
		'shipping_title' => 'Shipping Information',
		'same_buyer' => 'Check if it is <strong>the same billing information</strong>',
		'tax' => array(
			'title' => 'Tax Information',			
			'company' => 'Company Name',
			'code' => 'Tax code',
			'address' => 'Address',
			'address_format' => 'No., Street, Ward, District, City',
			'license' => 'in Business License',
			'expand' => 'Expand'
		),
		'payment' => array(
			'title' => 'Payment Method',
			'methods' => array(
				'pod' => 'Payment on Delivery (POD).',
				'transfer' => 'Transfer through bank account or ATM.',
				#'nganluong' => 'Ngan Luong online payment gate.',
				#'baokim' => 'Bao Kim online payment gate.'
			),
			'accounts' => 'View account information'
		),
		'review' => array(
			'title' => 'Confirmation',
			'detail' => 'Order Information',
			'note' => 'Order Note'
		),
		'button' => array(
			'cart' => 'View your cart',
			'return' => 'Go back',
			'confirm' => 'Confirm Order',
			'proceed' => 'Proceed to Checkout'
		),
		'vat' => 'Value Added Tax',
		'grand_total' => 'Grand total',
		'errors' => array(
			'billing_name' => 'Billing name is required.',
			'billing_email' => 'Email is required.',
			'billing_phone' => 'Billing phone is a number.',
			'billing_mobile' => 'Billing mobile is a number.',
			'billing_country' => 'Please choose billing country.',
			'billing_city' => 'Please choose billing city.',
			'billing_district' => 'Please choose billing district.',
			'billing_address' => 'Billing address is required.',
			'shipping_name' => 'Shipping name is required.',
			'shipping_phone' => 'Shipping phone is a number.',
			'shipping_mobile' => 'Shipping mobile is a number.',
			'shipping_country' => 'Please choose shipping country.',
			'shipping_city' => 'Please choose shipping city.',
			'shipping_district' => 'Please choose shipping district.',
			'shipping_address' => 'Shipping address is required.',
			'tax_code' => 'Tax code is a number.',
			'order_note' => 'Notes not more than 500 characters.',
			'missing' => 'Please fill in the checkout form.'
		)	
	),
	
	'signin' => array(
		'title' => 'Sign In',
		'rewrite' => 'signin',
		'subject' => 'I have an Account',
		'email' => 'Email',
		'password' => 'Password',
		'remember' => 'Remember me',
		'forgot' => 'Forgot your password ?',
		'create' => 'or Create an Account ?',
		'errors' => array(
			'email' => array(
				'require' => 'Email is required.',
				'invalid' => 'Email is invalided.'
			),
			'password' => 'Password is required.',
			'activate' => 'Please check your email to activate your account before logging.',
			'locked' => 'You account is temporarily locked.\nPlease contact administrator for more information.',
			'incorrect' => 'Email or password is incorrect.',
			'missing' => 'Please fill in the sign in form.'
		)
	),
	
	'forgot' => array(
	    'title' => 'Forgot your password',
		'rewrite' => 'forgot-password',
		'desc' => 'To recover your Account password, please enter your email address in the field below.',
		'email' => 'Email',
		'security_code' => 'Security code',
		'button' => 'Submit',
		'errors' => array(
			'email' => array(
				'require' => 'Email is required.',
				'invalid' => 'Email is invalided.'
			),
			'security_code' => 'Security code is incorrect.',
			'missing' => 'Please fill in the forgot password form.'
		),
		'success' => 'Please check your e-mail.\nWe have sent you an email with the information required to submit a new password.',
		'confirm_subject' => 'Request a password in ',
		'newpwd_subject' => 'Your new password in '
	),	
	
	'changepwd' => array(
		'title' => 'Change Password',
		'rewrite' => 'change-password',
		'oldpwd' => 'Old password',
		'newpwd' => 'New password',
		'renewpwd' => 'Re-type new password',		
		'button' => 'Change',
		'errors' => array(
			'oldpwd' => 'Old password is required.',
			'newpwd' => 'New password is required.',
			'renewpwd' => 'New password does not match the confirm new password.',
			'oldpwd_incorrect' => 'Old password is incorrect.',
			'newpwd_invalid' => 'New password must not match with old password.',
			'missing' => 'Please fill in the change password form.'
		),
		'success' => 'Password has been changed successfully.'		
	),
	
	'member' => array(
		'signup' => 'Member Register',
		'signup_desc' => ' If you already have an account, you can ',
		'title' => 'Member Information',
		'modify' => 'Modify',
		'email' => 'Email',
		'password' => 'Password',
		'repassword' => 'Confirm password',
		'salutation' => array(
			'title' => 'Salutation',
			'list' => array(
			  	'Mr.' => 'Mister',
				'Mrs.' => 'Mrs',
			  	'Ms.' => 'Miss'
			)
		),
		'name' => 'Fullname',
		'birthdate' => 'Birthdate',
		'birthdate_format' => 'Day/Month/Year',
		'phone' => 'Phone',
		'mobile' => 'Mobile',
		'country' => 'Country',
		'city' => 'City',
		'district' => 'District',
		'address' => 'Address',
		'address_format' => 'No., Street, Ward',
		'terms' => 'I have READ and AGREED with the ',
		'button' => array(
			'register' => 'Register',
			'change' => 'Changes',
			'cancel' => 'Cancel',
			'reset' => 'Reset'
		),
		'memos' => array(
			'email' => 'Very important, which is used to activate your account',
			'password' => 'Minimum length of 06 characters',
			'mobile' => 'Used to contact the delivery',
			'address' => '205/11/13 Pham Van Chieu St. , Ward 14'
		),
		'errors' => array(
			'email' => array(
				'invalid' => 'Email is invalided.',
				'isset' => 'This email address already exists in the system.'				
			),
			'password' => 'A password must be at least 8 and no more than 32 characters in length.',
			'repassword' => 'Password and confirm password does not match.',
			'salutation' => 'Please choose your salutation.',
			'name' => 'Fullname is required.',
			'birthdate' => 'Birthdate is required.',
			'birthdate_format' => 'Format date of birth (dd/mm /yyyy) is invalid.\nOr birth year should be between ',
			'phone' => 'Phone is a number.',
			'mobile' => 'Mobile is a  number.',
			'country' => 'Please choose your country.',
			'city' => 'Please choose your city.',
			'district' => 'Please choose your district.',
			'address' => 'Address is required.',
			'missing' => 'Please fill in the member form.'			
		),
		'thanks' => 'Thank you for registering at ',
		'success' => 'Your information has been updated.',
		'activate_subject' => 'Activate account in '
	),
	
	'others' => array(
		'home' => array(
			'title' => 'Home',
			'rewrite' => 'home'
		),
		'product' => array(
			'rewrite' => 'product'
		),
		'finished' => array(
			'title' => 'Finished Order',
			'rewrite' => 'finished-order',
			'mailtitle' => 'Your Order information at website',
			'success' => '<center><strong>Order Success!</strong><br /><br />An email containing the order information has been sent to you.<br />Very pleased to serve you!</center>'
		),
		'search' => array(
			'title' => 'Search',
			'rewrite' => 'search',
			'box' => array(
				'title' => 'Search by Price',
				'from' => 'From',
				'to' => 'To',
				'button' => 'Search'
			)
		),
		'support' => array(
			'title' => 'Online Support'
		),
		'advertising' => array(
			'title' => 'Advertising'
		),
		'visitor' => array(
			'title' => 'Visitor',
			'online' => 'Online',
			'visited' => 'Visited'
		),
		'textlink' => array(
			'title' => 'Links'
		),
		'partner' => array(
			'title' => 'Partners'
		),
		'pricing' => array(
			'title' => 'Pricing'
		),
		'news' => array(
			'title' => 'News',
			'rewrite' => 'news',
			'last' => 'Last News'
		),
		'videos' => array(
			'title' => 'Videos',
			'rewrite' => 'video'
		),
		'textlink' => array(
			'title' => 'Site Links'
		),
		'map' => array(
			'title' => 'Map',
			'rewrite' => 'map'
		),
		'aboutus' => array(
			'title' => 'About Us',
			'rewrite' => 'about-us'
		),
		'sitemap' => array(
			'collapseall' => 'Collapse All',
			'expandall' => 'Expand All'
		),
		'transfer' => array(
			'rewrite' => 'transfer-information'			
		),
		'information' => array(
			'title' => 'Information'			
		),
		'customer_service' => array(
			'title' => 'Customer Service'			
		),
		'or' => 'or',
		'collection' => array(
			'title' => 'Collection',
			'rewrite' => 'collection',
		),
		'viewed_products' => 'Recently Viewed Products',
		'color' => 'Color',
		'size' => 'Size',
		'close' => 'Close',
		'signup' => array(
			'title' => 'Signup',
			'rewrite' => 'signup'
		),
		'signin' => array(
			'title' => 'Signin',
			'full_title' => 'Signin',
			'rewrite' => 'signin'
		),
		'terms' => array(
			'title' => 'Terms and Conditions',
			'rewrite' => 'terms-and-conditions'
		),
		'ex' => 'Ex',
		'catalog' => 'Catalog',
		'review' => 'Reviews',
		'brand' => 'Brand',
		'please_select' => 'Please Select',
		'help' => 'Help',
		'newsletter' => array(
			'title' => 'Newsletter Sign-Up',
			'desc' => 'Please enter a your email to get the newsletter from %%SITE_NAME%% and manufacturers.',
			'email' => 'Your Email',
			'phone' => 'Your Phone',
			'button' => 'Submit',
			'errors' => array(
				'email' => 'Email incorrect format.',
				'phone' => 'Phone is a number.',
				'missing' => 'Please fill in the form.',
			),
			'success' => array(
				'text' => 'Thank you for registering for our newsletter.'
			)
		)
	),
	
	'panel' => array(
		'member' => array(
			'title' => 'Member Info',
			'rewrite' => 'member-info'
		),
		'order' => array(
			'title' => 'Order List',
			'rewrite' => 'order-list'
		),
		'changepwd' => array(
			'title' => 'Change Password',
			'rewrite' => 'change-password'
		),
		'signout' => array(
			'title' => 'Sign out',
			'confirm' => 'Are you sure you want to sign out?'
		)		
	),
	
	'order' => array(
		'title' => 'Order List',
		'rewrite' => 'order-list',
		'code' => 'Code',
		'ordered' => 'Ordered',
		'to' => 'Shipping To',
		'total' => 'Total',
		'status' => array(
			'title' => 'Order Status',
			'list' => array(
				'Processing' => 'Processing',
				'Confirmed' => 'Confirmed',
				'Paid' => 'Paid',
				'Completed' => 'Completed',
				'Canceled' => 'Canceled'				
			)
		),
		'view' => 'View Order',
		'detail' => 'Order Detail',
		'list' => 'Ordered Item List'
		
	)
);
<?php
namespace WprAddons\Admin\Templates;

use WprAddons\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPR_Templates_Data {
	public static function get_available_kits() {
		$is_pro_active = wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION');
		$is_cf7_active = is_plugin_active('contact-form-7/wp-contact-form-7.php') ? 'true' : 'false';
		$is_mla_active = is_plugin_active('media-library-assistant/index.php') ? 'true' : 'false';

		return [
			'nature' => [
				'v1' => [
					'name' => 'nature',
					'pages' => 'home,about,services,projects,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'nature influencer travel blogger blog content tourism influencers creator tourism tourism travel forest slider generic multipurpose national-park nature-park sanctuary wilderness hitchhiking mountain river lakes outdoors',
					'price' => $is_pro_active ? 'free' : 'free',
				],
			],
			'portfolio' => [
				'v1' => [
					'name' => 'Portfolio/CV',
					'pages' => 'home,about,portfolio,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'portfolio personal cv designer ux artist artwork personal resume photographer',
					'price' => $is_pro_active ? 'free' : 'free',
				],
			],
			'pizza' => [
				'v1' => [
					'name' => 'Pizza Restaurant',
					'pages' => 'home,menu,about,offer,gallery,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'pizza italian restaurant food slider pasta fastfood fast food recipes cooking',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'travel' => [
				'v1' => [
					'name' => 'Travel Blogger & Influencer',
					'pages' => 'home,about,stories,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'nature influencer travel blogger blog content tourism influencers creator tourism tourism travel forest slider generic multipurpose national-park nature-park sanctuary wilderness hitchhiking mountain river lakes outdoors',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'cybersecurity' => [
				'v1' => [
					'name' => 'Cybersecurity',
					'pages' => 'home,about,services,pricing,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'cybersecurity data protection hacker security dark digital technology cybercrime computer windows technician',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'photographer' => [
				'v1' => [
					'name' => 'Photographer Portfolio Dark',
					'pages' => 'home,about,services,portfolio,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .', "media-library-assistant":'. $is_mla_active .'}',
					'tags' => 'portfolio personal cv designer ux artist artwork personal resume camera fashion lens modelling photographer photography videography wedding shoot ',
					'price' => $is_pro_active ? 'free' : 'free',
				],
				'v2' => [
					'name' => 'Photographer Portfolio Light',
					'pages' => 'home,about,services,portfolio,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .', "media-library-assistant":'. $is_mla_active .'}',
					'tags' => 'portfolio personal cv designer ux artist artwork personal resume camera fashion lens modelling photographer photography videography wedding shoot ',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'cryptocurrency' => [
				'v1' => [
					'name' => 'Cryptocurrency',
					'pages' => 'home,about,services,token,pricing,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'cryptocurrency bitcoin ethereum etherium blockchain protection nft coin corporate crypto dark startup token digital',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'skincare' => [
				'v1' => [
					'name' => 'Skin Care',
					'pages' => 'home,about,services,procedures,gallery,pricing,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'skincare skin care beauty clean face skin-beauty health wellness',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'lawyer' => [
				'v1' => [
					'name' => 'Lawyer',
					'pages' => 'home,practice,faq,reviews,attorney,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'lawyers criminal defence lawyer firm divorce lawyer family lawyer law legal firm ',
					'price' => $is_pro_active ? 'free' : 'free',
				],
			],
			'medical' => [
				'v1' => [
					'name' => 'Medical',
					'pages' => 'home,about,services,doctors,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'medical clinic dental health healthcare doctor therapist wellness treatment cure',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'digitalagency' => [
				'v1' => [
					'name' => 'Digital Agency',
					'pages' => 'home,about,services,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'digital agency company corporate digital services office agency web marketing',
					'price' => $is_pro_active ? 'free' : 'free',
				],
				'v2' => [
					'name' => 'Digital Agency',
					'pages' => 'home,about,services,pricing,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'digital agency company corporate digital services office agency web marketing',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'drone' => [
				'v1' => [
					'name' => 'Drone Project',
					'pages' => 'home,about,gallery,services,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'drone photography aerial photo ',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'architecture' => [
				'v1' => [
					'name' => 'Architecture',
					'pages' => 'home,about,portfolio,services,faq,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'architecture company slider interior design designer landscaping office zoning building',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'fooddelivery' => [
				'v1' => [
					'name' => 'Food Delivery',
					'pages' => 'home,services,blog,faq,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'fooddelivery fast food chain restaurant service hotel italian pasta pizza pizzeria burger recipes cooking',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'construction' => [
				'v1' => [
					'name' => 'Construction',
					'pages' => 'home,about,services,projects,pricing,contact,faq,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'construction architecture company interior office real estate',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'ittech' => [
				'v1' => [
					'name' => 'IT Tech v1',
					'pages' => 'home,about,services,pricing,faq,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'ittech advanced technology it technique computer windows technician digital',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
				'v2' => [
					'name' => 'IT Tech v2',
					'pages' => 'home,about,services,pricing,faq,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'ittech advanced technology it technique computer windows technician digital',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'realestate' => [
				'v1' => [
					'name' => 'Real Estate',
					'pages' => 'home,properties,about,services,faq,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'real estate agency company construction property rentals estate sales developers',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'restaurant' => [
				'v1' => [
					'name' => 'Restaurant',
					'pages' => 'home,about,gallery,menu,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'restaurant fastfood slider hotel italian pizza pizzeria pasta dinner fast food wine recipe recipes cooking',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
			'winebar' => [
				'v1' => [
					'name' => 'Wine Bar & Restaurant',
					'pages' => 'home,story,wines,dishes,events,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'wine bar winery beer drink alcohol pub events dish wines italian restaurant food slider recipes cooking recipes',
					'price' => $is_pro_active ? 'free' : 'free',
				],
			],
			'wedding' => [
				'v1' => [
					'name' => 'Wedding',
					'pages' => 'home,about,services,blog,gallery,contact,',
					'plugins' => '{"contact-form-7":'. $is_cf7_active .'}',
					'tags' => 'wedding party event slider invitation planner',
					'price' => $is_pro_active ? 'free' : 'pro',
				],
			],
		];
	}

	public static function get_available_blocks() {
		return [
			'grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'grid/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'grid/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'grid/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'grid/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'grid/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'grid/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'grid/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'grid/v9/'],
				'v10-pro' => ['type' => 'iframe', 'url' => 'grid/v10/'],
				'v11' => ['type' => 'iframe', 'url' => 'grid/v11/'],
				'v12' => ['type' => 'iframe', 'url' => 'grid/v12/'],
				'v13' => ['type' => 'iframe', 'url' => 'grid/v13/'],
				'v14' => ['type' => 'iframe', 'url' => 'grid/v14/'],
			],
			'woo-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v2/'],
				'v3-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v9/'],
			],
			'media-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'image-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'image-grid/v2/'],
			],
			'magazine-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'magazine-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'magazine-grid/v2/'],
				// 'v3' => ['type' => 'iframe', 'url' => 'magazine-grid/v3/', 'sub' => 'carousel'], <-- Keep as example
				'v3-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'magazine-grid/v8/'],
			],
			'advanced-slider' => [
				'v1' => ['type' => 'iframe', 'url' => 'advanced-slider/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'advanced-slider/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'advanced-slider/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'advanced-slider/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'advanced-slider/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'advanced-slider/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'advanced-slider/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'advanced-slider/v8/'],
			],
			'posts-timeline' => [
				'v1' => ['type' => 'iframe', 'url' => 'timeline/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'timeline/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'timeline/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'timeline/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'timeline/v5/'],
			],
			'testimonial' => [
				'v1' => ['type' => 'iframe', 'url' => 'testimonial-slider/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'testimonial-slider/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'testimonial-slider/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'testimonial-slider/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'testimonial-slider/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'testimonial-slider/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'testimonial-slider/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'testimonial-slider/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'testimonial-slider/v9/'],
			],
			'nav-menu' => [
				'v1' => ['type' => 'iframe', 'url' => 'nav-menu/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'nav-menu/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'nav-menu/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'nav-menu/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'nav-menu/v5/'],
				'v6' => ['type' => 'iframe', 'url' => 'nav-menu/v6/'],
			],
			'onepage-nav' => [
				'v1' => ['type' => 'iframe', 'url' => 'one-page-navigation/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'one-page-navigation/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'one-page-navigation/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'one-page-navigation/v4/'],
			],
			'pricing-table' => [
				'v1' => ['type' => 'iframe', 'url' => 'pricing-table/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'pricing-table/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'pricing-table/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'pricing-table/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'pricing-table/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'pricing-table/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'pricing-table/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'pricing-table/v8/'],
			],
			'content-toggle' => [
				'v1' => ['type' => 'iframe', 'url' => 'content-toggle/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'content-toggle/v2/'],
				'v3-pro' => ['type' => 'iframe', 'url' => 'content-toggle/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'content-toggle/v4/'],
			],
			'countdown' => [
				'v1' => ['type' => 'iframe', 'url' => 'countdown/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'countdown/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'countdown/v3/'],
			],
			'progress-bar' => [
				'v1' => ['type' => 'iframe', 'url' => 'progress-bar/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'progress-bar/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'progress-bar/v3/'],
			],
			'tabs' => [
				'v1' => ['type' => 'iframe', 'url' => 'tabs/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'tabs/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'tabs/v3/'],
			],
			'advanced-text' => [
				'v1' => ['type' => 'iframe', 'url' => 'advanced-text/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'advanced-text/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'advanced-text/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'advanced-text/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'advanced-text/v5/'],
				'v6' => ['type' => 'iframe', 'url' => 'advanced-text/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v9/'],
				'v10-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v10/'],
				'v11-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v11/'],
				'v12-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v12/'],
			],
			'flip-box' => [
				'v1' => ['type' => 'iframe', 'url' => 'flip-box/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'flip-box/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'flip-box/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'flip-box/v4/'],
			],
			'promo-box' => [
				'v1' => ['type' => 'iframe', 'url' => 'promo-box/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'promo-box/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'promo-box/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'promo-box/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'promo-box/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'promo-box/v6/'],
			],
			'before-after' => [
				'v1' => ['type' => 'iframe', 'url' => 'before-and-after/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'before-and-after/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'before-and-after/v3/'],
			],
			'image-hotspots' => [
				'v1' => ['type' => 'iframe', 'url' => 'image-hotspot/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'image-hotspot/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'image-hotspot/v3/'],
			],
			'forms' => [],
			'mailchimp' => [
				'v1' => ['type' => 'iframe', 'url' => 'mailchimp/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'mailchimp/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'mailchimp/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'mailchimp/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'mailchimp/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'mailchimp/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'mailchimp/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'mailchimp/v8/'],
			],
			'content-ticker' => [
				'v1' => ['type' => 'iframe', 'url' => 'content-ticker/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'content-ticker/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'content-ticker/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'content-ticker/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'content-ticker/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'content-ticker/v6/'],
			],
			'button' => [
				'v1' => ['type' => 'iframe', 'url' => 'button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'button/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'button/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'button/v5/'],
			],
			'dual-button' => [
				'v1' => ['type' => 'iframe', 'url' => 'dual-button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'dual-button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'dual-button/v3/'],
			],
			'team-member' => [
				'v1' => ['type' => 'iframe', 'url' => 'team-member/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'team-member/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'team-member/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'team-member/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'team-member/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'team-member/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'team-member/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'team-member/v8/'],
			],
			'google-maps' => [
				'v1' => ['type' => 'iframe', 'url' => 'google-map/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'google-map/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'google-map/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'google-map/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'google-map/v5/'],
			],
			'price-list' => [
				'v1' => ['type' => 'iframe', 'url' => 'price-list/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'price-list/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'price-list/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'price-list/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'price-list/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'price-list/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'price-list/v7/'],
			],
			'business-hours' => [
				'v1' => ['type' => 'iframe', 'url' => 'business-hours/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'business-hours/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'business-hours/v3/'],
			],
			'sharing-buttons' => [
				'v1' => ['type' => 'iframe', 'url' => 'sharing-button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'sharing-button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'sharing-button/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'sharing-button/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'sharing-button/v5/'],
			],
			'logo' => [],
			'search' => [
				'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			],
			'phone-call' => [],
			'back-to-top' => [],
			'lottie-animations' => [],
			'popup-trigger' => [],
		];
	}

	public static function get_available_popups() {
		return [
			// 'contact' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
			'cookie' => [
				'v1' => ['type' => 'image', 'url' => 'popups/cookie/v1-preview.jpg'],
				'v2-pro' => ['type' => 'image', 'url' => 'popups/cookie/v2-pro-preview.jpg'],
				'v3-pro' => ['type' => 'image', 'url' => 'popups/cookie/v3-pro-preview.jpg'],
				'v4-pro' => ['type' => 'image', 'url' => 'popups/cookie/v4-pro-preview.jpg'],
			],
			'discount' => [
				'v1' => ['type' => 'image', 'url' => 'popups/discount/v1-preview.jpg'],
				'v2' => ['type' => 'image', 'url' => 'popups/discount/v2-preview.jpg'],
				'v3-pro' => ['type' => 'image', 'url' => 'popups/discount/v3-pro-preview.jpg'],
				'v4-pro' => ['type' => 'image', 'url' => 'popups/discount/v4-pro-preview.jpg'],
				'v5' => ['type' => 'image', 'url' => 'popups/discount/v5-preview.jpg'],
				'v6' => ['type' => 'image', 'url' => 'popups/discount/v6-preview.jpg'],
				'v7-pro' => ['type' => 'image', 'url' => 'popups/discount/v7-pro-preview.jpg'],
				'v8-pro' => ['type' => 'image', 'url' => 'popups/discount/v8-pro-preview.jpg'],
				'v9' => ['type' => 'image', 'url' => 'popups/discount/v9-preview.jpg'],
				'v10' => ['type' => 'image', 'url' => 'popups/discount/v10-preview.jpg'],
				'v11-pro' => ['type' => 'image', 'url' => 'popups/discount/v11-pro-preview.jpg'],
				'v12-pro' => ['type' => 'image', 'url' => 'popups/discount/v12-pro-preview.jpg'],
				'v13-pro' => ['type' => 'image', 'url' => 'popups/discount/v13-pro-preview.jpg'],
				'v14' => ['type' => 'image', 'url' => 'popups/discount/v14-preview.jpg'],
				'v15' => ['type' => 'image', 'url' => 'popups/discount/v15-preview.jpg'],
				'v16-pro' => ['type' => 'image', 'url' => 'popups/discount/v16-pro-preview.jpg'],
			],
			'subscribe' => [
                'v1-pro' => ['type' => 'image', 'url' => 'popups/subscribe/v1-pro-preview.jpg'],  
			    'v2-pro' => ['type' => 'image', 'url' => 'popups/subscribe/v2-pro-preview.jpg'],
				'v3-pro' => ['type' => 'image', 'url' => 'popups/subscribe/v3-pro-preview.jpg'],
		    ],
			'yesno' => [
				'v1-pro' => ['type' => 'image', 'url' => 'popups/yesno/v1-pro-preview.jpg'],  
			    'v2-pro' => ['type' => 'image', 'url' => 'popups/yesno/v2-pro-preview.jpg'],
				'v3-pro' => ['type' => 'image', 'url' => 'popups/yesno/v3-pro-preview.jpg'],
			],
		];
	}
}
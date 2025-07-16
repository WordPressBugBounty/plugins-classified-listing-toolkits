<?php

namespace RadiusTheme\ClassifiedListingToolkits\Admin;


use RadiusTheme\ClassifiedListingToolkits\Hooks\DiviHooks;
use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;

Class DiviController {


    public function __construct()
    {

        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'front_end_script' ], 99 );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_builder_scripts' ], 999 );
	    add_action( 'wp_enqueue_scripts', [ __CLASS__, 'rtcl_reb_et_builder_enqueue_assets_main' ], 999999 );
        $this->hooks();
    }
	public static function rtcl_reb_et_builder_enqueue_assets_main() {

		$custom_css = <<<CSS

			li.et_fb_classified_general_module.et_pb_folder:before{
			    content: "" !important;
				background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='20' height='20' id='Layer_1' xmlns='http://www.w3.org/2000/svg' version='1.1' viewBox='0 0 20 20'%3E%3C!-- Generator: Adobe Illustrator 29.1.0, SVG Export Plug-In . SVG Version: 2.1.0 Build 142) --%3E%3Cdefs%3E%3Cstyle%3E .st0 %7B fill: %2300ffd8; %7D .st1 %7B fill: %23fff; %7D .st2 %7B fill: %232828ea; %7D %3C/style%3E%3C/defs%3E%3Cpath class='st2' d='M14.6,9.4c.3-.7.5-1.5.5-2.3,0-2.7-2.3-4.9-5-5s-5.2,2.2-5.2,5.1c0,2.8,2.2,5.1,5,5.2s1.3-.1,1.9-.3,1.2,0,1.6.4h0c.5.5.6,1.4.1,2l-3.1,4c-.2.2-.6.2-.8,0l-5.4-6.9c-.9-1.2-1.4-2.7-1.4-4.3,0-2.3,1.1-4.4,2.9-5.8C7,.5,8.5,0,10,0s1.5.1,2.3.4c2.1.7,3.9,2.4,4.6,4.6.6,1.8.4,3.7-.3,5.3s-1.2.8-1.7.3h0c-.3-.3-.4-.8-.2-1.2Z'/%3E%3Cellipse class='st2' cx='10' cy='19.6' rx='2.7' ry='.2'/%3E%3Crect class='st1' x='8.5' y='6.6' width='.8' height='.1' rx='0' ry='0'/%3E%3Crect class='st2' x='9.4' y='6.6' width='.5' height='.1' rx='0' ry='0'/%3E%3Cpath class='st0' d='M8,6.8s0,0,0,0h-.1c0-.1,0-.2,0-.2s0,0,0,0h0c0,0,.2-.1.2-.1,0,0,0,0,0,0s0,0,0,0l-.2.2s0,0,0,0Z'/%3E%3Crect class='st1' x='8.5' y='7.2' width='.8' height='.1' rx='0' ry='0'/%3E%3Crect class='st2' x='9.4' y='7.2' width='.5' height='.1' rx='0' ry='0'/%3E%3Cpath class='st0' d='M8,7.4s0,0,0,0h-.1c0-.1,0-.2,0-.2s0,0,0,0h0c0,0,.2-.1.2-.1,0,0,0,0,0,0s0,0,0,0l-.2.2s0,0,0,0Z'/%3E%3Crect class='st1' x='8.5' y='7.8' width='.8' height='.1' rx='0' ry='0'/%3E%3Crect class='st2' x='9.4' y='7.8' width='.5' height='.1' rx='0' ry='0'/%3E%3Cpath class='st0' d='M8,8.1s0,0,0,0h-.1c0-.1,0-.2,0-.2s0,0,0,0h0c0,0,.2-.1.2-.1,0,0,0,0,0,0s0,0,0,0l-.2.2s0,0,0,0Z'/%3E%3Cpath class='st2' d='M9,10c-1.5,0-2.7-1.2-2.7-2.7s1.2-2.7,2.7-2.7.5.2.5.5-.2.5-.5.5c-.9,0-1.7.8-1.7,1.7s.8,1.7,1.7,1.7.9-.2,1.3-.6h0v-3.4h0c0-.3.3-.5.5-.5,0,0,0,0,0,0,.3,0,.5.2.5.5v3.5c0,.2.1.3.3.3h1.5c.1,0,.3,0,.4.2,0,.1.1.2.1.4,0,.3-.2.5-.5.5h-2.3c0,0-.1,0-.2,0,0,0,0,0,0,0-.1,0-.2-.1-.3-.2h0s0,0,0,0c-.4.2-.8.3-1.3.3Z'/%3E%3C/svg%3E");	
				width: 20px;
			    height: 20px;
			    display: block !important;
			    background-repeat: no-repeat;
			    margin: auto;
			    transform: scale(.95);
			    margin-bottom: 1px !important;
			}
CSS;
		wp_add_inline_style( 'et-cloud-styles', $custom_css );
	}

    private function hooks() {
            DiviHooks::init();
    }
    public static function front_end_script() {

		if(!Helper::is_divi_builder_preview()){
			return;
		}

        wp_register_style( "rtcl-divi-addons", CLASSIFIED_LISTING_TOOLKITS_BUILD . "/divi-frontend-element.css", [ 'rtcl-public' ], CLASSIFIED_LISTING_TOOLKITS_VERSION );
        wp_register_script( "rtcl-divi-addons", CLASSIFIED_LISTING_TOOLKITS_BUILD . "/divi-frontend-element.js", [ 'jquery' ], CLASSIFIED_LISTING_TOOLKITS_VERSION, true );

        wp_enqueue_style( "rtcl-divi-addons" );

        wp_enqueue_script( 'rtcl-divi-modules', CLASSIFIED_LISTING_TOOLKITS_BUILD . "/divi-element.js",
            [ 'jquery', 'react-dom', 'react', 'et_pb_media_library', 'wp-element', 'wp-i18n' ],
            CLASSIFIED_LISTING_TOOLKITS_VERSION, true );

        $localize = [
            'rtcl_nonce' => wp_create_nonce( 'rtcl-nonce' ),
            'is_divi_plugin_active' => Helper::is_divi_plugin_active()
        ];

        wp_localize_script( 'rtcl-divi-modules', 'rtcl_divi', $localize );
    }

    public static function enqueue_builder_scripts() {
        if ( function_exists('et_core_is_fb_enabled') && ! et_core_is_fb_enabled() ) {
            return;
        }

        wp_enqueue_script( 'rtcl-divi-builder', CLASSIFIED_LISTING_TOOLKITS_BUILD . "/divi-frontend-element.js",
            [ 'jquery', 'swiper', 'rtcl-divi-modules' ],
            CLASSIFIED_LISTING_TOOLKITS_VERSION,
            true
        );
    }

}
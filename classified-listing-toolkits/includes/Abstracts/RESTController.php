<?php

namespace RadiusTheme\ClassifiedListingToolkits\Abstracts;

use WP_REST_Controller;

/**
* Rest Controller base class.
*
* @since 0.3.0
*/
abstract class RESTController extends WP_REST_Controller {

    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'custom/v1';

    /**
     * Check default permission for rest routes.
     *
     * @since 0.3.0
     *
     * @TODO: manage permissions from capabilities.
     *
     * @return bool
     */
    public function check_permission(): bool {
        return true;
        // phpcs:disable Squiz.PHP.CommentedOutCode.Found
        // return current_user_can( 'manage_jobs' );
        //phpcs:enable
    }
}

<?php

namespace Strata\Controller\Loader;

use Strata\Controller\Controller;
use Exception;

/**
 * Allows bridging between Strata and Wordpress for declaring shortcodes.
 */
class ShortcodeLoader
{

    /**
     * A list of wordpress shortcode mapped in a array("shortcodename" => "functionname") way.
     * @var  array
     */
    private $shortcodes = array();

    /**
     * A Strata Controller instance to which shortcodes callbacks will be forwarded
     * @var null
     */
    private $controller = null;

    public function __construct(Controller $controller)
    {
        if (is_null($controller)) {
            throw new Exception("No controller has been defined for shortcode callback.");
        }

        $this->controller = $controller;
        $this->shortcodes = $controller->shortcodes;
    }

    /**
     * Specifies if a number of shortcodes have been defined.
     * @return boolean True if some are defined.
     */
    public function hasShortcodes()
    {
        return count($this->shortcodes) > 0;
    }

    /**
     * Registers dynamic shortcodes hooks to the instantiated controller.
     * Note that these are not available when this instance of the controller
     * is not being loaded.
     * @return  null
     */
    public function register()
    {
        if ($this->hasShortcodes()) {
            foreach ($this->shortcodes as $shortcode => $methodName) {
                if (method_exists($this->controller, $methodName)) {
                    add_shortcode($shortcode, array($this->controller, $methodName));
                }
            }
        }
    }

    /**
     * Unregisters the list of shortcodes in Wordpress.
     * @return null
     */
    public function unregister()
    {
        if ($this->hasShortcodes()) {
            foreach ($this->shortcodes as $shortcode => $methodName) {
                remove_shortcode($shortcode);
            }
        }
    }
}

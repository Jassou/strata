<?php
namespace Strata\View;

use Strata\View\Template;
use Strata\View\Helper\Helper;
use Strata\Utility\Inflector;
use Strata\Router\Router;

/**
 * Handles the generation of view html. It is important to understand this is not used
 * when a request continues the regular Wordpress flow which generates its own templates.
 *
 * This class is used when a controller is printing the view object itself, as it is often the
 * case for AJAX, file downloads and numeric returns.
 */
class View
{

    /**
     * Rendering options, used when explicitly rendering a view from a controller.
     * @var array
     */
    protected $options = array();

    /**
     * The list of variables to be declared when loading the view
     * @var array
     */
    protected $templateVars = array();

    public function loadTemplate($path)
    {
        return Template::parse($path, $this->getVariables());
    }

    public function loadHelper($helperName, $config = array())
    {
        $helper = Helper::factory($helperName, $config);
        $name = array_key_exists("name", (array)$config) ? $config["name"] : $helper->getShortName();
        $this->set($name, $helper);
    }

    /**
     * Assigns the variable to the current view
     */
    public function set($name, $value)
    {
        $this->templateVars[$name] = $value;
    }

    /**
     * Returns the list of currently declared variables in this view.
     * @return array An associative array of values
     */
    public function getVariables()
    {
        return $this->templateVars;
    }

    /**
     * Returns a variable assigned to the current view
     * @return mixed
     */
    public function get($name)
    {
        return $this->templateVars[$name];
    }

    /**
     * Checks whether a variable is assigned to the current view
     * @return boolean
     */
    public function check($name)
    {
        return array_key_exists($name, $this->templateVars);
    }

    /**
     * Renders on the page and end the process. Useful for simple HTML returns or data in another format like JSON.
     * @param  array $options An associative array of rendering options
     * @return string          The rendered content.
     */
    public function render($options = array())
    {
        $this->options = $options + array(
            "Content-type" => "text/html",
            "Content-disposition" => null,
            "content" => "",
            // Only the admin does not end Wordpress' parsing of the views
            // because we hook in the middle of the page.
            "end" =>  is_admin() && !Router::isAjax() ? false : true
        );

        $content = $this->parseCurrentContent();

        if ((bool)$this->options['end']) {
            // Only play with headers when we know we will
            // kill the process.
            $this->applyHeaders();

            echo $content;
            exit();
        }

        echo $content;
    }

    /**
     * Takes the current value of the content option and ensure that it is
     * correctly formatted for output. If the content is an array or an object, the value.
     * is encoded to a json string. Otherwise, the content simply cast into string.
     * @return string The content of the view
     */
    protected function parseCurrentContent()
    {
        if (is_array($this->options['content']) || is_object($this->options['content'])) {
            return json_encode($this->options['content']);
        }

        return "" . $this->options['content'];
    }

    /**
     * Applies header values sent as options. For the time being, only Content-type and Content-disposition
     * are supported.
     * @return null
     */
    protected function applyHeaders()
    {
        if ($this->options['Content-type'] != "text/html") {
            header('Content-type: ' . $this->options['Content-type']);
        }

        if (!is_null($this->options['Content-disposition'])) {
            header('Content-disposition: ' . $this->options['Content-disposition']);
        }
    }
}

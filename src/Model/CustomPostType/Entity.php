<?php
namespace Strata\Model\CustomPostType;

use Strata\Model\Model;

use Strata\Model\CustomPostType\QueriableEntity;
use Strata\Model\CustomPostType\Registrar\CustomPostTypeAdminMenuRegistrar;
use Strata\Model\CustomPostType\Registrar\CustomPostTypeRegistrar;
use Strata\Model\CustomPostType\Registrar\TaxonomyRegistrar;

use Strata\Utility\Hash;

class Entity extends QueriableEntity
{
    public $wpPrefix = "cpt_";
    public $admin_menus = array();
    public $belongs_to  = array();
    public $routed      = false;

    /**
     * Registers the custom post type in Wordpress. A Custom post type
     * must trigger this during the 'init' state for it to be recognized
     * automatically by Wordpress.
     */
    public function register()
    {
        $registrars = array(
            new CustomPostTypeRegistrar($this),
            new TaxonomyRegistrar($this)
        );

        foreach ($registrars as $registrar) {
            $registrar->register();
        }
    }

    public function registerAdminMenus()
    {
        $registration = new CustomPostTypeAdminMenuRegistrar($this);
        $registration->configure(Hash::normalize($this->admin_menus));
        $registration->register();
    }

    /**
     * Returns the model's menu icon
     * @return string
     */
    public function getIcon()
    {
        if (array_key_exists('menu_icon', $this->configuration)) {
            return $this->configuration['menu_icon'];
        }

        return 'dashicons-admin-post';
    }

    /**
     * Returns whether or not the current model supports and has taxonomies.
     * @return boolean True if model has taxonomies
     */
    public function hasTaxonomies()
    {
        return count($this->belongs_to) > 0;
    }

    /**
     * Gets the associated taxonomy objects.
     * @return array
     */
    public function getTaxonomies()
    {
        $tax = array();

        foreach (Hash::normalize($this->belongs_to) as $taxonomyName => $taxonomyConfig) {
            if (class_exists($taxonomyName)) {
                $tax[] = new $taxonomyName();
            }
            else {
                $tax[] = Model::factory($taxonomyName);
            }
        }
        return $tax;
    }

    /**
     * Creates a post of the current post type
     * @param (array) options : Options to be sent to wp_insert_post()
     * @return (int) postID
     */
    public function create($options)
    {
        $options += array(
            'post_type'         => self::wordpressKey(),
            'ping_status'       => false,
            'comment_status'    => false
        );

        return wp_insert_post( $options );
    }

    public function update($options)
    {
        return wp_update_post( $options );
    }

    public function delete($postId, $force = false)
    {
        return wp_delete_post( $postId, $force);
    }

}

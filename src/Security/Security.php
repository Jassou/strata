<?php
namespace Strata\Security;

/**
 * Assigns callback to common WP functions that needs additional security
 *
 * @package Strata.Security
 */
class Security
{

    public function addMesures()
    {
        if (function_exists('add_filter')) {
            $this->handleComments();
        }
    }

    protected function handleComments()
    {
        $parser = new CommentParser();
        $parser->register();
    }
}

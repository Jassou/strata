<?php

namespace Strata\Model\Validator;

class NumericValidator extends RegexValidator
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setMessage(__("Only numeric values are allowed.", $this->getTextdomain()));
        $this->setConfig("pattern", "/\d/i");
    }
}

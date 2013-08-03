<?php

namespace Zodyac\Behat\ExtensibleHtmlFormatter\Formatter;

use Behat\Behat\Formatter\FormatterDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Formatter dispatcher for the ExtensibleHtmlFormatter class.
 *
 * Sets the event dispatcher on the formatter when it has been created.
 */
class ExtensibleHtmlFormatterDispatcher extends FormatterDispatcher
{
    protected $dispatcher;

    public function __construct($class, $name = null, $description = null, EventDispatcher $dispatcher = null)
    {
        parent::__construct($class, $name, $description);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Initializes formatter instance.
     *
     * @return FormatterInterface
     */
    public function createFormatter()
    {
        $formatter = parent::createFormatter();
        $formatter->setDispatcher($this->dispatcher);

        return $formatter;
    }
}

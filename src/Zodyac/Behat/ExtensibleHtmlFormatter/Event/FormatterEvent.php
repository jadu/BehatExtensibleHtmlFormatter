<?php

namespace Zodyac\Behat\ExtensibleHtmlFormatter\Event;

use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\EventDispatcher\Event;

class FormatterEvent extends Event
{
    protected $output;

    public function __construct(StreamOutput $output)
    {
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function write($messages, $newline = false)
    {
        $this->output->write($messages, $newline);
    }

    public function writeln($messages = '')
    {
        $this->write($messages, true);
    }
}

<?php

namespace Zodyac\Behat\ExtensibleHtmlFormatter\Event;

use Behat\Behat\Definition\DefinitionInterface;
use Behat\Behat\Event\EventInterface;
use Behat\Gherkin\Node\StepNode;
use Symfony\Component\Console\Output\StreamOutput;

class FormatterStepEvent extends FormatterEvent
{
    protected $step;
    protected $definition;

    public function __construct(StreamOutput $output, StepNode $step, DefinitionInterface $definition = null)
    {
        parent::__construct($output);

        $this->step = $step;
        $this->definition = $definition;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function getDefinition()
    {
        return $this->definition;
    }
}

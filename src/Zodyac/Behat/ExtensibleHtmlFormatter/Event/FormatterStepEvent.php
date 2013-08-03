<?php

namespace Zodyac\Behat\ExtensibleHtmlFormatter\Event;

use Behat\Behat\Definition\DefinitionInterface;
use Behat\Behat\Event\EventInterface;
use Behat\Gherkin\Node\StepNode;
use Symfony\Component\Console\Output\StreamOutput;

class FormatterStepEvent extends FormatterEvent
{
    protected $step;
    protected $result;
    protected $definition;
    protected $exception;

    public function __construct(StreamOutput $output, StepNode $step, $result, DefinitionInterface $definition = null, \Exception $exception = null)
    {
        parent::__construct($output);

        $this->step = $step;
        $this->result = $result;
        $this->definition = $definition;
        $this->exception = $exception;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function getException()
    {
        return $this->exception;
    }
}

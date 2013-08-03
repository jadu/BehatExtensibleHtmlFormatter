<?php

namespace Zodyac\Behat\ExtensibleHtmlFormatter\Formatter;

use Behat\Behat\DataCollector\LoggerDataCollector;
use Behat\Behat\Definition\DefinitionInterface;
use Behat\Behat\Event\StepEvent;
use Behat\Behat\Formatter\HtmlFormatter as BaseHtmlFormatter;
use Behat\Gherkin\Node\StepNode;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zodyac\Behat\ExtensibleHtmlFormatter\Event\FormatterEvent;
use Zodyac\Behat\ExtensibleHtmlFormatter\Event\FormatterStepEvent;

/**
 * Extends the base HTML formatter with points in which formatter listeners
 * can hook into and output additional information.
 */
class ExtensibleHtmlFormatter extends BaseHtmlFormatter
{
    protected $dispatcher;

    public function setDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Outputs HTML header.
     *
     * Triggers "formatter.html.head" event before outputting the </head> closing tag.
     *
     * @param LoggerDataCollector $logger
     */
    protected function printSuiteHeader(LoggerDataCollector $logger)
    {
        $this->parameters->set('decorated', false);

        $template = $this->getHtmlTemplate();

        // Split the template at the {{content}} placeholder
        $header = mb_substr($template, 0, mb_strpos($template, '{{content}}'));
        $this->footer = mb_substr($template, mb_strpos($template, '{{content}}') + 11);

        // Split the header at the </head> closing tag
        $this->writeln(mb_substr($header, 0, mb_strpos($header, '</head>')));

        $this->dispatcher->dispatch('formatter.html.head', new FormatterEvent($this->getWritingConsole()));

        $this->writeln(mb_substr($header, mb_strpos($header, '</head>')));
    }

    /**
     * Outputs the HTML footer.
     *
     * Triggers "formatter.html.footer" event before outputting the </body> closing tag.
     *
     * @param LoggerDataCollector $logger
     */
    protected function printSuiteFooter(LoggerDataCollector $logger)
    {
        $this->printSummary($logger);

        // Split the footer at the </body> closing tag
        $this->writeln(mb_substr($this->footer, 0, mb_strpos($this->footer, '</body>')));

        $this->dispatcher->dispatch('formatter.html.footer', new FormatterEvent($this->getWritingConsole()));

        $this->writeln(mb_substr($this->footer, mb_strpos($this->footer, '</body>')));
    }

    /**
     * Outputs the step result.
     *
     * Triggers "formatter.html.step" before outputting the step </li> closing tag.
     *
     * @param StepNode $step
     * @param int $result
     * @param DefinitionInterface $definition
     * @param string $snippet
     * @param Exception $exception
     */
    protected function printStep(StepNode $step, $result, DefinitionInterface $definition = null, $snippet = null, \Exception $exception = null)
    {
        $this->writeln('<li class="' . $this->getResultColorCode($result) . '">');

        $color = $this->getResultColorCode($result);

        $this->printStepBlock($step, $definition, $color);

        if ($this->parameters->get('multiline_arguments')) {
            $this->printStepArguments($step->getArguments(), $color);
        }

        if (null !== $exception && (!$exception instanceof UndefinedException || null === $snippet)) {
            $this->printStepException($exception, $color);
        }

        if (null !== $snippet && $this->getParameter('snippets')) {
            $this->printStepSnippet($snippet);
        }

        $this->dispatcher->dispatch('formatter.html.step', new FormatterStepEvent($this->getWritingConsole(), $step, $result, $definition, $exception));

        $this->writeln('</li>');
    }
}

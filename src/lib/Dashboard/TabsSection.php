<?php

namespace EzSystems\HybridPlatformUi\Dashboard;

use Symfony\Component\Templating\EngineInterface;

abstract class TabsSection implements SectionInterface
{
    /** @var EngineInterface */
    protected $templating;

    /** @var TabInterface[] */
    protected $tabs = [];

    /** @var string */
    protected $template;

    /** @var array */
    protected $parameters = [];

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param string $identifier
     *
     * @return TabInterface
     */
    public function getTab(string $identifier): TabInterface
    {
        return $this->tabs[$identifier];
    }

    /**
     * @return TabInterface[]
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * @param string $identifier
     *
     * @param TabInterface $tab
     */
    public function setTab(string $identifier, TabInterface $tab)
    {
        $this->tabs[$identifier] = $tab;
    }

    /**
     * @param TabInterface[] $tabs
     */
    public function setTabs(array $tabs)
    {
        foreach ($tabs as $identifier => $tab) {
            $this->tabs[$identifier] = $tab;
        }
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $parameter
     * @param $value
     */
    public function setParameter(string $parameter, $value)
    {
        $this->parameters[$parameter] = $value;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        foreach ($parameters as $identifier => $parameter) {
            $this->parameters[$identifier] = $parameter;
        }
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->setParameter('tabs', $this->getTabs());

        return $this->templating->render($this->template, $this->parameters);
    }
}

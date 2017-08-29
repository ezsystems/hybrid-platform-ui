<?php

namespace EzSystems\HybridPlatformUi\Dashboard;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class PaginatedTab implements TabInterface
{
    /** @var EngineInterface */
    protected $templating;

    /** @var RouterInterface */
    protected $router;

    /** @var string */
    protected $template;

    /** @var array */
    protected $parameters = [];

    /**
     * @param EngineInterface $templating
     * @param RouterInterface $router
     */
    public function __construct(EngineInterface $templating, RouterInterface $router)
    {
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @param string $parameter
     * @param mixed $value
     */
    public function setParameter(string $parameter, $value)
    {
        $this->parameters[$parameter] = $value;
    }

    public function setParameters(array $parameters)
    {
        foreach ($parameters as $identifier => $parameter) {
            $this->setParameter($identifier, $parameter);
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
     * @param string $parameter
     *
     * @return mixed
     */
    public function getParameter(string $parameter)
    {
        return $this->parameters[$parameter];
    }

    /**
     * @param int $current
     * @param int $last
     *
     * @return array
     */
    protected function getPaginatorUrls(int $current, int $last): array
    {
        $urls = [1 => $this->getPageUrl(1)];
        $urls[$last] = $this->getPageUrl($last);

        $from = max(2, $current - 2);
        $to = min($last - 1, $current + 2);

        for ($i = $from; $i <= $to; ++$i) {
            $urls[$i] = $this->getPageUrl($i);
        }

        return $urls;
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function getPageUrl(int $page): string
    {
        return $this->router->generate('ez_hybrid_platform_ui_dashboard_view_tab', [
            'sectionIdentifier' => $this->getParameter('section_identifier'),
            'tabIdentifier' => $this->getParameter('identifier'),
            'page' => $page,
        ]);
    }

    /**
     * @param int $page
     *
     * @return string
     */
    public function render($page = 1): string
    {
        return $this->templating->render($this->template, $this->parameters);
    }
}

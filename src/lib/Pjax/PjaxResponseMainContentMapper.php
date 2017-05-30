<?php

namespace EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps a PJAX Response to the MainContent. And App.
 */
class PjaxResponseMainContentMapper implements MainContentMapper
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @throws \Exception
     */
    public function map($response)
    {
        if (!$response instanceof Response) {
            throw new \InvalidArgumentException('Expected a \Symfony\Component\HttpFoundation\Response');
        }

        $responseContent = $response->getContent();

        $errorHandling = libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        if (!$doc->loadHTML($responseContent)) {
            $errors = libxml_get_errors();
            libxml_use_internal_errors($errorHandling);
            throw new \Exception(
                "Error(s) occurred parsing the PJAX response:\n" .
                implode("\n", $errors)
            );
        }

        $xpath = new \DOMXPath($doc);
        $title = $xpath->query('//div[@data-name="title"]')[0]->nodeValue;
        $content = $this->innerHtml($xpath->query('//div[@data-name="html"]')[0]);

        $this->app->setConfig([
            'title' => $title,
            'toolbars' => ['discovery' => 1],
            'mainContent' => ['result' => "<ez-serverside-view>" . $content . "</ez-serverside-view>"]
        ]);
    }

    private function innerHtml(\DOMElement $element)
    {
        $doc = $element->ownerDocument;

        $html = '';

        foreach ($element->childNodes as $node) {
            $html .= $doc->saveHTML($node);
        }

        return $html;
    }
}

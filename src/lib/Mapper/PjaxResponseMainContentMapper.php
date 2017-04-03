<?php

namespace EzSystems\HybridPlatformUi\Mapper;

use EzSystems\HybridPlatformUi\Components\MainContent;
use Symfony\Component\HttpFoundation\Response;

class PjaxResponseMainContentMapper implements MainContentMapper
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\MainContent
     */
    private $mainContent;

    public function __construct(MainContent $mainContent)
    {
        $this->mainContent = $mainContent;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \EzSystems\HybridPlatformUi\Components\MainContent
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
        $nodeList = $xpath->query('//div[@data-name="title"]');
        $title = $nodeList[0]->nodeValue;
        $contentNodeList = $xpath->query('//div[@data-name="html"]//section[contains(concat(" ", normalize-space(@class), " "), " ez-serverside-content ")]');

        $contentNode = $contentNodeList[0];
        $attributes = 'ez-view-serversideview';
        foreach (explode(' ', $contentNode->getAttribute('class')) as $classAttribute) {
            if ($classAttribute !== 'ez-view-serversideview') {
                $attributes .= " $classAttribute";
            }
        }
        $content =
            '<div class="'.$attributes.'">' .
            $this->innerHtml($contentNode) .
            '</div>';

        // @todo Should a service be used for main_content ? So that it is not necessary to carry templating around like this.
        $this->mainContent->setResult($content);
        // @todo add a Title to the mainComponent again
        // $component->setTitle($title);

        libxml_use_internal_errors($errorHandling);

        return $this->mainContent;
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

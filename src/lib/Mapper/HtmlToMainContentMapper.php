<?php

namespace EzSystems\HybridPlatformUi\Mapper;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps an HTML string to the MainContent and App components.
 */
abstract class HtmlToMainContentMapper implements MainContentMapper
{
    /**
     * Returns the xpath that extracts the body.
     *
     * @return string
     */
    abstract protected function getBodyXpath();

    /**
     * Returns the xpath that extracts the title.
     * If it returns null, the title is not extracted.
     *
     * @return string|null
     */
    protected function getTitleXpath()
    {
        return null;
    }

    /**
     * Sets the extracted body.
     *
     * @param string $body
     */
    abstract protected function setBody($body);

    /**
     * Sets the extracted title.
     * Does nothing by default, as the title is optional.
     *
     * @param string $title
     */
    protected function setTitle($title)
    {
    }

    /**
     * @param string $html
     *
     * @throws \Exception when parsing of the HTML fails.
     */
    public function map($html)
    {
        $errorHandling = libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        if (!$doc->loadHTML($html)) {
            $errors = libxml_get_errors();
            libxml_use_internal_errors($errorHandling);
            throw new \Exception(
                "Error(s) occurred parsing the HTML:\n" .
                implode("\n", $errors)
            );
        }

        $xpath = new \DOMXPath($doc);
        $bodyElements = $xpath->query($this->getBodyXpath());
        if ($bodyElements->length === 0) {
            throw new \Exception("Failed extracting the body from the HTML");
        }
        $this->setBody($this->innerHtml($bodyElements[0]));

        if ($titleXpath = $this->getTitleXpath()) {
            $this->setTitle($xpath->query($this->getTitleXpath())[0]->nodeValue);
        }
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

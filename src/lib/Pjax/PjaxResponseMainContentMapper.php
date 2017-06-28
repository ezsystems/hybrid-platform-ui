<?php

namespace EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use EzSystems\PlatformUIBundle\Notification\Notification;
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

    /**
     * A map of PlatformUIBundle's notification state to Hybrid Platform UI
     * notification type.
     *
     * @const Array
     */
    const STATE_TO_TYPE_MAP = [
        Notification::STATE_STARTED => 'processing',
        Notification::STATE_DONE => 'positive',
    ];

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
        $notificationElements = $xpath->query('//ul[@data-name="notification"]/li');

        $this->app->setConfig([
            'title' => $title,
            'toolbars' => ['discovery' => 1],
            'mainContent' => ['result' => '<ez-server-side-content>' . $content . '</ez-server--side-content>'],
            'notifications' => $this->buildNotifications($notificationElements),
        ]);
    }

    /**
     * Builds an array of notifications for Hybrid Platform UI app from PJAX
     * Notification HTML elements.
     *
     * @param \DOMNodeList $elements
     * @return array
     */
    private function buildNotifications(\DOMNodeList $elements)
    {
        $notifications = [];

        foreach ($elements as $element) {
            $state = $element->getAttribute('data-state');
            $type = isset(static::STATE_TO_TYPE_MAP[$state]) ? static::STATE_TO_TYPE_MAP[$state] : $state;
            $timeout = 10;

            if ($type === 'error') {
                $timeout = 0;
            }

            $notifications[] = [
                'type' => $type,
                'timeout' => $timeout,
                'content' => $this->innerHtml($element),
            ];
        }

        return $notifications;
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

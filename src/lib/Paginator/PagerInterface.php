<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Paginator;

use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\PagerfantaInterface;

/**
 * Create our own interface to allow de-coupling from PagerFanta.
 *
 * @TODO extending `PagerfantaInterface` would be removed once our own twig extension was created.
 */
interface PagerInterface extends \Countable, \IteratorAggregate, PagerfantaInterface
{
    /**
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setAllowOutOfRangePages($value);

    /**
     * @return bool
     */
    public function getAllowOutOfRangePages();

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setNormalizeOutOfRangePages($value);

    /**
     * @return bool
     */
    public function getNormalizeOutOfRangePages();

    /**
     * @param int $maxPerPage
     *
     * @return self
     */
    public function setMaxPerPage($maxPerPage);

    /**
     * @return bool
     */
    public function getMaxPerPage();

    /**
     * @param int $currentPage
     *
     * @return self
     */
    public function setCurrentPage($currentPage);

    /**
     * @return int
     */
    public function getCurrentPageResults();

    /**
     * @return int
     */
    public function getCurrentPageOffsetEnd();

    /**
     * @return int
     */
    public function getNbResults();

    /**
     * @return int
     */
    public function getNbPages();

    /**
     * @return bool
     */
    public function haveToPaginate();

    /**
     * @return bool
     */
    public function hasPreviousPage();

    /**
     * @return int
     */
    public function getPreviousPage();

    /**
     * @return bool
     */
    public function hasNextPage();

    /**
     * @return bool
     */
    public function getNextPage();
}

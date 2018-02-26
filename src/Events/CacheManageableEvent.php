<?php
/**
 * Author: Xavier Au
 * Date: 19/2/2018
 * Time: 3:10 PM
 */

namespace Anacreation\CMS\Events;


use Anacreation\Cms\Contracts\CacheManageableEventInterface;
use Anacreation\Cms\Contracts\CacheManageableInterface;

abstract class CacheManageableEvent
    implements CacheManageableEventInterface
{

    public $manageableObject;

    public function __construct(CacheManageableInterface $manageableObject) {
        $this->manageableObject = $manageableObject;
    }
}
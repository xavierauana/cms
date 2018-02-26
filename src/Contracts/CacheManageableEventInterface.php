<?php
/**
 * Author: Xavier Au
 * Date: 12/2/2018
 * Time: 2:07 PM
 */

namespace Anacreation\Cms\Contracts;


interface CacheManageableEventInterface
{
    public function __construct(CacheManageableInterface $cachebleObject);
}
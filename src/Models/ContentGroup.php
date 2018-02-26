<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\traits\ContentGroup as Group;
use Illuminate\Database\Eloquent\Model;

class ContentGroup extends Model implements ContentGroupInterface
{
    use Group;
}

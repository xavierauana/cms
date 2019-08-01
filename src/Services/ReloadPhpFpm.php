<?php
/**
 * Author: Xavier Au
 * Date: 2019-07-12
 * Time: 19:48
 */

namespace Anacreation\Cms\Services;


class ReloadPhpFpm
{
    public static function execute() {
        (new static)->reload();
    }

    public function reload() {
        if ($phpVersion = config('cms.reload_php_fpm', null)) {
            $command = sprintf('echo "" | sudo -S service php%s-fpm reload',
                $phpVersion);

            exec($command);
        }
    }
}
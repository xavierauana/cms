<?php
/**
 * Author: Xavier Au
 * Date: 2019-01-06
 * Time: 18:38
 */

namespace Anacreation\Cms\Services;


class ApiEncoder
{

    public function encode(array $data): string {
        return base64_encode(serialize($data));
    }

    public function decode(string $data): array {
        return unserialize(base64_decode($data));
    }
}
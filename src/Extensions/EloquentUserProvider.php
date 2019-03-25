<?php
namespace Smart\Extentions;

use Illuminate\Support\Str;
use Illuminate\Auth\EloquentUserProvider as BaseUserProvider;

class EloquentUserProvider extends BaseUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        // 用于标识是否是第一个登录字段，如果包含多个登录字段，使用 OR 查询
        $flag = false;
        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if ($flag) {
                $query->orWhere($key, $value);
            } else {
                $query->where($key, $value);
                $flag = true;
            }

        }

        return $query->first();
    }
}
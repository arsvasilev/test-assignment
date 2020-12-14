<?php

namespace app\components\platforms\api;

use Gitlab\Api;

/**
 * GitlabUsers patching usersProjects endpoint
 * 
 * @see https://github.com/KnpLabs/php-github-api/blob/master/doc/users.md#search-for-users-by-keyword
 */
class GitlabUsers extends Api\Users
{
    /**
     * @param int $id
     * @return mixed
     */
    public function usersProjects($id, array $parameters = [])
    {
        return $this->get('users/' . $this->encodePath($id) . '/projects');
    }

}

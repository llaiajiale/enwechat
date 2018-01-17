<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\Work\User;

use EasyWeChat\Kernel\BaseClient;

/**
 * Class TagClient.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class TagClient extends BaseClient
{
    /**
     * Create tag.
     *
     * @param string   $tagName
     * @param int|null $tagId
     *
     * @return mixed
     */
    public function create($tagName, $tagId = null)
    {
        $params = [
            'tagname' => $tagName,
            'tagid' => $tagId,
        ];

        return $this->httpPostJson('cgi-bin/tag/create', $params);
    }

    /**
     * Update tag.
     *
     * @param int    $tagId
     * @param string $tagName
     *
     * @return mixed
     */
    public function update($tagId, $tagName)
    {
        $params = [
            'tagid' => $tagId,
            'tagname' => $tagName,
        ];

        return $this->httpPostJson('cgi-bin/tag/update', $params);
    }

    /**
     * Delete tag.
     *
     * @param int $tagId
     *
     * @return mixed
     */
    public function delete($tagId)
    {
        return $this->httpGet('cgi-bin/tag/delete', ['tagid' => $tagId]);
    }

    /**
     * @param int $tagId
     *
     * @return mixed
     */
    public function get($tagId)
    {
        return $this->httpGet('cgi-bin/tag/get', ['tagid' => $tagId]);
    }

    /**
     * @param int   $tagId
     * @param array $userList
     *
     * @return mixed
     */
    public function tagUsers($tagId, array $userList = [])
    {
        return $this->tagOrUntagUsers('cgi-bin/tag/addtagusers', $tagId, $userList);
    }

    /**
     * @param int   $tagId
     * @param array $partyList
     *
     * @return mixed
     */
    public function tagDepartments($tagId, array $partyList = [])
    {
        return $this->tagOrUntagUsers('cgi-bin/tag/addtagusers', $tagId, [], $partyList);
    }

    /**
     * @param $tagId
     * @param array $userList
     *
     * @return mixed
     */
    public function untagUsers($tagId, array $userList = [])
    {
        return $this->tagOrUntagUsers('cgi-bin/tag/deltagusers', $tagId, $userList);
    }

    /**
     * @param $tagId
     * @param array $partyList
     *
     * @return mixed
     */
    public function untagDepartments($tagId, array $partyList = [])
    {
        return $this->tagOrUntagUsers('cgi-bin/tag/deltagusers', $tagId, [], $partyList);
    }

    /**
     * @param $endpoint
     * @param $tagId
     * @param array $userList
     * @param array $partyList
     *
     * @return mixed
     */
    protected function tagOrUntagUsers($endpoint, $tagId, array $userList = [], array $partyList = [])
    {
        $data = [
            'tagid' => $tagId,
            'userlist' => $userList,
            'partylist' => $partyList,
        ];

        return $this->httpPostJson($endpoint, $data);
    }

    /**
     * @return mixed
     */
    public function lists()
    {
        return $this->httpGet('cgi-bin/tag/list');
    }
}

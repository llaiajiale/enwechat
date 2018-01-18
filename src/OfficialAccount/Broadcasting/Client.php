<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\OfficialAccount\Broadcasting;

use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Contracts\MessageInterface;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\Card;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Media;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Support\Arr;

/**
 * Class Client.
 *
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewTextByName($text, $name);
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewNewsByName($mediaId, $name);
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewVoiceByName($mediaId, $name);
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewImageByName($mediaId, $name);
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewVideoByName($message, $name);
 * @method \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
 *         previewCardByName($cardId, $name);
 *
 * @author overtrue <i@overtrue.me>
 */
class Client extends BaseClient
{
    const PREVIEW_BY_OPENID = 'touser';
    const PREVIEW_BY_NAME = 'towxname';

    /**
     * Send a message.
     *
     * @param array $message
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function send(array $message)
    {
        if (empty($message['filter']) && empty($message['touser'])) {
            throw new RuntimeException('The message reception object is not specified');
        }

        $api = Arr::get($message, 'touser') ? 'cgi-bin/message/mass/send' : 'cgi-bin/message/mass/sendall';

        return $this->httpPostJson($api, $message);
    }

    /**
     * Preview a message.
     *
     * @param array $message
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function preview(array $message)
    {
        return $this->httpPostJson('cgi-bin/message/mass/preview', $message);
    }

    /**
     * Delete a broadcast.
     *
     * @param string $msgId
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     */
    public function delete($msgId)
    {
        $options = [
            'msg_id' => $msgId,
        ];

        return $this->httpPostJson('cgi-bin/message/mass/delete', $options);
    }

    /**
     * Get a broadcast status.
     *
     * @param string $msgId
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     */
    public function status($msgId)
    {
        $options = [
            'msg_id' => $msgId,
        ];

        return $this->httpPostJson('cgi-bin/message/mass/get', $options);
    }

    /**
     * Send a text message.
     *
     * @param string $message
     * @param mixed  $reception
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendText($message, $reception = null)
    {
        return $this->sendMessage(new Text($message), $reception);
    }

    /**
     * Send a news message.
     *
     * @param string $mediaId
     * @param mixed  $reception
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendNews($mediaId, $reception = null)
    {
        return $this->sendMessage(new Media($mediaId, 'mpnews'), $reception);
    }

    /**
     * Send a voice message.
     *
     * @param string $mediaId
     * @param mixed  $reception
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendVoice($mediaId, $reception = null)
    {
        return $this->sendMessage(new Media($mediaId, 'voice'), $reception);
    }

    /**
     * Send a image message.
     *
     * @param mixed $mediaId
     * @param mixed $reception
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendImage($mediaId, $reception = null)
    {
        return $this->sendMessage(new Image($mediaId), $reception);
    }

    /**
     * Send a video message.
     *
     * @param string $mediaId
     * @param mixed  $reception
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendVideo($mediaId, $reception = null)
    {
        return $this->sendMessage(new Media($mediaId, 'mpvideo'), $reception);
    }

    /**
     * Send a card message.
     *
     * @param string $cardId
     * @param mixed  $reception
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendCard($cardId, $reception = null)
    {
        return $this->sendMessage(new Card($cardId), $reception);
    }

    /**
     * Preview a text message.
     *
     * @param mixed  $message   message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewText($message, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Text($message), $reception, $method);
    }

    /**
     * Preview a news message.
     *
     * @param mixed  $mediaId   message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewNews($mediaId, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Media($mediaId, 'mpnews'), $reception, $method);
    }

    /**
     * Preview a voice message.
     *
     * @param mixed  $mediaId   message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewVoice($mediaId, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Media($mediaId, 'voice'), $reception, $method);
    }

    /**
     * Preview a image message.
     *
     * @param mixed  $mediaId   message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewImage($mediaId, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Image($mediaId), $reception, $method);
    }

    /**
     * Preview a video message.
     *
     * @param mixed  $mediaId   message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewVideo($mediaId, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Media($mediaId, 'mpvideo'), $reception, $method);
    }

    /**
     * Preview a card message.
     *
     * @param mixed  $cardId    message
     * @param string $reception
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewCard($cardId, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        return $this->previewMessage(new Card($cardId), $reception, $method);
    }

    /**
     * @param \EasyWeChat\Kernel\Contracts\MessageInterface $message
     * @param mixed                                         $reception
     * @param string                                        $method
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function previewMessage(MessageInterface $message, $reception, $method = self::PREVIEW_BY_OPENID)
    {
        $message = (new MessageBuilder())->message($message)->buildForPreview($method, $reception);

        return $this->preview($message);
    }

    /**
     * @param \EasyWeChat\Kernel\Contracts\MessageInterface $message
     * @param mixed                                         $reception
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendMessage(MessageInterface $message, $reception = null)
    {
        $message = (new MessageBuilder())->message($message)->toAll();

        if (\is_int($reception)) {
            $message->toTag($reception);
        } elseif (\is_array($reception)) {
            $message->toUsers($reception);
        }

        return $this->send($message->build());
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (strpos($method, 'ByName') > 0) {
            $method = strstr($method, 'ByName', true);

            if (method_exists($this, $method)) {
                array_push($args, self::PREVIEW_BY_NAME);

                return $this->$method(...$args);
            }
        }

        throw new \BadMethodCallException(sprintf('Method %s not exists.', $method));
    }
}

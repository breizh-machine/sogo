<?php

namespace Scubs\ApiBundle\Serialization\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Scubs\CoreDomain\Game\GameLogicException;

class GameLogicExceptionHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Scubs\CoreDomain\Game\GameLogicException',
                'method' => 'serializeGameLogicExceptionToJson',
            ),
        );
    }

    public function serializeGameLogicExceptionToJson(JsonSerializationVisitor $visitor, GameLogicException $exception, array $type, Context $context)
    {
        return [
            'code' => $exception->getCode(),
            'test' => 'test',
            'message' => $exception->getMessage()
        ];
    }
}

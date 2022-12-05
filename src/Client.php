<?php

namespace Coreto\CoretoRCSSDK;

use Coreto\CoretoRCSSDK\Exceptions\InvalidActionException;
use Coreto\CoretoRCSSDK\Exceptions\InvalidScoreChangeException;
use Coreto\CoretoRCSSDK\Exceptions\InvalidDataSchemaException;

class Client
{
    private $actionsWhitelist;

    public function __construct(array $actionsWhitelist) {
        if (
            !$actionsWhitelist ||
            !is_array($actionsWhitelist) ||
            count($actionsWhitelist) === 0
        ) {
            throw new \Exception('The client could not be initialized. Actions whitelist cannot be empty');
        }

        foreach ($actionsWhitelist as $key => $value) {
            if (
                !is_string($key) ||
                !(is_null($value) || is_numeric($value))
            ) {
                throw new \Exception('The client could not be initialized. Actions whitelist is not valid');
            }
        }

        $this->actionsWhitelist = $actionsWhitelist;
    }

    public function validate($actionData) {
        if (
            !(isset($actionData['action_type']) && is_string($actionData['action_type'])) ||
            !(isset($actionData['account_did']) && is_string($actionData['account_did'])) ||
            !(isset($actionData['trust']) && is_float($actionData['trust'])) ||
            !(isset($actionData['performance']) && is_float($actionData['performance'])) ||
            !(isset($actionData['action_date']) && ctype_digit($actionData['action_date']))
        ) {
            throw new InvalidDataSchemaException();
        }

        if (!array_key_exists($actionData['action_type'], $this->actionsWhitelist)) {
            throw new InvalidActionException();
        }

        $changeFluctuation = $this->actionsWhitelist[$actionData['action_type']];

        if (
            $changeFluctuation &&
            (
                $actionData['trust'] > $changeFluctuation ||
                $actionData['performance'] > $changeFluctuation
            )
        ) {
            throw new InvalidScoreChangeException();
        }

        return true;
    }
}

# Coreto RCS SDK

An SDK used for the collection and validation of the KPIs used in the Coreto DRS

## Dependencies

* PHP >=7.4

## Installation via Composer

To install simply run:

```
composer require coreto/coreto-rcs-sdk
```

Or add it to `composer.json` manually:

```json
{
    "require": {
        "coreto/coreto-rcs-sdk": "~1"
    }
}
```

## Direct usage

```php
use Coreto\CoretoRCSSDK\Client;

$reputationCollectionClient = new Client(
    [
        'react' => null, // Key-value pairs of allowed action and allowed fluctuation for that action
        'comment' => 2,
        'opinion' => 4
    ]
);

$reputationCollectionClient->validate([
    'account_did' => 'did:example:example1', // The account DID
    'action_date' => 1669121362000, // The unix timestamp in ms of the action
    'action_type' => 'react', // The action type (must be one of the allowed types)
    'trust' => 1, // The trust value (must be less than the score fluctuation if one is set)
    'performance' => 0.3, // The performance value (must be less than the score fluctuation if one is set)
    'identifier' => 123 // The identifier of the action (can be used if actions are first cached and then sent to the ledger in order to validate the sync)
]);
```

## Errors
```php
// Initialization exception
try {
$reputationCollectionClient = new Client(
    []
);
} catch (Exception $e) {
    // Action whitelist cannot be empty
}

// Score fluctuation error
$reputationCollectionClient = new Client(
    [
        'react' => 1
    ]
);

try {
    $reputationCollectionClient->validate([
        'account_did' => 'did:example:example1',
        'action_date' => 1669121362000,
        'action_type' => 'react',
        'trust' => 5,
        'performance' => 0.3,
        'identifier' => 123
    ]);
} catch (Coreto\Coreto\CoretoReputationCollectionSDK\InvalidScoreChangeException $e) {
    // Scores cannot fluctuate more than the amount set on initialization
}

// Invalid action type error
$reputationCollectionClient = new Client(
    [
        'react' => null,
        'comment' => 2,
        'opinion' => 4
    ]
);

try {
    $reputationCollectionClient->validate([
        'account_did' => 'did:example:example1',
        'action_date' => 1669121362000,
        'action_type' => 'create',
        'trust' => 0,
        'performance' => 0.3,
        'identifier' => 123
    ]);
} catch (Coreto\Coreto\CoretoReputationCollectionSDK\InvalidActionException $e) {
    // Action type is not in the whitelist
}

// Invalid action schema error
try {
    $reputationCollectionClient->validate([
        // 'account_did' => 'did:example:example1',
        'action_date' => 1669121362000,
        'action_type' => 'react',
        'trust' => 0,
        'performance' => 0.3,
        'identifier' => 123
    ]);
} catch (Coreto\Coreto\CoretoReputationCollectionSDK\InvalidActionSchemaException $e) {
    // Action type is not in the whitelist
}
```

## Todo

1. Add unit and integration tests
2. Update documentation

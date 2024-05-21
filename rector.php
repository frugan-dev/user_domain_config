<?php

declare(strict_types=1);

/*
 * This file is part of the Per-User-Domain Configuration Plugin for Roundcube.
 *
 * (ɔ) Frugan <dev@frugan.it>
 *
 * This source file is subject to the GNU GPLv3 license that is bundled
 * with this source code in the file COPYING.
 */

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__,
    ]);

    // https://getrector.com/documentation/ignoring-rules-or-paths
    $rectorConfig->skip([
        __DIR__.'/vendor',
    ]);

    // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
    // https://github.com/rectorphp/rector/tree/main/packages/Set/ValueObject
    // register a single rule
    // $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
    ]);

    // if (file_exists(__DIR__.'/vendor/thecodingmachine/safe/rector-migrate.php')) {
    //     (require __DIR__.'/vendor/thecodingmachine/safe/rector-migrate.php')($rectorConfig);
    // }
};

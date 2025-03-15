<?php

declare(strict_types=1);

use App\Consts\Role as ConstsRole;
use App\Models\Role;
use App\ValueObjects\Role\RoleID;

\describe('casts', function () {
    \it('successfully casts id', function () {
        $roleID = RoleID::parse(ConstsRole::Admin);
        $model = new Role(['id' => $roleID]);

        \expect((string) $model->id)->toBe((string) $roleID);
    });
});

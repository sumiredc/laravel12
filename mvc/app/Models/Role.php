<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Role\RoleIDCast;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

final class Role extends Model
{
    use HasUlids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => RoleIDCast::class,
            'name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

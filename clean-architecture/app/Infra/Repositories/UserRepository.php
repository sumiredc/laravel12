<?php

declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Domain\Consts\RoleID;
use App\Domain\Entities\User;
use App\Domain\Entities\UserSearchParams;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Result;
use App\Domain\ValueObjects\HashedPassword;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserID;
use App\Exceptions\InternalServerErrorException;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use InvalidArgumentException;
use Throwable;

final class UserRepository implements UserRepositoryInterface
{
    public function list(UserSearchParams $params): Result
    {
        $query = ModelsUser::query();

        if ($params->name) {
            $query->whereLike('name', "%$params->name%");
        }

        if ($params->email) {
            $query->whereLike('email', "%$params->email%");
        }

        try {
            $users = $query->offset($params->offset)->limit($params->limit)->get();

            return Result::ok($users->map(fn ($m) => $this->toDomain($m))->toArray());
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function create(User $user, Password $password): Result
    {
        $model = new ModelsUser;
        $model->id = $user->userID->value;
        $model->role_id = $user->roleID->value;
        $model->name = $user->name;
        $model->email = $user->email;
        $model->password = $password->hashed;

        try {
            $model->save();

            return Result::ok($user);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function get(UserID $userID): Result
    {
        $query = ModelsUser::query();

        try {
            $model = $query->find($userID->value);
            $user = $model ? $this->toDomain($model) : null;

            return Result::ok($user);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function getByEmail(string $email): Result
    {
        $query = ModelsUser::query();

        try {
            $model = $query->whereEmail($email)
                ->whereNotNull('email_verified_at')
                ->first();
            $user = $model ? $this->toDomain($model) : null;

            return Result::ok($user);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function getByEmailWithPassword(string $email): Result
    {
        $query = ModelsUser::query();

        try {
            $model = $query->whereEmail($email)
                ->whereNotNull('email_verified_at')
                ->first();

            $user = $model ? $this->toDomain($model) : null;
            if (is_null($user)) {
                return Result::ok([null, null]);
            }

            $password = $this->toPasswordDomain($model);

            return Result::ok([$user, $password]);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function getUnverifiedByEmail(string $email): Result
    {
        $query = ModelsUser::query();

        try {
            $model = $query->whereEmail($email)
                ->whereNull('email_verified_at')
                ->first();
            $user = $model ? $this->toDomain($model) : null;

            return Result::ok($user);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function update(User $user, ?Password $password = null): Result
    {
        $model = new ModelsUser;
        $model->id = $user->userID->value;
        $model->name = $user->name ?: $model->name;
        $model->email = $user->email ?: $model->email;
        $model->password = $password->hashed ?: $model->password;

        try {
            $model->save();

            return Result::ok($this->toDomain($model));
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function verifyEmail(User $user): Result
    {
        $model = new ModelsUser;
        $model->id = $user->userID->value;
        $model->email_verified_at = Carbon::now();

        try {
            $model->save();

            return Result::ok(null);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    public function delete(User $user): Result
    {
        $model = new ModelsUser;
        $model->id = $user->userID->value;

        try {
            $model->delete();

            return Result::ok(null);
        } catch (Throwable $th) {
            $err = new InternalServerErrorException(previous: $th);

            return Result::err($err);
        }
    }

    /** @throws InvalidArgumentException */
    private function toDomain(ModelsUser $user): User
    {
        $result = UserID::parse($user->id);

        // NOTE: NO ERROR - Refer to DB for value
        if ($result->isErr()) {
            throw $result->getError();
        }

        $userID = $result->getValue();

        return new User(
            userID: $userID,
            roleID: RoleID::from($user->role_id),
            name: $user->name,
            email: $user->email,
        );
    }

    private function toPasswordDomain(ModelsUser $user): HashedPassword
    {
        $result = HashedPassword::parse($user->password);
        if ($result->isErr()) {
            throw $result->getError();
        }

        return $result->getValue();
    }
}

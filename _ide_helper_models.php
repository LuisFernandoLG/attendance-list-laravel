<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ControlledListRecord
 *
 * @property int $id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ControlledListRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlledListRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlledListRecord query()
 */
	class ControlledListRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image_url
 * @property string $type
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EventDate
 *
 * @property int $id
 * @property string $date
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate query()
 */
	class EventDate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $name
 * @property string $custom_id
 * @property string $email
 * @property string|null $phone
 * @property string|null $image_url
 * @property int $notifyByEmail
 * @property int $notifyByPhone
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 */
	class Member extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UncontrolledListRecord
 *
 * @property int $id
 * @property string $name
 * @property string $custom_id
 * @property string $email
 * @property string|null $phone
 * @property string|null $image_url
 * @property int $notifyByEmail
 * @property int $notifyByPhone
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UncontrolledListRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UncontrolledListRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UncontrolledListRecord query()
 */
	class UncontrolledListRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $rol
 * @property string $auth_type
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}


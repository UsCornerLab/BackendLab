<?php

namespace App\Services;

use App\Models\Log;

class LogService
{
  public static function record($user, string $action, string $objectType, int $objectId, ?string $remarks = null): void
  {
    Log::create([
      'user_id'     => $user->id,
      'object_type' => $objectType,
      'object_id'   => $objectId,
      'action'      => $action,
      'remarks'     => $remarks,
      'ip_address'  => request()->ip(),
    ]);
  }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class ApiController extends Controller
{
    public function isAble(string $ability, Model|string $targetModel)
    {
        return $this->authorize($ability, $targetModel);
    }
}

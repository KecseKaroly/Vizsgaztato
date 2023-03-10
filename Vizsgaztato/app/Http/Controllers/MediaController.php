<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function storeImage()
    {
        $module = new Module();
        $module->id = 0;
        $module->exists = true;
        info($module);
        $image = $module->addMediaFromRequest('upload')->toMediaCollection('images');

        return response()->json([
            'url' => $image->getUrl(),
        ]);
    }
}

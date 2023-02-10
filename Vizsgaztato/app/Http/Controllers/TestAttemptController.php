<?php

namespace App\Http\Controllers;

use App\Models\testAttempt;
use Illuminate\Http\Request;

class TestAttemptController extends Controller
{
    public function destroy(Request $request)
    {
        $testAttempt = testAttempt::find($request->testAttemptId);
        $testAttempt->delete();
        return response()->json(['success'=>'Próbálkozás sikeresen törölve']);
    }
}

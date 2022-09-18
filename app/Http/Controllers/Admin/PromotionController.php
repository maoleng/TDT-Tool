<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function toggleActive($id): RedirectResponse
    {
        $promotion = Promotion::query()->findOrFail($id);
        if ($promotion->status === false) {
            $promotion->status = null;
        } else {
            $promotion->status = false;
        }
        $promotion->save();

        return redirect()->back();

    }
}

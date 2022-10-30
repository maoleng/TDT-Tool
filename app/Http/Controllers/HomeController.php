<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $data = $request->all();
        $q = $data['q'] ?? null;
        $from_date = isset($data['from_date']) ? Carbon::make($data['from_date']) : null;
        $to_date = isset($data['to_date']) ? Carbon::make($data['to_date']) : null;
        $in_date = isset($data['in_date']) ? Carbon::make($data['in_date']) : null;
        $department_ids = isset($data['department_ids']) ? explode(',', $data['department_ids']) : null;

        $builder = Notification::query();
        if (isset($q)) {
            $builder = $builder->where('title', 'LIKE', '%'.$q.'%')
                ->orWhere('content', 'LIKE', '%'.$q.'%')
                ->orWhere('notification_id', 'LIKE', '%'.$q.'%');
        }
        if (isset($from_date) && $to_date === null) {
            $builder = $builder->whereBetween('created_at', [$from_date, now()]);
        } elseif (isset($to_date) && $from_date === null) {
            $builder = $builder->whereBetween('created_at', [Carbon::make('01-01-2022'), $to_date]);
        } elseif (isset($from_date, $to_date)) {
            $builder = $builder->whereBetween('created_at', [$from_date, $to_date]);
        }
        if (isset($in_date)) {
            $builder = $builder->where('created_at', $in_date);
        }
        if (isset($department_ids)) {
            $builder = $builder->whereIn('department_id', $department_ids);
        }
        $notifications = $builder->with('department')
            ->orderBy('created_at', 'DESC')->paginate(7);

        return view('app.index', [
            'breadcrumb' => 'Trang chủ',
            'notifications' => $notifications,
            'departments' => (new Department)->getDepartmentByType(),
            'search_data' => [
                'q' => $q,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'in_date' => $in_date,
                'department_ids' => $department_ids,
            ],
        ]);
    }
}

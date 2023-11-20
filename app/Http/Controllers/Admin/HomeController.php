<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, User, Cart};
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class HomeController extends Controller {


    public function index() {
        //En 30 días
       /*  $totals = Order::select(DB::raw('DATE(created_at) as date, sum(grand_total) as total_amount, count(id) as total_order'))
            ->groupBy('date')
            ->having('date', '<=', date('Y-m-d'))
            ->whereRaw('DATE(created_at) >=  DATE_SUB(DATE(NOW()), INTERVAL 1 MONTH)')
            ->where('status_id', 2)
            ->get();

        $arrDays = [];
        $arrTotalsOrder = [];
        $arrTotalsAmount = [];
        $rangDays = new \DatePeriod(
            new \DateTime('-1 MONTH'),
            new \DateInterval('P1D'),
            new \DateTime('+1 day')
        );

        foreach ($rangDays as $i => $day) {
            $arrDays[$i] = $day->format('m/d');
            $temp = $totals->where('date', $day->format('Y-m-d'))->first();
            if ($temp) {
                $arrTotalsAmount[$i] = $temp->total_amount;
                $arrTotalsOrder[$i] = $temp->total_order;

            } else {
                $arrTotalsAmount[$i] = 0;
                $arrTotalsOrder[$i] = 0;
            }
        }

        $max_order = max($arrTotalsOrder);
        foreach ($arrTotalsAmount as $key => $value) {
            if ($key != 0) {
                $key_first = $key - 1;
                $arrTotalsAmount[$key] += $arrTotalsAmount[$key_first];
            }
        }

        $arrDays = '["' . implode('","', $arrDays) . '"]';
        $arrTotalsAmount = '[' . implode(',', $arrTotalsAmount) . ']';
        $arrTotalsOrder = '[' . implode(',', $arrTotalsOrder) . ']';

        $data['arrDays'] = $arrDays;
        $data['arrTotalsAmount'] = $arrTotalsAmount;
        $data['arrTotalsOrder'] = $arrTotalsOrder;
        $data['max_order'] = $max_order;
        //====Fin en 30 días====//

        //En 12 meses

        $arrTotalsAmount_year = [];

        for ($i = 12; $i >= 0; $i--) {
            $months1[$i] = date("m/Y", strtotime(date('Y-m-01') . " -$i months"));
            $months2[$i] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
            $arrTotalsAmount_year[$i] = 0;
        }

        $totals_month = Order::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym, sum(grand_total) as total_amount, count(id) as total_order'))
            ->groupBy('ym')
            ->having('ym', '>=', $months2[12])
            ->having('ym', '<=', $months2[0])
            ->get();

        foreach ($totals_month as $key => $value) {
            $key_month = array_search($value->ym, $months2);
            $arrTotalsAmount_year[$key_month] = $value->total_amount;
        }
        $months1 = '["' . implode('","', $months1) . '"]';
        $arrTotalsAmount_year = '[' . implode(',', $arrTotalsAmount_year) . ']';

        $data['months1'] = $months1;
        $data['arrTotalsAmount_year'] = $arrTotalsAmount_year;
        //====Fin en 12 meses====//

        $data['ordersCount'] = Order::count();
        $data['usersCount'] = User::count();
        $data['cartsCount'] = Cart::count();
        $data['customers'] = User::orderBy('id', 'DESC')->limit(16)->get();
        $data['orders'] = Order::with(['status', 'user'])->orderBy('created_at', 'DESC')->limit(16)->get(); */

        return view('admin.home.index'/* , $data */);
    }

}

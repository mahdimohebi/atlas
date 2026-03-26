<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\DesignPot;
use App\Models\FactoryPurchase;
use App\Models\PouringPot;
use App\Models\Saleitem;
use Illuminate\Http\Request;

class AluminumReportController extends Controller
{
    public function al_report()
    {
        // همه خریدها با category = 'soft'
        $al_report = FactoryPurchase::where('category', 'soft')->get();

        // همه ریخته‌گری‌ها
        $pouring = PouringPot::get();

        // محاسبه مجموع مقدار کلی و مصرف شده
        $total_amount = $al_report->sum('net_weight'); // مقدار کلی خرید
        $total_waste  = $al_report->sum('waste');      // مجموع ضایعات
        $total_quantity = $al_report->sum('quantity');

        // محاسبه مصرف شده از جدول PouringPot
        $total_used = $pouring->sum('total_weight');

        // باقیمانده = مقدار کلی - مصرف شده - ضایعات
        $remaining = $total_amount - $total_used;

        return view('backend.report.al_report', compact(
            'al_report',
            'pouring',
            'total_amount',
            'total_used',
            'total_waste',
            'remaining',
            'total_quantity'
        ));
    }

    public function pouring_report()
    {
        // همه رکوردها را بگیریم
        $pourings = PouringPot::all();

        // گروه‌بندی بر اساس pot_type, pot_number, pot_sub_type
        $grouped = $pourings->groupBy(function($item) {
            return $item->pot_type.'|'.$item->pot_number.'|'.$item->pot_sub_type;
        })->map(function($items, $key) {
            return [
                'pot_type'      => $items->first()->pot_type,
                'pot_number'    => $items->first()->pot_number,
                'pot_sub_type'  => $items->first()->pot_sub_type,
                'total_quantity'=> $items->sum('quantity'),
                'total_weight'  => $items->sum('total_weight'),
            ];
        });

        return view('backend.report.pouring_report', compact('grouped'));
    }


    public function design_report()
    {
        // همه رکوردها را بگیریم
        $designs = DesignPot::all();

        // گروه‌بندی بر اساس pot_type, pot_number, design_type
        $grouped = $designs->groupBy(function($item) {
            return $item->pot_type.'|'.$item->pot_number.'|'.$item->design_type;
        })->map(function($items, $key) {
            return [
                'pot_type'      => $items->first()->pot_type,
                'pot_number'    => $items->first()->pot_number,
                'design_type'   => $items->first()->design_type,
                'total_quantity'=> $items->sum('quantity'),
                'total_price'   => $items->sum('total_price'),
            ];
        });

        return view('backend.report.design_report', compact('grouped'));
    }


public function sale_report()
{
    $designs = DesignPot::all();
    $saleitems = SaleItem::all();

    // گروه‌بندی DesignPot
    $design_grouped = $designs->groupBy(function($item) {
        return strtolower(trim($item->pot_type))
             .'|'.strtolower(trim($item->pot_number))
             .'|'.strtolower(trim($item->design_type));
    })->map(function($items) {
        return [
            'pot_type' => $items->first()->pot_type,
            'pot_number' => $items->first()->pot_number,
            'design_type' => $items->first()->design_type,
            'total_quantity' => $items->sum('quantity'),
        ];
    });

    // گروه‌بندی SaleItem با کلید مشابه
    $sale_grouped = $saleitems->groupBy(function($item) {
        return strtolower(trim($item->pot_type))
             .'|'.strtolower(trim($item->pot_number))
             .'|'.strtolower(trim($item->pot_design));
    })->mapWithKeys(function($items, $key) {
        return [$key => $items->sum('quantity')];
    });

    // ادغام دو مجموعه
    $report = $design_grouped->map(function($item, $key) use ($sale_grouped) {
        $sold_quantity = $sale_grouped[$key] ?? 0;
        $remaining = $item['total_quantity'] - $sold_quantity;

        return [
            'pot_type' => $item['pot_type'],
            'pot_number' => $item['pot_number'],
            'design_type' => $item['design_type'],
            'total_quantity' => $item['total_quantity'],
            'sold_quantity' => $sold_quantity,
            'remaining' => $remaining,
        ];
    })->values()->all(); // تبدیل به آرایه برای Blade

    return view('backend.report.sale_report', compact('report'));
}


}

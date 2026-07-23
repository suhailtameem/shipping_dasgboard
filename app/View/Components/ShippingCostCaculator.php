<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\shDestinations;
use App\Models\sysLists;
use App\Models\lists;
use App\Models\currencies;

class ShippingCostCaculator extends Component
{
    public $airDests;
    public $seaDests;
    public $landDests;
    public $cargoCategories;
    public $currencies;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Load destinations by transport type
        $this->airDests = shDestinations::where('type', 1)->get();
        $this->seaDests = shDestinations::where('type', 2)->get();
        $this->landDests = shDestinations::where('type', 3)->get();

        // Load packageType list options from sysLists -> lists
        $packageTypeList = sysLists::where('name', 'packageType')->first();
        if ($packageTypeList) {
            $this->cargoCategories = lists::where('lid', $packageTypeList->id)->get();
        } else {
            $this->cargoCategories = collect();
        }

        // Load currencies from DB
        $this->currencies = currencies::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shipping-cost-caculator');
    }
}

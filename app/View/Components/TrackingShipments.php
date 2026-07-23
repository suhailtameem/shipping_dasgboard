<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\requestsControllrt;
use App\Http\Controllers\shipmentsController;

class TrackingShipments extends Component
{
    public $searchBar;
    public $result; // 0: initial, 1: result found, -1: no match, -2: not shipped yet
    public $containerInformation;
    public $shipmentMoves;
    public $shipmentMovesCounter;
    public $shippmentComplateProgress;

    /**
     * Create a new component instance.
     */
    public function __construct($tno = null)
    {
        $this->searchBar = trim($tno ?? request()->get('TNO') ?? request()->get('tno') ?? '');
        $this->result = 0;
        $this->containerInformation = [];
        $this->shipmentMoves = [];
        $this->shipmentMovesCounter = [];
        $this->shippmentComplateProgress = 0;

        if (!empty($this->searchBar)) {
            $requestData = requestsControllrt::getShipmentRequestBy($this->searchBar);
            if (count($requestData) > 0) {
                $this->result = 1;
                foreach ($requestData as $key) {
                    if ($key->shid != -1) {
                        $trackingResult = shipmentsController::trackingShipments($key->shid);
                        if (!empty($trackingResult)) {
                            $this->containerInformation = $trackingResult[0] ?? [];
                            $this->shipmentMoves = $trackingResult[1] ?? [];
                            $this->shipmentMovesCounter = $trackingResult[2] ?? [];

                            // shipment Complete Progress if auto/manual
                            foreach ($this->containerInformation as $conInfo) {
                                $pauto = is_array($conInfo) ? ($conInfo['pauto'] ?? '0') : ($conInfo->pauto ?? '0');
                                $progress = is_array($conInfo) ? ($conInfo['progress'] ?? 0) : ($conInfo->progress ?? 0);
                                if ($pauto == '1') {
                                    // case auto on
                                    foreach ($this->shipmentMovesCounter as $item) {
                                        $this->shippmentComplateProgress = is_array($item) ? ($item['parcantage'] ?? 0) : ($item->parcantage ?? 0);
                                    }
                                } else {
                                    // case auto off
                                    $this->shippmentComplateProgress = $progress;
                                }
                            }
                        }
                    } else {
                        $this->result = -2;
                    }
                }
            } else {
                $this->result = -1;
            }
        }
    }

    public function getStatusClass($statusNo)
    {
        switch ($statusNo) {
            case '0':
                return 'waiting';
            case '1':
                return 'done';
            default:
                return 'waiting';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tracking-shipments');
    }
}

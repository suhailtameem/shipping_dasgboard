<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Http\Controllers\customerController;

class AssignCustomerModal extends Component
{
    public $requestId;
    public $customers;
    public $receivers;
    public $dir;
    public $type; // sender or receiver

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($requestId, $dir, $type = 'sender', $customers = null, $receivers = null)
    {
        $this->requestId = $requestId;
        $this->dir = $dir;
        $this->type = $type;
        $this->customers = $customers ?? customerController::getAllCustomers();
        $this->receivers = $receivers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.assign-customer-modal');
    }
}

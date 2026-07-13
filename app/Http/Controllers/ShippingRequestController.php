<?php

namespace App\Http\Controllers;

use App\Models\ShippingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShippingRequestController extends Controller
{
    /**
     * Assign a customer (sender) to a shipping request.
     */
    public function assignCustomer(Request $request)
    {
        $reqId = $request->reqId;
        $cid = $request->cid;

        $update = ShippingRequest::whereId($reqId)->update([
            'cid' => $cid,
        ]);

        if ($update) {
            // Also update customer type to sender
            \App\Models\customers::whereId($cid)->update(['type' => 'sender']);
            $this->setFlash('success', 'Customer Assigned Successfully');
        } else {
            $this->setFlash('danger', 'Failed to Assign Customer');
        }
        return redirect()->back();
    }

    /**
     * Assign a receiver to a shipping request.
     */
    public function assignReceiver(Request $request)
    {
        $reqId = $request->reqId;
        $rid = $request->rid;

        $update = ShippingRequest::whereId($reqId)->update([
            'rid' => $rid,
        ]);

        if ($update) {
            // Also update customer type to receiver
            \App\Models\customers::whereId($rid)->update(['type' => 'receiver']);
            $this->setFlash('success', 'Receiver Assigned Successfully');
        } else {
            $this->setFlash('danger', 'Failed to Assign Receiver');
        }
        return redirect()->back();
    }

    /**
     * Helper to set flash messages consistent with existing logic.
     */
    private function setFlash($type, $message)
    {        
        Session::put('status', $message);
        Session::put('stype', $type);
    }
}

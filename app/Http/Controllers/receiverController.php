<?php

namespace App\Http\Controllers;

use App\Models\receiver;
use App\Models\customers;
use App\Models\ShippingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class receiverController extends Controller
{
    public static function getReceiversByCustomer($cid)
    {
        return receiver::where('cid', $cid)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cid' => 'required|exists:customers,id',
            'first' => 'nullable|string|max:255',
            'last' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'verify_id' => 'nullable|boolean',
            'prof_id_img' => 'nullable|file|image|max:5120',
        ]);

        $data = [
            'cid' => $validated['cid'],
            'first' => $validated['first'] ?? null,
            'last' => $validated['last'] ?? null,
            'full' => trim(($validated['first'] ?? '') . ' ' . ($validated['last'] ?? '')),
            'phone' => $validated['phone'] ?? null,
            'phone2' => $validated['phone2'] ?? null,
            'email' => $validated['email'] ?? null,
            'country' => $validated['country'] ?? null,
            'address' => $validated['address'] ?? null,
            'verify_id' => $request->boolean('verify_id'),
        ];

        if ($request->hasFile('prof_id_img')) {
            $data['prof_id_img'] = $request->file('prof_id_img')->store('receivers', 'public');
        }

        $receiver = receiver::create($data);

        if ($receiver) {
            $this->setFlash('success', 'Receiver created successfully');
        } else {
            $this->setFlash('danger', 'Failed to create receiver');
        }

        return redirect()->back();
    }

    public function updateReceiver(Request $request)
    {
        $validated = $request->validate([
            'rid' => 'required|exists:receivers,id',
            'first' => 'nullable|string|max:255',
            'last' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'verify_id' => 'nullable|boolean',
            'prof_id_img' => 'nullable|file|image|max:5120',
        ]);

        $data = [
            'first' => $validated['first'] ?? null,
            'last' => $validated['last'] ?? null,
            'full' => trim(($validated['first'] ?? '') . ' ' . ($validated['last'] ?? '')),
            'phone' => $validated['phone'] ?? null,
            'phone2' => $validated['phone2'] ?? null,
            'email' => $validated['email'] ?? null,
            'country' => $validated['country'] ?? null,
            'address' => $validated['address'] ?? null,
            'verify_id' => $request->boolean('verify_id'),
        ];

        if ($request->hasFile('prof_id_img')) {
            $data['prof_id_img'] = $request->file('prof_id_img')->store('receivers', 'public');
        }

        $updated = receiver::where('id', $validated['rid'])->update($data);

        if ($updated) {
            $this->setFlash('success', 'Receiver updated successfully');
        } else {
            $this->setFlash('danger', 'Failed to update receiver');
        }

        return redirect()->back();
    }

    public function assignToRequest(Request $request)
    {
        $validated = $request->validate([
            'reqId' => 'required|exists:shipping_requests,id',
            'rid' => 'required|exists:receivers,id',
        ]);

        $update = ShippingRequest::whereId($validated['reqId'])->update([
            'rid' => $validated['rid'],
        ]);

        if ($update) {
            $this->setFlash('success', 'Receiver assigned successfully');
        } else {
            $this->setFlash('danger', 'Failed to assign receiver');
        }

        return redirect()->back();
    }

    private function setFlash($type, $message)
    {
        Session::put('status', $message);
        Session::put('stype', $type);
    }
}

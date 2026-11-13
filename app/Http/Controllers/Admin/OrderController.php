<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $page = 10;
        $sort = 'asc';
        $orders = Order::whereHas('user', function (Builder $query) use ($request) {
            $query->where('role', '!=', 'suspended')
                ->when($request->q, function ($query, $q) {
                    $query->where('first_name', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%');
                });
        })
            ->with('user', 'plan')
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->orderBy('id', 'desc')
            ->paginate($page)
            ->withQueryString();

        $users = User::all();
        $plans = Plan::all();

        return Inertia::render('Admin/Order/Index', [
            'page' => $page,
            'orders' => $orders,
            'users' => $users,
            'plans' => $plans,
            'search' => $request->only('q'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'userId'  => 'required',
            'planId'  => 'required',
            'invoice_number'  => 'required',
            'amount'  => 'required',
            // 'midtrans_order_id'  => 'required',
            // 'snap_token'  => 'required',
            'payment_method'  => 'required',
            'payment_type'  => 'required',
            'status'  => 'required',
            'paid_at'  => 'required',
            // 'payload'  => 'required',
        ]);

        Order::create([
            'user_id' => auth()->id(),
            'plan_id' => $validated['planId'],
            'invoice_number' => $validated['invoice_number'], //'INV-' . Str::upper(Str::random(10)),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'midtrans_order_id' => $validated['midtrans_order_id'] ?? null,
            'snap_token' => $validated['snap_token'] ?? null,
            'payment_type' => $validated['payment_type'],
            'status' => $validated['status'] ?? null,
            'paid_at' => $validated['paid_at'],
            'payload' => $validated['payload'] ?? null,
        ]);

        return back()->with('success', 'Subscription submitted.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'userId'  => 'required',
            'planId'  => 'required',
            'invoice_number'  => 'required',
            'amount'  => 'required',
            // 'midtrans_order_id'  => 'required',
            // 'snap_token'  => 'required',
            'payment_method'  => 'required',
            'payment_type'  => 'required',
            'status'  => 'required',
            'paid_at'  => 'required',
            // 'payload'  => 'required',
        ]);

        $order = Order::findOrFail($id);

        $order->user_id = auth()->id();
        $order->plan_id = $request->planId;
        // $order->invoice_number = $request->invoice_number;
        $order->amount = $request->amount;
        $order->payment_method = $request->payment_method;
        $order->midtrans_order_id = $request->midtrans_order_id ?? null;
        $order->snap_token = $request->snap_token ?? null;
        $order->payment_type = $request->payment_type;
        $order->status = $request->status;
        $order->paid_at = $request->paid_at;
        $order->payload = $request->payload ?? null;

        $order->update();

        return redirect()->route('admin.order.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $type = Order::findOrFail($id);
        $type->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:reports,id'],
        ]);

        Order::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    public function approve(Order $order)
    {
        DB::transaction(function () use ($order) {

            $order->update([
                'status' => 'paid',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'paid_at' => now(),
            ]);

            // jika user masih punya subscribe aktif 
            // 🔍 cek subscription aktif
            $activeSub = Subscription::where('user_id', $order->user_id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->latest()
                ->first();

            if ($activeSub) {

                // 🔥 EXTEND (tambah durasi)
                $activeSub->update([
                    'end_date' => $activeSub->end_date->addDays((int) $order->plan->duration_days),
                    'plan_id' => $order->plan_id, // optional upgrade plan
                ]);
            } else {
                Subscription::create([
                    'user_id' => $order->user_id,
                    'plan_id' => $order->plan_id,
                    'order_id' => $order->id,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addDays(
                        (int) $order->plan->duration_days
                    ),
                ]);
            }
        });

        return back()->with('success', 'Subscription berhasil diaktifkan.');
    }

    public function reject(Order $order)
    {
        if ($order->status === 'paid') {
            return back()->with('error', 'Order sudah dibayar dan tidak dapat ditolak.');
        }

        DB::transaction(function () use ($order) {

            $order->update([
                'status' => 'failed', // atau 'cancelled'
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Order berhasil ditolak.'
        );
    }
}

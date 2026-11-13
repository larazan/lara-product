<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;


class SubscriptionController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $page = 10;
        $sort = 'asc';
        // $subscriptions = Subscription::whereHas('user', function (Builder $query) use($request) {
        //     $query->where('role', '!=', 'suspended')
        //     ->when($request->q, function($query, $q){
        //         $query->where('first_name', 'like', '%'.$q.'%')
        //         ->orWhere('last_name', 'like', '%'.$q.'%');
        //     });
        // })
        // ->with('user', 'plan', 'order')
        // ->paginate($page)
        // ->withQueryString();
        $subscriptions = Subscription::query()
            ->join('orders', 'orders.id', '=', 'subscriptions.order_id')
            ->whereHas('user', function (Builder $query) use ($request) {
                $query->where('role', '!=', 'suspended')
                    ->when($request->q, function ($query, $q) {
                        $query->where(function ($query) use ($q) {
                            $query->where('first_name', 'like', "%{$q}%")
                                ->orWhere('last_name', 'like', "%{$q}%");
                        });
                    });
            })
            ->with(['user', 'plan', 'order'])
            ->select('subscriptions.*')
            ->orderBy('orders.paid_at', 'desc')
            ->paginate($page)
            ->withQueryString();

        $users = User::all();
        $orders = Order::all();
        $plans = Plan::all();

        return Inertia::render('Admin/Subscription/Index', [
            'page' => $page,
            'subscriptions' => $subscriptions,
            'users' => $users,
            'orders' => $orders,
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
            'orderId'  => 'required',
            'startDate'  => 'required',
            'endDate'  => 'required',
            // 'status'  => 'required',
        ]);

        Subscription::create([
            'user_id' => $validated['userId'],
            'plan_id' => $validated['planId'],
            'order_id' => $validated['orderId'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'],
            // 'status' => $validated['status'] ?? null,
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
            'orderId'  => 'required',
            'startDate'  => 'required',
            'endDate'  => 'required',
            // 'status'  => 'required',
        ]);

        $subscription = Subscription::findOrFail($id);

        $subscription->user_id = $request->userId;
        $subscription->plan_id = $request->planId;
        $subscription->order_id = $request->orderId;
        $subscription->start_date = $request->startDate;
        $subscription->end_date = $request->endDate;
        // $subscription->status = $request->status;

        $subscription->update();

        return redirect()->route('admin.subscription.index')->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $type = Subscription::findOrFail($id);
        $type->delete();
        return redirect()->back()->with('success', 'Subscription deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:reports,id'],
        ]);

        Subscription::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    public function cancel(Request $request, Subscription $subscription)
    {
        // Pastikan subscription belum dihentikan/expired
        if ($subscription->status === 'canceled') {
            return back()->with('error', 'Subscription sudah dihentikan sebelumnya.');
        }

        $subscription->update([
            'status'      => 'canceled',
            'canceled_at' => now(),
            // Opsional: simpan alasan pembatalan atau metadata pendukung
            'meta'        => array_merge($subscription->meta ?? [], [
                'canceled_by'     => auth()->id(),
                'cancel_reason'   => $request->input('reason', 'Dihentikan oleh admin'),
                'original_end_date' => $subscription->end_date,
            ]),
        ]);

        return back()->with('success', 'Subscription berhasil dihentikan.');
    }
}

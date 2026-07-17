<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with('items')->where('user_id', auth()->id())->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function payment(\App\Models\Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('status', 'Pesanan ini sudah diproses atau tidak menunggu pembayaran.');
        }

        $bankAccounts = \App\Models\Setting::where('key', 'bank_accounts')->value('value');
        $qrisImage = \App\Models\Setting::where('key', 'qris_image')->value('value');
        $whatsappNumber = \App\Models\Setting::where('key', 'whatsapp_cs_number')->value('value');

        return view('orders.payment', compact('order', 'bankAccounts', 'qrisImage', 'whatsappNumber'));
    }
}

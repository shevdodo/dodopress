<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with('items')->where('user_id', auth()->id())->latest()->get();
        
        $bankAccounts = \App\Models\Setting::where('key', 'bank_accounts')->value('value');
        $qrisImage = \App\Models\Setting::where('key', 'qris_image')->value('value');
        $whatsappNumber = \App\Models\Setting::where('key', 'whatsapp_cs_number')->value('value');

        return view('orders.index', compact('orders', 'bankAccounts', 'qrisImage', 'whatsappNumber'));
    }
}

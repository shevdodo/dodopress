<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $apiShippingEnabled = \App\Models\Setting::where('key', 'api_shipping_enabled')->value('value') == '1';
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        $provinces = [];
        
        if ($apiShippingEnabled && !empty($apiKey)) {
            $isKomerce = !preg_match('/^[a-f0-9]{32}$/', $apiKey); // Classic RajaOngkir keys are strict 32-char hex
            $baseUrl = $isKomerce ? 'https://rajaongkir.komerce.id/api/v1' : 'https://api.rajaongkir.com/starter';
            $endpoint = $isKomerce ? '/destination/province' : '/province';

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->get($baseUrl . $endpoint);
                
                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['data'])) {
                        $provinces = array_map(function($item) {
                            return [
                                'province_id' => $item['id'] ?? $item['province_id'] ?? '',
                                'province' => $item['name'] ?? $item['province'] ?? ''
                            ];
                        }, $json['data']);
                    } else {
                        $provinces = $json['rajaongkir']['results'] ?? [];
                    }
                } else {
                    throw new \Exception('API Error: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('RajaOngkir Exception: ' . $e->getMessage());
                // Fallback Mock Data
                $provinces = [
                    ['province_id' => '9', 'province' => 'Jawa Barat'],
                    ['province_id' => '10', 'province' => 'Jawa Tengah'],
                    ['province_id' => '11', 'province' => 'Jawa Timur'],
                    ['province_id' => '6', 'province' => 'DKI Jakarta'],
                ];
            }
        }

        return view('cart.index', compact('cart', 'apiShippingEnabled', 'provinces'));
    }

    private function syncCart($cart)
    {
        session()->put('cart', $cart);
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $user->cart_data = json_encode($cart);
            $user->save();
        }
    }

    public function add(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, produk ini sedang habis stok.');
        }

        $cart = session()->get('cart', []);
        $size = $request->input('size');
        $cartKey = $size ? $id . '-' . $size : $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'size' => $size,
                'weight' => $product->weight
            ];
        }

        $this->syncCart($cart);
        return redirect()->route('cart.index')->with('status', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            $quantity = (int) $request->input('quantity');
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
            $this->syncCart($cart);
        }
        return redirect()->route('cart.index')->with('status', 'Cart updated successfully.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            $this->syncCart($cart);
        }
        return redirect()->route('cart.index')->with('status', 'Product removed from cart.');
    }

    public function getCities($provinceId)
    {
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        if (empty($apiKey)) return response()->json([]);

        $isKomerce = !preg_match('/^[a-f0-9]{32}$/', $apiKey);
        
        try {
            if ($isKomerce) {
                // Komerce API V2
                $response = \Illuminate\Support\Facades\Http::timeout(5)->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");
            } else {
                // Classic RajaOngkir
                $response = \Illuminate\Support\Facades\Http::timeout(5)->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->get('https://api.rajaongkir.com/starter/city', [
                    'province' => $provinceId
                ]);
            }
            
            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['data'])) {
                    $results = array_map(function($item) {
                        return [
                            'city_id' => $item['id'] ?? $item['city_id'] ?? '',
                            'city_name' => $item['name'] ?? $item['city_name'] ?? '',
                            'type' => $item['type'] ?? 'Kota'
                        ];
                    }, $json['data']);
                } else {
                    $results = $json['rajaongkir']['results'] ?? [];
                }
                
                if (!empty($results)) {
                    return response()->json($results);
                }
            }
            throw new \Exception('API Error or empty results: ' . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir City Exception: ' . $e->getMessage());
            // Fallback Mock Data with more cities for realism
            $mockCities = [];
            
            if ($provinceId == '9') { // Jawa Barat
                $mockCities = [
                    ['city_id' => '22', 'type' => 'Kabupaten', 'city_name' => 'Bandung'],
                    ['city_id' => '23', 'type' => 'Kota', 'city_name' => 'Bandung'],
                    ['city_id' => '54', 'type' => 'Kota', 'city_name' => 'Bekasi'],
                    ['city_id' => '78', 'type' => 'Kota', 'city_name' => 'Bogor'],
                    ['city_id' => '104', 'type' => 'Kota', 'city_name' => 'Cirebon'],
                    ['city_id' => '115', 'type' => 'Kota', 'city_name' => 'Depok'],
                ];
            } elseif ($provinceId == '10') { // Jawa Tengah
                $mockCities = [
                    ['city_id' => '39', 'type' => 'Kabupaten', 'city_name' => 'Bantul'],
                    ['city_id' => '41', 'type' => 'Kabupaten', 'city_name' => 'Banyumas'],
                    ['city_id' => '109', 'type' => 'Kabupaten', 'city_name' => 'Demak'],
                    ['city_id' => '163', 'type' => 'Kabupaten', 'city_name' => 'Karanganyar'],
                    ['city_id' => '197', 'type' => 'Kabupaten', 'city_name' => 'Kudus'],
                    ['city_id' => '252', 'type' => 'Kabupaten', 'city_name' => 'Magelang'],
                    ['city_id' => '344', 'type' => 'Kabupaten', 'city_name' => 'Pati'],
                    ['city_id' => '398', 'type' => 'Kota', 'city_name' => 'Semarang'],
                    ['city_id' => '427', 'type' => 'Kabupaten', 'city_name' => 'Sukoharjo'],
                    ['city_id' => '445', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)'],
                ];
            } elseif ($provinceId == '11') { // Jawa Timur
                $mockCities = [
                    ['city_id' => '255', 'type' => 'Kota', 'city_name' => 'Malang'],
                    ['city_id' => '444', 'type' => 'Kota', 'city_name' => 'Surabaya'],
                    ['city_id' => '425', 'type' => 'Kabupaten', 'city_name' => 'Sidoarjo'],
                    ['city_id' => '133', 'type' => 'Kabupaten', 'city_name' => 'Gresik'],
                    ['city_id' => '160', 'type' => 'Kabupaten', 'city_name' => 'Kediri'],
                ];
            } elseif ($provinceId == '6') { // DKI Jakarta
                $mockCities = [
                    ['city_id' => '151', 'type' => 'Kota', 'city_name' => 'Jakarta Barat'],
                    ['city_id' => '152', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat'],
                    ['city_id' => '153', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan'],
                    ['city_id' => '154', 'type' => 'Kota', 'city_name' => 'Jakarta Timur'],
                    ['city_id' => '155', 'type' => 'Kota', 'city_name' => 'Jakarta Utara'],
                ];
            } else {
                // Generic fallback
                $mockCities = [
                    ['city_id' => '1', 'type' => 'Kota', 'city_name' => 'Bandung'],
                    ['city_id' => '2', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)'],
                    ['city_id' => '3', 'type' => 'Kota', 'city_name' => 'Surabaya'],
                    ['city_id' => '4', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat'],
                ];
            }
            return response()->json($mockCities);
        }
    }

    public function checkOngkir(Request $request)
    {
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        if (empty($apiKey)) return response()->json(['error' => 'API Key not set'], 400);

        $request->validate([
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string|in:jne,pos,tiki'
        ]);

        try {
            // Usually the origin is the shop's location. Let's hardcode Solo/Surakarta for now or get from settings.
            // ID for Surakarta is 445 in RajaOngkir starter.
            $originId = \App\Models\Setting::where('key', 'store_city_id')->value('value') ?: 445; 

            $isKomerce = !preg_match('/^[a-f0-9]{32}$/', $apiKey);
            
            if ($isKomerce) {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->asForm()->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                    'origin' => $originId,
                    'destination' => $request->destination,
                    'weight' => $request->weight,
                    'courier' => $request->courier
                ]);
            } else {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->asForm()->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->post('https://api.rajaongkir.com/starter/cost', [
                    'origin' => $originId,
                    'destination' => $request->destination,
                    'weight' => $request->weight,
                    'courier' => $request->courier
                ]);
            }
            
            if ($response->successful()) {
                $json = $response->json();
                $costs = $json['data'][0]['costs'] ?? ($json['rajaongkir']['results'][0]['costs'] ?? []);
                if (!empty($costs)) {
                    return response()->json($costs);
                }
            }
            throw new \Exception('API Error: ' . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir Cost Exception: ' . $e->getMessage());
            
            // Fallback Mock Data for demo/sandbox
            $courier = strtoupper($request->courier);
            $mockCosts = [
                [
                    "service" => "REG",
                    "description" => "Layanan Reguler",
                    "cost" => [
                        [
                            "value" => rand(15000, 25000),
                            "etd" => "2-3",
                            "note" => ""
                        ]
                    ]
                ],
                [
                    "service" => "YES",
                    "description" => "Yakin Esok Sampai",
                    "cost" => [
                        [
                            "value" => rand(30000, 45000),
                            "etd" => "1-1",
                            "note" => ""
                        ]
                    ]
                ]
            ];
            return response()->json($mockCosts);
        }
    }
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'nullable|string',
            'destination_city' => 'nullable|string',
            'courier' => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = $request->input('shipping_cost', 0);
        $totalAmount = $subtotal + $shippingCost;

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'shipping_courier' => $request->courier,
            'shipping_service' => $request->shipping_service,
            'destination_city' => $request->destination_city,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $id => $item) {
            $productId = isset($item['product_id']) ? $item['product_id'] : (int)$id;
            $productName = $item['name'];
            if (!empty($item['size'])) {
                $productName .= ' (Size: ' . $item['size'] . ')';
            }

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);

            // Decrement stock
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Clear cart
        $this->syncCart([]);

        return redirect()->route('orders.index')->with('status', 'Order created successfully. Please complete your payment.');
    }
}

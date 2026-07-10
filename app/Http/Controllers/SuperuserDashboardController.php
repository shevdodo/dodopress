<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperuserDashboardController extends Controller
{
    /**
     * Display the superuser dashboard.
     */
    public function index()
    {
        // Fetch some basic stats for a professional dashboard experience
        $stats = [
            'total_users' => User::count(),
            'standard_users' => User::where('role', 'user')->count(),
            'superusers' => User::where('role', 'superuser')->count(),
        ];

        $users = User::latest()->take(10)->get();

        return view('dashboard.superuser', compact('stats', 'users'));
    }

    public function usersIndex()
    {
        $users = User::latest()->paginate(10);
        return view('dashboard.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('dashboard.users.create');
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,superuser',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('superuser.users.index')->with('status', 'User created successfully.');
    }

    public function usersEdit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,superuser',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('superuser.users.index')->with('status', 'User updated successfully.');
    }

    public function usersDestroy(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return redirect()->route('superuser.users.index')->withErrors(['error' => 'You cannot delete your own account.']);
        }
        $user->delete();
        return redirect()->route('superuser.users.index')->with('status', 'User deleted successfully.');
    }

    public function settingsGeneral()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.general', compact('settings'));
    }

    public function settingsGeneralUpdate(Request $request)
    {
        $data = $request->except(['_token']);
        
        // Handle checkbox which might not be in request if unchecked
        if (!isset($data['membership'])) {
            $data['membership'] = 'off';
        }

        if ($request->hasFile('fav_icon')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('fav_icon');
            $safeName = 'favicon-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['fav_icon'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('fav_icon_media_path')) {
            $data['fav_icon'] = $request->input('fav_icon_media_path');
        } else {
            unset($data['fav_icon']);
        }

        if ($request->hasFile('site_icon')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('site_icon');
            $safeName = 'site-icon-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['site_icon'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('site_icon_media_path')) {
            $data['site_icon'] = $request->input('site_icon_media_path');
        } else {
            unset($data['site_icon']);
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.general')->with('status', 'Settings saved successfully.');
    }

    public function settingsTheme()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.theme', compact('settings'));
    }

    public function settingsThemeUpdate(Request $request)
    {
        $request->validate([
            'theme_color' => 'nullable|string|max:50',
            'theme_font' => 'nullable|string|max:100',
        ]);

        $data = $request->only(['theme_color', 'theme_font']);
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.theme')->with('status', 'Theme settings saved successfully.');
    }

    public function settingsPermalink()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.permalink', compact('settings'));
    }

    public function settingsPermalinkUpdate(Request $request)
    {
        $request->validate([
            'post_permalink_base' => 'nullable|string|max:50',
            'product_permalink_base' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['post_permalink_base', 'product_permalink_base']);
        
        foreach ($data as $key => $value) {
            // Remove leading/trailing slashes
            $value = trim($value, '/');
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.permalink')->with('status', 'Permalink settings saved successfully.');
    }

    public function settingsFooter()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.footer', compact('settings'));
    }

    public function settingsFooterUpdate(Request $request)
    {
        $request->validate([
            'footer_column1_title' => 'nullable|string|max:255',
            'footer_address' => 'nullable|string',
            'footer_whatsapp' => 'nullable|string|max:50',
            'footer_map_embed' => 'nullable|string',
            'footer_copyright' => 'nullable|string',
        ]);

        $data = $request->only(['footer_column1_title', 'footer_address', 'footer_whatsapp', 'footer_map_embed', 'footer_copyright']);
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.footer')->with('status', 'Footer settings saved successfully.');
    }

    public function settingsApi()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.api', compact('settings'));
    }

    public function settingsApiUpdate(Request $request)
    {
        $request->validate([
            'api_shipping_enabled' => 'nullable|boolean',
            'api_shipping_key' => 'nullable|string',
            'api_payment_enabled' => 'nullable|boolean',
            'api_payment_server_key' => 'nullable|string',
            'api_payment_client_key' => 'nullable|string',
        ]);

        $data = [
            'api_shipping_enabled' => $request->has('api_shipping_enabled') ? '1' : '0',
            'api_shipping_key' => $request->input('api_shipping_key', ''),
            'api_payment_enabled' => $request->has('api_payment_enabled') ? '1' : '0',
            'api_payment_server_key' => $request->input('api_payment_server_key', ''),
            'api_payment_client_key' => $request->input('api_payment_client_key', ''),
        ];
        
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.api')->with('status', 'API settings saved successfully.');
    }

    public function settingsStore()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.store', compact('settings'));
    }

    public function settingsStoreUpdate(Request $request)
    {
        $request->validate([
            'store_page_title' => 'nullable|string|max:255',
            'store_catalog_title' => 'nullable|string|max:255',
            'store_page_subtitle' => 'nullable|string',
            'store_products_per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $data = $request->only(['store_page_title', 'store_catalog_title', 'store_page_subtitle', 'store_products_per_page']);

        if ($request->hasFile('store_banner_image')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('store_banner_image');
            $safeName = 'store-banner-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['store_banner_image'] = $file->storeAs($folder, $safeName, 'public');
        } else {
            $data['store_banner_image'] = $request->input('store_banner_image_media_path');
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('superuser.settings.store')->with('status', 'Store settings saved successfully.');
    }

    public function clearCache()
    {
        return view('dashboard.system.clear-cache');
    }

    public function clearCacheExecute()
    {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        return redirect()->route('superuser.clear-cache')->with('status', 'Semua cache (views, config, routes, application) berhasil dihapus!');
    }

    public function settingsSeo()
    {
        return view('dashboard.settings.seo');
    }

    public function settingsSeoUpdate(Request $request)
    {
        $data = $request->validate([
            'sitemap_enabled' => 'required|in:0,1',
            'robots_txt' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // If robots_txt was provided, let's write it to public/robots.txt
        $robotsPath = public_path('robots.txt');
        if (isset($data['robots_txt'])) {
            file_put_contents($robotsPath, $data['robots_txt']);
        }

        return back()->with('status', 'SEO settings updated successfully.');
    }

    public function settingsAiVisibility()
    {
        $robotsPath = public_path('robots.txt');
        $robotsContent = file_exists($robotsPath) ? file_get_contents($robotsPath) : '';
        
        $bots = [
            'GPTBot' => str_contains($robotsContent, "User-agent: GPTBot\nDisallow: /") ? false : true,
            'ClaudeBot' => str_contains($robotsContent, "User-agent: ClaudeBot\nDisallow: /") ? false : true,
            'PerplexityBot' => str_contains($robotsContent, "User-agent: PerplexityBot\nDisallow: /") ? false : true,
            'Applebot-Extended' => str_contains($robotsContent, "User-agent: Applebot-Extended\nDisallow: /") ? false : true,
        ];

        $sitemapEnabled = \App\Models\Setting::where('key', 'sitemap_enabled')->value('value') !== '0';
        $score = 50; // Base score (assuming Schema & Meta are implemented)
        if ($sitemapEnabled) $score += 25;
        if (file_exists($robotsPath)) $score += 25;

        return view('dashboard.settings.ai-visibility', compact('bots', 'score'));
    }

    public function settingsAiVisibilityUpdate(Request $request)
    {
        $bots = ['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Applebot-Extended'];
        $robotsPath = public_path('robots.txt');
        $robotsContent = file_exists($robotsPath) ? file_get_contents($robotsPath) : "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap_index.xml') . "\n";

        foreach ($bots as $bot) {
            $status = $request->input($bot, '0');
            $blockStr = "User-agent: {$bot}\nDisallow: /\n";
            
            if ($status == '0') { // 0 means block
                if (!str_contains($robotsContent, "User-agent: {$bot}")) {
                    $robotsContent .= "\n" . $blockStr;
                } elseif (preg_match("/User-agent: {$bot}\r?\nAllow: \/\r?\n?/", $robotsContent)) {
                    $robotsContent = preg_replace("/User-agent: {$bot}\r?\nAllow: \/\r?\n?/", $blockStr, $robotsContent);
                }
            } else { // 1 means allow
                if (str_contains($robotsContent, $blockStr)) {
                    $robotsContent = str_replace($blockStr, "", $robotsContent);
                }
            }
        }

        // Clean up empty lines
        $robotsContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $robotsContent);
        
        file_put_contents($robotsPath, trim($robotsContent) . "\n");

        return back()->with('status', 'AI Crawler preferences updated successfully.');
    }
}

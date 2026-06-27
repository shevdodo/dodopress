<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function requirements()
    {
        $requirements = [
            'PHP >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'BCMath PHP Extension' => extension_loaded('bcmath'),
            'Ctype PHP Extension' => extension_loaded('ctype'),
            'Fileinfo PHP extension' => extension_loaded('fileinfo'),
            'JSON PHP Extension' => extension_loaded('json'),
            'Mbstring PHP Extension' => extension_loaded('mbstring'),
            'OpenSSL PHP Extension' => extension_loaded('openssl'),
            'PDO PHP Extension' => extension_loaded('pdo'),
            'Tokenizer PHP Extension' => extension_loaded('tokenizer'),
            'XML PHP Extension' => extension_loaded('xml'),
        ];
        
        $allPassed = !in_array(false, $requirements);

        return view('install.requirements', compact('requirements', 'allPassed'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function processDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
        ]);

        try {
            // Test connection dynamically before writing
            $connection = @mysqli_connect(
                $request->db_host,
                $request->db_username,
                $request->db_password,
                $request->db_database,
                $request->db_port
            );

            if (!$connection) {
                return back()->with('error', 'Could not connect to the database. Please check your credentials.');
            }
            mysqli_close($connection);

            // Update .env file
            $this->setEnvValue('DB_HOST', $request->db_host);
            $this->setEnvValue('DB_PORT', $request->db_port);
            $this->setEnvValue('DB_DATABASE', $request->db_database);
            $this->setEnvValue('DB_USERNAME', $request->db_username);
            $this->setEnvValue('DB_PASSWORD', $request->db_password ?? '');
            
            // Wait a moment for .env to apply, then migrate
            Artisan::call('config:clear');
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);

            return redirect()->route('install.admin');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function processAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => 'superuser',
            ]
        );

        return redirect()->route('install.complete');
    }

    public function complete()
    {
        // Mark as installed
        File::put(storage_path('installed'), 'installed on ' . now());
        
        return view('install.complete');
    }

    private function setEnvValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = env($envKey);

        if (strpos($str, $envKey) !== false) {
            // Replace existing key
            $str = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$envValue}", $str);
        } else {
            // Append if not exists
            $str .= "\n{$envKey}={$envValue}\n";
        }

        file_put_contents($envFile, $str);
    }
}

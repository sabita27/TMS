<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class GlobalSettingController extends Controller
{
    /**
     * Allowed Keys (STRICT CONTROL)
     */
    private $allowedKeys = [
        // Identity
        'system_name',
        'system_footer',
        'home_tagline',

        // Logo
        'system_logo',
        'system_favicon',
        'system_logo_height',
        'system_logo_width',

        // SMTP
        'mail_host',
        'mail_port',
        'mail_encryption',
        'mail_username',
        'mail_password',
        'mail_from_address',
        'mail_from_name',

        // Currency
        'default_currency',
    ];

    /**
     * GET: All settings
     */
    public function index()
    {
        $settings = Setting::whereIn('key', $this->allowedKeys)
            ->pluck('value', 'key');

        return response()->json([
            'status' => true,
            'data'   => $settings
        ]);
    }

    /**
     * POST: Update settings
     */
    public function update(Request $request)
    {
        try {

            // ✅ Validation
            $validator = Validator::make($request->all(), [
                'system_name'        => 'nullable|string|max:255',
                'system_footer'      => 'nullable|string|max:255',
                'home_tagline'       => 'nullable|string|max:255',

                'mail_host'          => 'nullable|string|max:255',
                'mail_port'          => 'nullable|numeric',
                'mail_encryption'    => 'nullable|string|max:50',
                'mail_username'      => 'nullable|string|max:255',
                'mail_password'      => 'nullable|string|max:255',
                'mail_from_address'  => 'nullable|email',
                'mail_from_name'     => 'nullable|string|max:255',

                'default_currency'   => 'nullable|string|max:50',

                'system_logo_height' => 'nullable|numeric',
                'system_logo_width'  => 'nullable|numeric',

                'logo'               => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'favicon'            => 'nullable|image|mimes:png,ico|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // ✅ Save ONLY allowed keys
            foreach ($request->only($this->allowedKeys) as $key => $value) {

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            // ✅ Logo Upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('settings', 'public');

                Setting::updateOrCreate(
                    ['key' => 'system_logo'],
                    ['value' => $logoPath]
                );
            }

            // ✅ Favicon Upload
            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('settings', 'public');

                Setting::updateOrCreate(
                    ['key' => 'system_favicon'],
                    ['value' => $faviconPath]
                );
            }

            return response()->json([
                'status'  => true,
                'message' => 'Global settings updated successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET: Section-wise settings
     */
    public function getBySection($section)
    {
        $sections = [

            'identity' => [
                'system_name',
                'system_footer',
                'home_tagline'
            ],

            'logo' => [
                'system_logo',
                'system_favicon',
                'system_logo_height',
                'system_logo_width'
            ],

            'smtp' => [
                'mail_host',
                'mail_port',
                'mail_encryption',
                'mail_username',
                'mail_password',
                'mail_from_address',
                'mail_from_name'
            ],

            'currency' => [
                'default_currency'
            ],
        ];

        if (!array_key_exists($section, $sections)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid section'
            ], 400);
        }

        $data = Setting::whereIn('key', $sections[$section])
            ->pluck('value', 'key');

        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }
}
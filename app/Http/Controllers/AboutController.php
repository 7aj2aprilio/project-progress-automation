<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the About & User Guide page.
     */
    public function index()
    {
        $appInfo = [
            'name' => 'Sistem Otomasi Progres & Analisis Profitabilitas Proyek',
            'english_name' => 'Project Progress & Profitability Automation System',
            'organization' => 'Telkom Property (PT Graha Sarana Duta)',
            'version' => '1.0.0',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
        ];

        return view('about.index', compact('appInfo'));
    }
}

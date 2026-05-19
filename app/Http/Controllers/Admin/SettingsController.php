<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            // Paramètres généraux
            'library_name' => Cache::get('library_name', 'Bibliothèque Centrale'),
            'library_phone' => Cache::get('library_phone', '+253 77 00 00 00'),
            'library_email' => Cache::get('library_email', 'contact@bibliotheque.dj'),
            'library_address' => Cache::get('library_address', 'Boulevard du Général de Gaulle, Djibouti'),
            'library_logo' => Cache::get('library_logo', null),
            
            // Paramètres d'emprunt
            'fine_per_day' => Cache::get('fine_per_day', 50),
            'max_borrow_days' => Cache::get('max_borrow_days', 15),
            'max_borrow_books' => Cache::get('max_borrow_books', 5),
            'max_extend_days' => Cache::get('max_extend_days', 7),
            'max_extend_count' => Cache::get('max_extend_count', 2),
            
            // Paramètres de réservation
            'max_reservations' => Cache::get('max_reservations', 3),
            'reservation_expiry_days' => Cache::get('reservation_expiry_days', 3),
            
            // Paramètres d'amende
            'fine_threshold' => Cache::get('fine_threshold', 5000),
            'grace_period_days' => Cache::get('grace_period_days', 0),
            
            // Paramètres de notification
            'enable_notifications' => Cache::get('enable_notifications', true),
            'notify_days_before' => Cache::get('notify_days_before', 3),
            
            // Horaires d'ouverture
            'opening_hours' => Cache::get('opening_hours', json_encode([
                'monday' => ['open' => '09:00', 'close' => '18:00'],
                'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                'thursday' => ['open' => '09:00', 'close' => '18:00'],
                'friday' => ['open' => '09:00', 'close' => '17:00'],
                'saturday' => ['open' => '10:00', 'close' => '14:00'],
                'sunday' => ['open' => null, 'close' => null],
            ])),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Paramètres généraux
        $this->cacheSettings($request, [
            'library_name', 'library_phone', 'library_email', 'library_address',
            'fine_per_day', 'max_borrow_days', 'max_borrow_books', 'max_extend_days', 'max_extend_count',
            'max_reservations', 'reservation_expiry_days', 'fine_threshold', 'grace_period_days',
            'enable_notifications', 'notify_days_before'
        ]);
        
        // Validation pour les paramètres numériques
        $validated = $request->validate([
            'fine_per_day' => 'required|integer|min:10|max:500',
            'max_borrow_days' => 'required|integer|min:1|max:60',
            'max_borrow_books' => 'required|integer|min:1|max:20',
            'max_extend_days' => 'required|integer|min:1|max:30',
            'max_extend_count' => 'required|integer|min:0|max:5',
            'max_reservations' => 'required|integer|min:1|max:10',
            'reservation_expiry_days' => 'required|integer|min:1|max:7',
            'fine_threshold' => 'required|integer|min:1000|max:20000',
            'grace_period_days' => 'required|integer|min:0|max:7',
            'notify_days_before' => 'required|integer|min:0|max:7',
            'enable_notifications' => 'boolean',
        ]);
        
        // Gérer l'upload du logo
        if ($request->hasFile('library_logo')) {
            $logo = $request->file('library_logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);
            Cache::forever('library_logo', $logoName);
        }
        
        // Sauvegarder les horaires
        if ($request->has('opening_hours')) {
            $openingHours = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $openingHours[$day] = [
                    'open' => $request->input("opening_hours.{$day}.open"),
                    'close' => $request->input("opening_hours.{$day}.close")
                ];
            }
            Cache::forever('opening_hours', json_encode($openingHours));
        }
        
        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
    
    private function cacheSettings(Request $request, array $keys)
    {
        foreach ($keys as $key) {
            $value = $request->input($key);
            if ($value !== null) {
                Cache::forever($key, $value);
            }
        }
    }
    
    public function clearCache()
    {
        Cache::flush();
        return back()->with('success', 'Cache vidé avec succès.');
    }
    
    public function resetSettings()
    {
        // Supprimer tous les paramètres du cache
        $keys = [
            'library_name', 'library_phone', 'library_email', 'library_address', 'library_logo',
            'fine_per_day', 'max_borrow_days', 'max_borrow_books', 'max_extend_days', 'max_extend_count',
            'max_reservations', 'reservation_expiry_days', 'fine_threshold', 'grace_period_days',
            'enable_notifications', 'notify_days_before', 'opening_hours'
        ];
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        return redirect()->route('admin.settings')->with('success', 'Paramètres réinitialisés avec succès.');
    }
}
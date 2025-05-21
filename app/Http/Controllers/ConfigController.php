<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfigController extends Controller
{
    /**
     * Mostrar el panel de configuración general
     */
    public function index()
    {
        // En una implementación real, estas configuraciones vendrían de la base de datos
        // Para el demo, usamos datos de sesión o valores por defecto
        $amazonConfig = Session::get('amazon_config', [
            'api_key' => '',
            'api_secret' => '',
            'marketplace_id' => 'A1RKKUPIHCS9HS', // España por defecto
            'auto_sync' => false,
            'sync_interval' => 60, // minutos
            'notify_changes' => true,
        ]);
        
        $bigbuyConfig = Session::get('bigbuy_config', [
            'api_key' => '',
            'account_id' => '',
            'auto_sync' => false,
            'sync_interval' => 60, // minutos
            'notify_changes' => true,
        ]);
        
        $syncConfig = Session::get('sync_config', [
            'auto_sync_products' => false,
            'auto_sync_orders' => false,
            'sync_schedule' => 'hourly', // hourly, daily, manual
            'sync_conflicts' => 'manual', // auto_resolve, manual
            'price_variation_threshold' => 5, // porcentaje
        ]);

        $generalConfig = Session::get('general_config', [
            'company_name' => 'Mi Empresa',
            'company_email' => 'contacto@miempresa.com',
            'default_currency' => 'EUR',
            'notifications_email' => true,
            'email_digest' => 'daily', // daily, weekly, realtime
        ]);

        $marketplaces = [
            'A1RKKUPIHCS9HS' => 'Amazon España',
            'A1F83G8C2ARO7P' => 'Amazon Reino Unido',
            'A13V1IB3VIYZZH' => 'Amazon Francia',
            'A1PA6795UKMFR9' => 'Amazon Alemania',
            'APJ6JRA9NG5V4' => 'Amazon Italia',
        ];
        
        return view('config.index', compact(
            'amazonConfig', 
            'bigbuyConfig', 
            'syncConfig', 
            'generalConfig',
            'marketplaces'
        ));
    }

    /**
     * Actualizar la configuración de Amazon
     */
    public function updateAmazon(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'marketplace_id' => 'required|string',
            'auto_sync' => 'boolean',
            'sync_interval' => 'required|integer|min:15|max:1440',
            'notify_changes' => 'boolean',
        ]);

        // En una implementación real, guardaríamos esto en la base de datos
        // Para el demo, usamos datos de sesión
        Session::put('amazon_config', $validated);
        
        // En un caso real, verificaríamos las credenciales
        $connectionSuccess = $this->simulateApiConnection('amazon');

        if ($connectionSuccess) {
            return redirect()->route('config.index')
                            ->with('success', 'Configuración de Amazon actualizada correctamente.');
        } else {
            return redirect()->route('config.index')
                            ->with('error', 'Las credenciales son incorrectas o no se pudo conectar con Amazon.');
        }
    }

    /**
     * Actualizar la configuración de BigBuy
     */
    public function updateBigbuy(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'account_id' => 'required|string',
            'auto_sync' => 'boolean',
            'sync_interval' => 'required|integer|min:15|max:1440',
            'notify_changes' => 'boolean',
        ]);

        // En una implementación real, guardaríamos esto en la base de datos
        // Para el demo, usamos datos de sesión
        Session::put('bigbuy_config', $validated);
        
        // En un caso real, verificaríamos las credenciales
        $connectionSuccess = $this->simulateApiConnection('bigbuy');

        if ($connectionSuccess) {
            return redirect()->route('config.index')
                            ->with('success', 'Configuración de BigBuy actualizada correctamente.');
        } else {
            return redirect()->route('config.index')
                            ->with('error', 'Las credenciales son incorrectas o no se pudo conectar con BigBuy.');
        }
    }

    /**
     * Actualizar la configuración de sincronización
     */
    public function updateSync(Request $request)
    {
        $validated = $request->validate([
            'auto_sync_products' => 'boolean',
            'auto_sync_orders' => 'boolean',
            'sync_schedule' => 'required|in:hourly,daily,manual',
            'sync_conflicts' => 'required|in:auto_resolve,manual',
            'price_variation_threshold' => 'required|integer|min:0|max:100',
        ]);

        // En una implementación real, guardaríamos esto en la base de datos
        // Para el demo, usamos datos de sesión
        Session::put('sync_config', $validated);

        return redirect()->route('config.index')
                        ->with('success', 'Configuración de sincronización actualizada correctamente.');
    }

    /**
     * Actualizar la configuración general
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'default_currency' => 'required|string|size:3',
            'notifications_email' => 'boolean',
            'email_digest' => 'required|in:daily,weekly,realtime',
        ]);

        // En una implementación real, guardaríamos esto en la base de datos
        // Para el demo, usamos datos de sesión
        Session::put('general_config', $validated);

        return redirect()->route('config.index')
                        ->with('success', 'Configuración general actualizada correctamente.');
    }

    /**
     * Probar la conexión con una plataforma
     */
    public function testConnection(Request $request)
    {
        $platform = $request->platform;
        $success = $this->simulateApiConnection($platform);
        
        if ($success) {
            return response()->json(['status' => 'success', 'message' => 'Conexión establecida correctamente.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error al conectar. Verifica tus credenciales.']);
        }
    }

    /**
     * Lanzar sincronización manual
     */
    public function syncNow(Request $request)
    {
        $type = $request->type; // products, orders, all
        
        // En un caso real, lanzaríamos un trabajo en cola para sincronizar
        // Para el demo, simplemente simulamos el proceso
        
        switch ($type) {
            case 'products':
                $message = 'Sincronización de productos iniciada. Recibirás una notificación cuando finalice.';
                break;
            case 'orders':
                $message = 'Sincronización de pedidos iniciada. Recibirás una notificación cuando finalice.';
                break;
            default:
                $message = 'Sincronización completa iniciada. Recibirás una notificación cuando finalice.';
        }
        
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * Función para simular conexión con APIs (para demostración)
     */
    private function simulateApiConnection($platform)
    {
        // En un caso real, intentaríamos conectar con la API
        // Para el demo, simulamos una respuesta exitosa
        
        // Simulamos un 80% de éxito en las conexiones
        return (rand(1, 100) <= 80);
    }
}

<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use App\Http\Controllers\ResponseController as Response;

class NotificationController extends Controller
{

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->notificationService->getNotificationQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'notifications.id',
                    'notifications.read_at',
                    'notifications.data',
                    DB::raw("
                        CASE
                            WHEN notifiable_type = 'LowInventoryNotification' THEN 'cantidad baja'
                            WHEN notifiable_type = 'StagnantProductNotification' THEN 'producto estancado'
                            ELSE 'otro'
                        END as notifiable_type
                    "),
                    DB::raw("DATE_FORMAT(notifications.created_at, '%d-%m-%Y %H:%i:%s') as date_entry"),
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function updateStatus($id)
    {
        try {
            $data = [
                'read_at' => now()
            ];
            $notification = $this->notificationService->updateNotification($data, $id);
            return Response::sendResponse($notification, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function listUnread()
    {
        try {
            $notifications = $this->notificationService->listUnread();
            return Response::sendResponse($notifications, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}

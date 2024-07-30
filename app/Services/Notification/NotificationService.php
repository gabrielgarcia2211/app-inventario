<?php

namespace App\Services\Notification;

use Illuminate\Support\Carbon;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification\Notification;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotificationQuery()
    {
        return Notification::query()
            ->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc')
            ->distinct();
    }

    public function updateNotification(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $notification = $this->notificationRepository->update($id, $data);
            DB::commit();
            return $notification;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function listUnread()
    {
        return Notification::whereNull('notifications.read_at')->count();
    }

    /**
     * Gestionar la notificación para productos con bajo inventario.
     */
    public function handleLowInventoryNotifications()
    {
        $lowInventoryProducts = $this->getLowInventoryProducts();

        foreach ($lowInventoryProducts as $product) {
            $this->notify('LowInventoryNotification', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'total_quantity' => $product->total_quantity
            ]);
        }
    }

    /**
     * Gestionar la notificación para productos estancados.
     */
    public function handleStagnantProductNotifications()
    {
        $stagnantProducts = $this->getStagnantProducts();

        foreach ($stagnantProducts as $product) {
            $this->notify('StagnantProductNotification', [
                'product_id' => $product->id,
                'product_name' => $product->name
            ]);
        }
    }

    private function getLowInventoryProducts()
    {
        return Product::join('product_sizes', 'product_sizes.product_id', '=', 'products.id')
            ->select([
                'products.id',
                'products.name',
                'products.description',
                DB::raw('SUM(product_sizes.quantity) as total_quantity')
            ])
            ->groupBy(
                'products.id',
                'products.name',
                'products.description'
            )
            ->having(DB::raw('SUM(product_sizes.quantity)'), '<', 36)
            ->get();
    }

    private function getStagnantProducts()
    {
        $dateThreshold = Carbon::now()->subDays(15);
        return Product::whereDoesntHave('ProductOutflows', function ($query) use ($dateThreshold) {
            $query->where('created_at', '>=', $dateThreshold);
        })->get();
    }

    protected function notify($notificationType, $data)
    {
        $this->notificationRepository->create([
            'notifiable_type' => $notificationType,
            'data' => json_encode($data),
            'read_at' => null,
        ]);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductNotification\ProductNotificationService;

class CheckProductNotifications extends Command
{
    protected $signature = 'products:check-notifications';
    protected $description = 'Check for products not sold in 15 days and products with low stock';

    protected $productNotificationChecker;

    public function __construct(ProductNotificationService $productNotificationChecker)
    {
        parent::__construct();
        $this->productNotificationChecker = $productNotificationChecker;
    }

    public function handle()
    {
        $this->productNotificationChecker->checkAndStoreNotifications();
        $this->info('Product notifications check completed.');
    }
}

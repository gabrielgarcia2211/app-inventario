<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Notification\NotificationService;

class CheckProductNotifications extends Command
{
    protected $signature = 'products:check-notifications';
    protected $description = 'Check for products not sold in 15 days and products with low stock';

    protected $productNotificationChecker;

    public function __construct(NotificationService $productNotificationChecker)
    {
        parent::__construct();
        $this->productNotificationChecker = $productNotificationChecker;
    }

    public function handle()
    {
        $this->productNotificationChecker->handleLowInventoryNotifications();
        $this->productNotificationChecker->handleStagnantProductNotifications();
        $this->info('Product notifications check completed.');
    }
}

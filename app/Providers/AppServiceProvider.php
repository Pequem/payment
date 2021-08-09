<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Services\UserBalanceService;
use App\Services\TransactionService;
use App\Services\NotificationService;
use App\Services\AuthorizerTransactionService;
use App\Repositories\UserRepository;
use App\Repositories\UserBalanceRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\BaseRepository;
use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\IUserBalanceService;
use App\Interfaces\Services\ITransactionService;
use App\Interfaces\Services\INotificationService;
use App\Interfaces\Services\IAuthorizerTransactionService;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IUserBalanceRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IBaseRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);


        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(ITransactionRepository::class, TransactionRepository::class);
        $this->app->bind(IUserBalanceRepository::class, UserBalanceRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);

        $this->app->bind(IAuthorizerTransactionService::class, AuthorizerTransactionService::class);
        $this->app->bind(INotificationService::class, NotificationService::class);
        $this->app->bind(ITransactionService::class, TransactionService::class);
        $this->app->bind(IUserBalanceService::class, UserBalanceService::class);
        $this->app->bind(IUserService::class, UserService::class);
    }
}

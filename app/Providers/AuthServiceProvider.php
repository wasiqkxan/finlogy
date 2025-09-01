<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use App\Policies\AccountPolicy;
use App\Policies\BudgetPolicy;
use App\Policies\TransactionPolicy;
use App\Models\Bill;
use App\Policies\BillPolicy;
use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Account::class => AccountPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Budget::class => BudgetPolicy::class,
        Bill::class => BillPolicy::class,
        Attachment::class => AttachmentPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

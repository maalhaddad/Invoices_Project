<?php

namespace App\Observers;

use App\Models\Invoices;
use App\Models\User;
use App\Notifications\AddInvoiceNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class InvoiceObserver
{
    /**
     * Handle the Invoices "created" event.
     */
    public function created(Invoices $invoices): void
    {
        $users = User::permission('الاشعارات')->get();
       Notification::send($users, new AddInvoiceNotification($invoices , 'Add'));
    }

    /**
     * Handle the Invoices "updated" event.
     */
    public function updated(Invoices $invoices): void
    {
        $users = User::permission('الاشعارات')->get();
        Notification::send($users, new AddInvoiceNotification($invoices , 'Update'));
    }

    /**
     * Handle the Invoices "deleted" event.
     */
    public function deleted(Invoices $invoices): void
    {
        $users = User::permission('الاشعارات')->get();
        Notification::send($users, new AddInvoiceNotification($invoices , 'Delete'));
    }

    /**
     * Handle the Invoices "restored" event.
     */
    public function restored(Invoices $invoices): void
    {
        //
    }

    /**
     * Handle the Invoices "force deleted" event.
     */
    public function forceDeleted(Invoices $invoices): void
    {
        $users = User::permission('الاشعارات')->get();
       Notification::send($users, new AddInvoiceNotification($invoices , 'forceDelete'));
    }
}

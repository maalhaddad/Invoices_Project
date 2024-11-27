<?php

namespace App\Notifications;

use App\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoiceNotification extends Notification
{
    use Queueable;

     protected Invoices $invoices;
     protected $Title ;
    /**
     * Create a new notification instance.
     */
    public function __construct(Invoices $invoices , $typeNotification)
    {
        $this->invoices = $invoices;
        switch ($typeNotification) {
            case 'Add':
                $this->Title = 'تم اضافة فاتورة جديد بواسطة :';
                break;
            case 'Update': $this->Title = 'تم تعديل الفاتوره بواسطة :';
                break;
            case 'forceDelete':
                $this->Title = 'تم حذف فاتورة  بواسطة :';
                break;
            case 'Delete': $this->Title = 'تم ارشفة الفاتوره بواسطة :';
                break;
        }
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toDataBase($notifiable)
    {
        return [
            'id'=> $this->invoices->id,
            'title'=> $this->Title,
            'user'=> Auth::user()->name,
        ];
    }


}

<?php

namespace App\Notifications;

use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMemberRegistered extends Notification
{
    use Queueable;

    private User $memberUser;
    private PaymentHistory $paymentHistory;

    public function __construct(User $memberUser, PaymentHistory $paymentHistory)
    {
        $this->memberUser = $memberUser;
        $this->paymentHistory = $paymentHistory;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'format' => 'filament',
            'title'  => 'Pendaftar Baru Menunggu Konfirmasi',
            'body'   => "Member '{$this->memberUser->name}' telah mendaftar. Mohon segera konfirmasi pembayarannya.",
            'icon'   => 'heroicon-o-user-plus',
            'iconColor' => 'success',
            'actions' => [
                [
                    'name' => 'view',
                    'label' => 'Lihat Detail',
                    'url' => route('filament.admin.resources.payment-histories.edit', $this->paymentHistory),
                    'color' => 'primary',
                ],
            ],
            'payment_history_id' => $this->paymentHistory->id,
            'member_user_id'     => $this->memberUser->id,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
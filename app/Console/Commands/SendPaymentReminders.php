<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReminderMail;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    protected $signature = 'app:send-payment-reminders';
    protected $description = 'Kirim email pengingat untuk pesanan yang belum dibayar setelah batas waktu tertentu.';

    public function handle(): void
    {
        $this->info('Mencari pesanan yang belum dibayar untuk dikirim pengingat...');

        // Ambil pesanan yang statusnya 'pending_payment' ATAU payment_status 'pending'
        // dan dibuat lebih dari X jam yang lalu (misalnya 24 jam)
        // dan belum pernah dikirim pengingat (jika Anda menambahkan kolom untuk ini)
        $reminderThreshold = now()->subHours(24); 

        $pendingOrders = Order::where(function($query) {
                                $query->where('status', 'pending_payment')
                                      ->orWhere('payment_status', 'pending');
                            })
                            ->where('created_at', '<=', $reminderThreshold)
                            // Opsional: Tambahkan kolom 'payment_reminder_sent_at' (datetime, nullable)
                            // ->whereNull('payment_reminder_sent_at') 
                            ->get();

        if ($pendingOrders->isEmpty()) {
            $this->info('Tidak ada pesanan yang memerlukan pengingat saat ini.');
            return;
        }

        $this->info("Ditemukan {$pendingOrders->count()} pesanan yang belum dibayar.");

        foreach ($pendingOrders as $order) {
            try {
                Mail::to($order->customer_email)->send(new PaymentReminderMail($order));
                $this->info("Email pengingat dikirim untuk Pesanan #{$order->id} ke {$order->customer_email}");
                Log::info("Email pengingat pembayaran berhasil dikirim untuk Order ID: {$order->id}");
                
                // Opsional: Tandai bahwa pengingat sudah dikirim
                // $order->payment_reminder_sent_at = now();
                // $order->save();

            } catch (\Exception $e) {
                $this->error("Gagal mengirim email pengingat untuk Pesanan #{$order->id}: " . $e->getMessage());
                Log::error("Gagal mengirim email pengingat pembayaran untuk Order ID: {$order->id}. Error: " . $e->getMessage());
            }
        }
        $this->info('Proses pengiriman pengingat selesai.');
    }
}
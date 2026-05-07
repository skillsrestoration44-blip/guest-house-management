<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['booking_confirmed',  'Booking confirmed',          'Your booking {booking_no} for room {room_number} on {check_in_date} is confirmed.', 'system'],
            ['booking_reminder',   'Booking reminder',           'Reminder: your stay starts tomorrow at {check_in_date}.',                            'sms'],
            ['payment_received',   'Payment received',           'We have received your payment of {amount} for invoice {invoice_no}.',               'email'],
            ['debt_outstanding',   'Outstanding balance',        'Invoice {invoice_no} has an outstanding balance of {balance}.',                     'email'],
            ['housekeeping_done',  'Room ready',                 'Room {room_number} cleaning has been completed.',                                   'system'],
            ['maintenance_filed',  'Maintenance request filed',  'Maintenance request {request_no} for room {room_number} has been filed.',          'system'],
            ['stock_low',          'Low stock alert',            'Stock item {item_name} has fallen below the minimum threshold.',                   'system'],
        ];

        foreach ($templates as [$code, $title, $msg, $channel]) {
            NotificationTemplate::firstOrCreate(
                ['code' => $code],
                ['title' => $title, 'message' => $msg, 'channel' => $channel, 'status' => 'active']
            );
        }

        /* In-app notifications for the admin user */
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $samples = [
                ['system',       'Welcome', 'Welcome to your Guest House admin panel.', false],
                ['booking',      'New booking', 'A new booking BK-000003 has been confirmed.', false],
                ['payment',      'Payment received', 'Payment PAY-000004 of $42.00 received.', true],
                ['housekeeping', 'Room cleaned', 'Room 102 housekeeping task is complete.', true],
                ['maintenance',  'New maintenance request', 'Maintenance REQ-000002 reported (high priority).', false],
                ['stock',        'Low stock alert', 'Bath Towel — White is below minimum stock.', false],
            ];
            foreach ($samples as [$type, $title, $msg, $isRead]) {
                Notification::firstOrCreate(
                    ['user_id' => $admin->id, 'title' => $title],
                    [
                        'message'    => $msg,
                        'type'       => $type,
                        'channel'    => 'system',
                        'is_read'    => $isRead,
                        'read_at'    => $isRead ? now()->subHours(2) : null,
                    ]
                );
            }
        }
    }
}

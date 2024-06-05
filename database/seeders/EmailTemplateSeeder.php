<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'password_reset',
                'subject' => 'Password Reset',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>',
            ],
            [
                'name' => 'contact_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name' => 'subscribe_notification',
                'subject' => 'Subscribe Notification',
                'message' => '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>',
            ],

            [
                'name' => 'user_verification',
                'subject' => 'User Verification',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Congratulations! Your Account has been created successfully. Please Click the following link and Active your Account.</p>',
            ],

            [
                'name' => 'approved_refund',
                'subject' => 'Refund Request Approval',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We are happy to say that, we have send {{refund_amount}} USD to your provided bank information. </p>',
            ],

            [
                'name' => 'new_refund',
                'subject' => 'New Refund Request',
                'message' => '<p>Hello websolutionus, </p>

                <p>Mr. {{user_name}} has send a new refund request to you.</p>',
            ],


            [
                'name' => 'Order Successfully',
                'subject' => 'Order Successfully',
                'message' => '<p>Hi {{user_name}},</p>
                <p> Thanks for your new order. Your order has been placed .</p>
                <p>Total Amount : {{total_amount}},</p>
                <p>Payment Method : {{payment_method}},</p>
                <p>Payment Status : {{payment_status}},</p>
                <p>Order Status : {{order_status}},</p>
                <p>Order Date: {{order_date}},</p>
                <p>Order Detail: {{order_detail}}</p>
                ',

            ],
            [
                'name' => 'Order Cancel',
                'subject' => 'Order Cancel',
                'message' => '<p>Hi {{user_name}},</p>
                <p> Your order has been canceled .</p>
                <p>Total Amount : {{total_amount}},</p>
                <p>Payment Method : {{payment_method}},</p>
                <p>Payment Status : {{payment_status}},</p>
                <p>Order Status : {{order_status}},</p>
                <p>Order Date: {{order_date}},</p>',
            ],
            [
                'name' => 'Order Delivery',
                'subject' => 'Order Delivery',
                'message' => '<p>Hi {{user_name}},</p>
                <p> Your order has been delivered .</p>
                <p>Total Amount : {{total_amount}},</p>
                <p>Payment Method : {{payment_method}},</p>
                <p>Payment Status : {{payment_status}},</p>
                <p>Order Status : {{order_status}},</p>
                <p>Order Date: {{order_date}},</p>',
            ],
            [
                'name' => 'Payment Success',
                'subject' => 'Payment Success',
                'message' => '<p>Hi {{user_name}},</p>
                <p> Your payment has been successfully completed .</p>
                <p>Total Amount : {{total_amount}},</p>
                <p>Payment Method : {{payment_method}},</p>
                <p>Payment Status : {{payment_status}},</p>
                <p>Order Status : {{order_status}},</p>
                <p>Order Date: {{order_date}},</p>',
            ],

        ];

        foreach ($templates as $index => $template) {
            $new_template = new EmailTemplate();
            $new_template->name = $template['name'];
            $new_template->subject = $template['subject'];
            $new_template->description = $template['message'];
            $new_template->save();
        }
    }
}

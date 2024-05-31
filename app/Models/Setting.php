<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        "maintenance_mode",
        "logo",
        "favicon",
        "popular_category_banner",
        "featured_category_banner",
        "contact_email",
        "enable_user_register",
        "enable_multivendor",
        "enable_subscription_notify",
        "enable_save_contact_message",
        "text_direction",
        "timezone",
        "sidebar_lg_header",
        "sidebar_sm_header",
        "topbar_phone",
        "topbar_email",
        "menu_phone",
        "currency_name",
        "currency_icon",
        "currency_rate",
        "show_product_qty",
        "theme_one",
        "theme_two",
        "seller_condition",
    ];

    public function currency()
    {
        return $this->belongsTo(MultiCurrency::class);
    }
}

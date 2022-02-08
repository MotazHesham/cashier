<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'product_management_access',
            ],
            [
                'id'    => 18,
                'title' => 'product_category_create',
            ],
            [
                'id'    => 19,
                'title' => 'product_category_edit',
            ],
            [
                'id'    => 20,
                'title' => 'product_category_show',
            ],
            [
                'id'    => 21,
                'title' => 'product_category_delete',
            ],
            [
                'id'    => 22,
                'title' => 'product_category_access',
            ],
            [
                'id'    => 23,
                'title' => 'product_tag_create',
            ],
            [
                'id'    => 24,
                'title' => 'product_tag_edit',
            ],
            [
                'id'    => 25,
                'title' => 'product_tag_show',
            ],
            [
                'id'    => 26,
                'title' => 'product_tag_delete',
            ],
            [
                'id'    => 27,
                'title' => 'product_tag_access',
            ],
            [
                'id'    => 28,
                'title' => 'product_create',
            ],
            [
                'id'    => 29,
                'title' => 'product_edit',
            ],
            [
                'id'    => 30,
                'title' => 'product_show',
            ],
            [
                'id'    => 31,
                'title' => 'product_delete',
            ],
            [
                'id'    => 32,
                'title' => 'product_access',
            ],
            [
                'id'    => 33,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 34,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 35,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 36,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 37,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 38,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 39,
                'title' => 'expense_management_access',
            ],
            [
                'id'    => 40,
                'title' => 'expense_category_create',
            ],
            [
                'id'    => 41,
                'title' => 'expense_category_edit',
            ],
            [
                'id'    => 42,
                'title' => 'expense_category_show',
            ],
            [
                'id'    => 43,
                'title' => 'expense_category_delete',
            ],
            [
                'id'    => 44,
                'title' => 'expense_category_access',
            ],
            [
                'id'    => 45,
                'title' => 'income_category_create',
            ],
            [
                'id'    => 46,
                'title' => 'income_category_edit',
            ],
            [
                'id'    => 47,
                'title' => 'income_category_show',
            ],
            [
                'id'    => 48,
                'title' => 'income_category_delete',
            ],
            [
                'id'    => 49,
                'title' => 'income_category_access',
            ],
            [
                'id'    => 50,
                'title' => 'expense_create',
            ],
            [
                'id'    => 51,
                'title' => 'expense_edit',
            ],
            [
                'id'    => 52,
                'title' => 'expense_show',
            ],
            [
                'id'    => 53,
                'title' => 'expense_delete',
            ],
            [
                'id'    => 54,
                'title' => 'expense_access',
            ],
            [
                'id'    => 55,
                'title' => 'income_create',
            ],
            [
                'id'    => 56,
                'title' => 'income_edit',
            ],
            [
                'id'    => 57,
                'title' => 'income_show',
            ],
            [
                'id'    => 58,
                'title' => 'income_delete',
            ],
            [
                'id'    => 59,
                'title' => 'income_access',
            ],
            [
                'id'    => 60,
                'title' => 'expense_report_create',
            ],
            [
                'id'    => 61,
                'title' => 'expense_report_edit',
            ],
            [
                'id'    => 62,
                'title' => 'expense_report_show',
            ],
            [
                'id'    => 63,
                'title' => 'expense_report_delete',
            ],
            [
                'id'    => 64,
                'title' => 'expense_report_access',
            ],
            [
                'id'    => 65,
                'title' => 'attribute_create',
            ],
            [
                'id'    => 66,
                'title' => 'attribute_edit',
            ],
            [
                'id'    => 67,
                'title' => 'attribute_show',
            ],
            [
                'id'    => 68,
                'title' => 'attribute_delete',
            ],
            [
                'id'    => 69,
                'title' => 'attribute_access',
            ],
            [
                'id'    => 70,
                'title' => 'order_create',
            ],
            [
                'id'    => 71,
                'title' => 'order_edit',
            ],
            [
                'id'    => 72,
                'title' => 'order_show',
            ],
            [
                'id'    => 73,
                'title' => 'order_delete',
            ],
            [
                'id'    => 74,
                'title' => 'order_access',
            ],
            [
                'id'    => 75,
                'title' => 'cashier_mode_create',
            ],
            [
                'id'    => 76,
                'title' => 'cashier_mode_edit',
            ],
            [
                'id'    => 77,
                'title' => 'cashier_mode_show',
            ],
            [
                'id'    => 78,
                'title' => 'cashier_mode_delete',
            ],
            [
                'id'    => 79,
                'title' => 'cashier_mode_access',
            ],
            [
                'id'    => 80,
                'title' => 'voucher_code_create',
            ],
            [
                'id'    => 81,
                'title' => 'voucher_code_edit',
            ],
            [
                'id'    => 82,
                'title' => 'voucher_code_show',
            ],
            [
                'id'    => 83,
                'title' => 'voucher_code_delete',
            ],
            [
                'id'    => 84,
                'title' => 'voucher_code_access',
            ],
            [
                'id'    => 85,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}

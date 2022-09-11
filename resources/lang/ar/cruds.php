<?php

return [
    'userManagement' => [
        'title'          => 'إدارة المستخدمين',
        'title_singular' => 'إدارة المستخدمين',
    ],
    'permission' => [
        'title'          => 'الصلاحيات',
        'title_singular' => 'الصلاحية',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'title_ar'             => 'Title Arabic',
            'title_ar_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'المجموعات',
        'title_singular' => 'مجموعة',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'المستخدمين',
        'title_singular' => 'مستخدم',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'الأسم',
            'name_helper'              => ' ',
            'email'                    => 'البريد الألكتروني',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'كلمة المرور',
            'password_helper'          => ' ',
            'roles'                    => 'الصلاحيات',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
            'identity'                 => 'الهوية',
            'identity_helper'          => ' ',
            'phone'                    => 'رقم الهاتف',
            'phone_helper'             => ' ',
            'user_type'                => 'User Type',
            'user_type_helper'         => ' ',
        ],
    ],
    'productManagement' => [
        'title'          => 'أدارة المنتجات',
        'title_singular' => 'أدارة المنتجات',
    ],
    'productCategory' => [
        'title'          => 'الفئات',
        'title_singular' => 'فئة',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'name'               => 'أسم الفئة',
            'name_helper'        => ' ',
            'description'        => 'الوصف',
            'description_helper' => ' ',
            'photo'              => 'الصورة',
            'photo_helper'       => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated At',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted At',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'productTag' => [
        'title'          => 'Tags',
        'title_singular' => 'Tag',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
        ],
    ],
    'product' => [
        'title'          => 'المنتجات',
        'title_singular' => 'منتج',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'name'               => 'أسم ألمنتج',
            'name_helper'        => ' ',
            'description'        => 'الوصف',
            'description_helper' => ' ',
            'price'              => 'السعر',
            'price_helper'       => ' ',
            'photo'              => 'الصورة',
            'photo_helper'       => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated At',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted At',
            'deleted_at_helper'  => ' ',
            'category'           => 'الفئة',
            'category_helper'    => ' ',
            'attributes'         => 'السمات',
            'attributes_helper'  => ' ',
            'status'             => 'الحالة',
            'status_helper'      => ' ',
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'userAlert' => [
        'title'          => 'أشعارات المستخدمين',
        'title_singular' => 'أشعار المسخدم',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'alert_text'        => 'الأشعار',
            'alert_text_helper' => ' ',
            'alert_link'        => 'اللينك عند الضفط',
            'alert_link_helper' => ' ',
            'user'              => 'المستخدمين',
            'user_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
        ],
    ],
    'expenseManagement' => [
        'title'          => 'المصاريف',
        'title_singular' => 'المصاريف',
    ],
    'expenseCategory' => [
        'title'          => 'تصنيف النفقات',
        'title_singular' => 'تصنيف المصاريف',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'أسم التصنيف',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
        ],
    ],
    'incomeCategory' => [
        'title'          => 'تصنيفات الإيراد',
        'title_singular' => 'الإيراد حسب التصنيف',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'أسم التصنيف',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
        ],
    ],
    'expense' => [
        'title'          => 'المصروفات',
        'title_singular' => 'المصروف',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'expense_category'        => 'التصنيف',
            'expense_category_helper' => ' ',
            'entry_date'              => 'الوقت',
            'entry_date_helper'       => ' ',
            'amount'                  => 'المبلغ',
            'amount_helper'           => ' ',
            'description'             => 'الوصف',
            'description_helper'      => ' ',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated At',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted At',
            'deleted_at_helper'       => ' ',
            'photo'                   => 'صورة الفاتورة',
            'photo_helper'            => ' ',
        ],
    ],
    'income' => [
        'title'          => 'الإيرادات',
        'title_singular' => 'الإيرادات',
        'fields'         => [
            'id'                     => 'ID',
            'id_helper'              => ' ',
            'income_category'        => 'التصنيف',
            'income_category_helper' => ' ',
            'entry_date'             => 'الوقت',
            'entry_date_helper'      => ' ',
            'amount'                 => 'المبلغ',
            'amount_helper'          => ' ',
            'description'            => 'الوصف',
            'description_helper'     => ' ',
            'created_at'             => 'Created at',
            'created_at_helper'      => ' ',
            'updated_at'             => 'Updated At',
            'updated_at_helper'      => ' ',
            'deleted_at'             => 'Deleted At',
            'deleted_at_helper'      => ' ',
            'photo'                  => 'صورة الفاتورة',
            'photo_helper'           => ' ',
            'relationid'             => 'Relationid',
            'relationid_helper'      => ' ',
        ],
    ],
    'expenseReport' => [
        'title'          => 'تقرير شهري',
        'title_singular' => 'تقرير شهري',
        'reports'        => [
            'title'             => 'التقارير',
            'title_singular'    => 'تقرير',
            'incomeReport'      => 'تقرير الإيرادات',
            'incomeByCategory'  => 'الإيراد حسب التصنيف',
            'expenseByCategory' => 'المصروف حسب التصنيف',
            'income'            => 'الإيرادات',
            'expense'           => 'المصروف',
            'profit'            => 'ربح',
        ],
    ],
    'attribute' => [
        'title'          => 'السمات',
        'title_singular' => 'سمة',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'attribute'         => 'أسم السمة',
            'attribute_helper'  => 'مثال (size , extra , sugar , etc... )',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'slug'              => 'Slug',
            'slug_helper'       => ' ',
            'type'              => 'Type',
            'type_helper'       => ' ',
        ],
    ],
    'order' => [
        'title'          => 'الأوردرات',
        'title_singular' => 'أوردر',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'code'                => 'كود الطلب',
            'code_helper'         => ' ',
            'products'            => 'المنتجات',
            'products_helper'     => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
            'created_by'          => 'بواسطة',
            'created_by_helper'   => ' ',
            'voucher_code'        => 'كود الخصم',
            'voucher_code_helper' => ' ',
            'paid_up'             => 'تم دفع',
            'paid_up_helper'      => ' ',
            'total_cost'          => 'الأجمالي',
            'total_cost_helper'   => ' ',
        ],
    ],
    'cashierMode' => [
        'title'          => 'الكاشير',
        'title_singular' => 'الكاشير',
    ],
    'voucherCode' => [
        'title'          => 'أكواد الخصم',
        'title_singular' => 'كود الخصم',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'code'               => 'الكود',
            'code_helper'        => ' ',
            'discount'               => 'الخصم',
            'discount_helper'        => '',
            'start_date'         => 'تاريخ البداية',
            'start_date_helper'  => ' ',
            'end_date'           => 'تاريخ النهاية',
            'end_date_helper'    => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'description'        => 'الوصف',
            'description_helper' => ' ',
            'type'               => 'النوع',
            'type_helper'        => 'بالجنيه / بالنسبة المؤية',
        ],
    ],
    'generalSetting' => [
        'title'          => 'الأعدادات العامة',
        'title_singular' => 'الأعدادات العامة',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
            'income_orders'        => 'Income Orders',
            'income_orders_helper' => ' ',
            'website_title'        => 'Website Title',
            'website_title_helper' => ' ',
            'phone_1'              => 'Phone 1',
            'phone_1_helper'       => ' ',
            'phone_2'              => 'Phone 2',
            'phone_2_helper'       => ' ',
            'address'              => 'Address',
            'address_helper'       => ' ',
            'logo'                 => 'Logo',
            'logo_helper'          => ' ',
        ],
    ],
    'schoolsManagment' => [
        'title'          => 'أدارة المدارس',
        'title_singular' => 'أدارة المدارس',
    ],
    'school' => [
        'title'          => 'المدارس',
        'title_singular' => 'مدرسة',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'school_name'              => 'أسم المدرسة',
            'school_name_helper'       => ' ',
            'address'           => 'العنوان',
            'address_helper'    => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'father' => [
        'title'          => 'الأباء',
        'title_singular' => 'أب',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'note'              => 'Note',
            'note_helper'       => ' ',
        ],
    ],
    'student' => [
        'title'          => 'الطلاب',
        'title_singular' => 'طالب',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'father'            => 'الأب',
            'father_helper'     => ' ',
        ],
    ],
    'payment' => [
        'title'          => 'التحويلات',
        'title_singular' => 'تحويلة',
        'fields'         => [
            'id'                    => 'ID',
            'id_helper'             => ' ',
            'completed'             => 'Completed',
            'completed_helper'      => ' ',
            'payment_order'         => 'Payment Order',
            'payment_order_helper'  => ' ',
            'payment_type'          => 'نوع التحويل',
            'payment_type_helper'   => ' ',
            'payment_status'        => 'حالة التحويل',
            'payment_status_helper' => ' ',
            'amount'                => 'المبلغ',
            'amount_helper'         => ' ',
            'user'                  => 'المستخدم',
            'user_helper'           => ' ',
            'created_at'            => 'Created at',
            'created_at_helper'     => ' ',
            'updated_at'            => 'Updated at',
            'updated_at_helper'     => ' ',
            'deleted_at'            => 'Deleted at',
            'deleted_at_helper'     => ' ',
        ],
    ],
];

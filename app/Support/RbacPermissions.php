<?php

namespace App\Support;

class RbacPermissions
{
    private static function crudPermissions($prefix, $label)
    {
        return [
            $prefix . '.view' => 'Xem ' . $label,
            $prefix . '.create' => 'Thêm ' . $label,
            $prefix . '.update' => 'Sửa ' . $label,
            $prefix . '.delete' => 'Xóa ' . $label,
            $prefix . '.manage' => 'Quản lý ' . $label,
        ];
    }

    public static function all()
    {
        return array_merge([
            'dashboard.view' => 'Xem dashboard',
        ],
        self::crudPermissions('rbac', 'role/permission'),
        self::crudPermissions('menu', 'menu'),
        self::crudPermissions('language', 'ngôn ngữ'),
        self::crudPermissions('bill', 'đơn hàng'),
        self::crudPermissions('product', 'sản phẩm'),
        self::crudPermissions('blog', 'bài viết'),
        self::crudPermissions('service', 'dịch vụ'),
        self::crudPermissions('project', 'dự án'),
        self::crudPermissions('solution', 'giải pháp'),
        self::crudPermissions('pagecontent', 'trang nội dung'),
        self::crudPermissions('website', 'cấu hình website'),
        self::crudPermissions('customer', 'khách hàng'),
        self::crudPermissions('promotion', 'khuyến mãi'),
        self::crudPermissions('variant', 'biến thể'),
        self::crudPermissions('tag', 'tag'),
        self::crudPermissions('bannerads', 'banner/quảng cáo'),
        self::crudPermissions('review', 'review'),
        self::crudPermissions('construction', 'công trình'),
        self::crudPermissions('message', 'tin nhắn liên hệ'),
        self::crudPermissions('library', 'thư viện media'));
    }
}

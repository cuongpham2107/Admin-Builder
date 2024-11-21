<?php
namespace CuongPham2107\AdminBuilder\Extensions;

class NotificationHelper {
    public static function getMessage(string $title, string $body, bool $status): array {
        return [
            'title' => $title,
            'body' => $body,
            'status' => $status
        ];
    }
}
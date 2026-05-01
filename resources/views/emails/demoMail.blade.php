@php
    $order = $data['cus'] ?? null;
    $items = collect($data['bill'] ?? [])->map(function ($item) {
        $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
        $price = (int) ($item['price'] ?? 0);
        return [
            'name' => $item['name'] ?? '',
            'variant' => $item['variant'] ?? '',
            'quantity' => $quantity,
            'price' => $price,
            'line_total' => $quantity * $price,
        ];
    });

    $computedSubtotal = $items->sum('line_total');
    $shipping = (int) ($order->transport_price ?? 0);
    $total = (int) ($order->total_money ?? ($computedSubtotal + $shipping));
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thong bao don hang moi</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f7fb;padding:20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="760" cellpadding="0" cellspacing="0" border="0" style="max-width:760px;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;">
                    <tr>
                        <td style="padding:20px 24px;border-bottom:1px solid #e5e7eb;">
                            <h2 style="margin:0;font-size:24px;color:#111827;">Ban co don hang moi</h2>
                            <p style="margin:8px 0 0;font-size:14px;color:#6b7280;">
                                Ma don: <strong>#{{ $order->code_bill ?? 'N/A' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 24px;">
                            <h3 style="margin:0 0 12px;font-size:18px;color:#111827;">Thong tin khach hang</h3>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;line-height:1.7;">
                                <tr>
                                    <td style="width:180px;color:#6b7280;">Ho va ten:</td>
                                    <td><strong>{{ $order->cus_name ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">So dien thoai:</td>
                                    <td>{{ $order->cus_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Email:</td>
                                    <td>{{ $order->cus_email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Dia chi:</td>
                                    <td>{{ $order->cus_address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Phuong thuc thanh toan:</td>
                                    <td>
                                        @if (($order->payment_method ?? 'cod') === 'online')
                                            Thanh toan online
                                        @else
                                            Thanh toan khi nhan hang (COD)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Ghi chu:</td>
                                    <td>{{ $order->note ?: 'Khong co' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 24px 20px;">
                            <h3 style="margin:0 0 12px;font-size:18px;color:#111827;">Chi tiet san pham</h3>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-size:14px;">
                                <thead>
                                    <tr style="background:#f3f4f6;">
                                        <th align="left" style="padding:10px;border:1px solid #e5e7eb;">San pham</th>
                                        <th align="left" style="padding:10px;border:1px solid #e5e7eb;">Phien ban</th>
                                        <th align="right" style="padding:10px;border:1px solid #e5e7eb;">So luong</th>
                                        <th align="right" style="padding:10px;border:1px solid #e5e7eb;">Don gia</th>
                                        <th align="right" style="padding:10px;border:1px solid #e5e7eb;">Thanh tien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td style="padding:10px;border:1px solid #e5e7eb;">{{ $item['name'] ?: 'N/A' }}</td>
                                            <td style="padding:10px;border:1px solid #e5e7eb;">{{ $item['variant'] ?: '-' }}</td>
                                            <td align="right" style="padding:10px;border:1px solid #e5e7eb;">{{ $item['quantity'] }}</td>
                                            <td align="right" style="padding:10px;border:1px solid #e5e7eb;">{{ number_format($item['price']) }}đ</td>
                                            <td align="right" style="padding:10px;border:1px solid #e5e7eb;">{{ number_format($item['line_total']) }}đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" align="center" style="padding:14px;border:1px solid #e5e7eb;color:#6b7280;">
                                                Khong co du lieu san pham.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 24px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;line-height:1.9;">
                                <tr>
                                    <td align="right" style="color:#6b7280;">Tam tinh:</td>
                                    <td align="right" style="width:160px;">{{ number_format($computedSubtotal) }}đ</td>
                                </tr>
                                <tr>
                                    <td align="right" style="color:#6b7280;">Phi van chuyen:</td>
                                    <td align="right">{{ number_format($shipping) }}đ</td>
                                </tr>
                                <tr>
                                    <td align="right" style="font-size:16px;"><strong>Tong thanh toan:</strong></td>
                                    <td align="right" style="font-size:16px;color:#dc2626;"><strong>{{ number_format($total) }}đ</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 24px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;text-align:center;">
                            Email nay duoc gui tu he thong quan ly don hang.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

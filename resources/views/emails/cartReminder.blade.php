<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكير: لديك عناصر في سلة التسوق الخاصة بك!</title>
</head>
<body style="direction: rtl;">
    <h1>تذكير: لديك عناصر في سلة التسوق الخاصة بك!</h1>

    <p>عزيزي {{ $cartDetails['customer_name'] }},</p>

    <p>يبدو أنك تركت بعض العناصر في سلة التسوق الخاصة بك:</p>

    <ul>
        <li><strong>اسم المنتج:</strong> {{ $cartDetails['product_name'] }}</li>
        <li><strong>السعر:</strong>  {{ $cartDetails['product_price'] }} ريال سعودي</li>
        <li><strong>الكمية:</strong> {{ $cartDetails['product_quantity'] }}</li>
        <li><strong>الإجمالي:</strong> {{ $cartDetails['total'] }} ريال سعودي</li>
    </ul>

    <p>لا تنس إكمال عملية الشراء!</p>

    <p><a href="{{ url('/') }}">قم بزيارة متجرنا لإتمام طلبك</a>.</p>

    <p>شكرًا لك!</p>
</body>
</html>

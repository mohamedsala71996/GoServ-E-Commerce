<!DOCTYPE html>
<html>
<head>
    <title>Product Available</title>
</head>
<body>
    <h1>{{ $product->getTranslation('name', app()->getLocale()) }} is now available!</h1>
    <p>Dear customer,</p>
    <p>We are happy to inform you that the product you were waiting for, {{ $product->getTranslation('name', app()->getLocale()) }}, is now back in stock.</p>
    <p><a href="{{ url('/api/website/products/' . $product->id) }}">Click here</a> to purchase it now before it runs out again!</p>
    <p>Thank you for shopping with us!</p>
</body>
</html>

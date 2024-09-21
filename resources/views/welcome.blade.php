<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <div class="container py-5 text-center">
        <div class="container py-5 text-center">
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                @method('POST')
                    <button class="btn btn-lg text-light bg-dark text-center"
                        type="submit" >Confirm Online Order
                    </button>
            </form>
            </div>
        </div>
</body>
</html>

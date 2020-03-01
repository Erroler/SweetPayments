<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paypal Checkout</title>
</head>

<body>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" id="form">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="/* INSERT YOUR BUISSNESS ID */">
        <input type="hidden" name="lc" value="PT">
        <input type="hidden" name="item_name" value="{{ $subscription->name }}">
        <input type="hidden" name="item_number" value="{!! $sale_id !!}">
        <input type="hidden" name="amount" value="{{ number_format($subscription->pricing, 2) }}">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="button_subtype" value="services">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="rm" value="1">
        <input type="hidden" name="return" value="{{ route('webshop.success', ['webshop_name' => $community->small_name]) }}">
        <input type="hidden" name="cancel_return" value="{{ route('webshop.index', ['webshop_name' => $community->small_name ]) }}">
        <input type="hidden" name="notify_url" value="{{ route('webshop.paypal_ipn', ['sale' => $sale_id ]) }}"> 
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
    </form>
    <script>
        document.getElementById('form').submit();
    </script>

</body>

</html>
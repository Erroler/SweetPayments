<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Log;
use Mdb\PayPal\Ipn\Event\MessageInvalidEvent;
use Mdb\PayPal\Ipn\Event\MessageVerifiedEvent;
use Mdb\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use Mdb\PayPal\Ipn\ListenerBuilder\Guzzle\InputStreamListenerBuilder as ListenerBuilder;

class PaypalIPN extends Controller
{
    public function notification($sale_id)
    {
        $sale_id_ns = \Hashids::connection(Sale::class)->decode($sale_id)[0] ?? null;
        $sale = Sale::withoutGlobalScope('completed')->findOrFail($sale_id_ns);

        $listenerBuilder = new ListenerBuilder();
        //$listenerBuilder->useSandbox(); // use PayPal sandbox
        $listener = $listenerBuilder->build();

        $listener->onVerified(function (MessageVerifiedEvent $event) use ($sale) {
            $ipnMessage = $event->getMessage();
            Log::error($ipnMessage);
            
            $transaction_type = $ipnMessage->get('txn_type');
            if($transaction_type !== 'web_accept')
                abort(400);

            $amount = $ipnMessage->get('mc_gross');

            if($amount != number_format($sale->revenue_before_tax, 2))
                abort(400);

            if($ipnMessage->get('payment_status') != 'Completed')
                abort(400);

            if($ipnMessage->get('mc_currency') != 'EUR')
                abort(400);

            DB::transaction(function () use ($sale) {
                $sale->complete();
                $sale->subscription->community->user()->increment('balance', $sale->revenue_after_tax);
            }, 10);

            Log::error('[paypal] User with steamid64: '.$sale->steamid64.' bought subscription'. $sale->subscription->id. ' of community '.$sale->subscription->community->small_name. '.');

            // IPN message was verified, everything is ok! Do your processing logic here...
        });

        $listener->onInvalid(function (MessageInvalidEvent $event) {
            $ipnMessage = $event->getMessage();
            Log::error($ipnMessage);

            // IPN message was was invalid, something is not right! Do your logging here...
        });

        $listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
            $error = $event->getError();
            Log::error($error);

            // Something bad happend when trying to communicate with PayPal! Do your logging here...
        });

        $listener->listen();
    }

}

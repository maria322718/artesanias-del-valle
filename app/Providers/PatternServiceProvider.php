<?php

declare(strict_types=1);

namespace App\Providers;

use App\Patterns\Adapter\ExternalLegacyBankGateway;
use App\Patterns\Adapter\LegacyBankAdapter;
use App\Patterns\Observer\Observers\ArtisanNotificationObserver;
use App\Patterns\Observer\Observers\AuditLogObserver;
use App\Patterns\Observer\Observers\StockReductionObserver;
use App\Patterns\Observer\OrderSubject;
use App\Patterns\Strategy\CreditCardPaymentStrategy;
use App\Patterns\Strategy\LegacyBankPaymentStrategy;
use App\Patterns\Strategy\PaymentStrategyRegistry;
use App\Patterns\Strategy\PaypalPaymentStrategy;
use App\Patterns\Strategy\PSEPaymentStrategy;
use App\Services\CheckoutService;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para el registro e inyección de dependencias de los 5 Patrones GoF.
 */
class PatternServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Registro del Patrón Strategy y Adapter
        $this->app->singleton(PaymentStrategyRegistry::class, function ($app) {
            $registry = new PaymentStrategyRegistry();

            // Estrategias Canónicas
            $registry->register(new CreditCardPaymentStrategy());
            $registry->register(new PSEPaymentStrategy());
            $registry->register(new PaypalPaymentStrategy());

            // Estrategia con Patrón Adapter integrado
            $legacyBankGateway = new ExternalLegacyBankGateway('BANCO-ARTESANAL-COLOMBIA-909');
            $legacyBankAdapter = new LegacyBankAdapter($legacyBankGateway);
            $registry->register(new LegacyBankPaymentStrategy($legacyBankAdapter));

            return $registry;
        });

        // 2. Registro del Patrón Observer (Sujeto y Observadores suscritos)
        $this->app->singleton(OrderSubject::class, function ($app) {
            $subject = new OrderSubject();

            $subject->attach(new AuditLogObserver());
            $subject->attach(new ArtisanNotificationObserver());
            $subject->attach(new StockReductionObserver());

            return $subject;
        });

        // 3. Orquestador de Checkout
        $this->app->singleton(CheckoutService::class, function ($app) {
            return new CheckoutService(
                $app->make(PaymentStrategyRegistry::class),
                $app->make(OrderSubject::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}

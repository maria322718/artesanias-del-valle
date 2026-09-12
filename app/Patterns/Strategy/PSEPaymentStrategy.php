<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

/**
 * Estrategia Concreta 2: Transferencia Bancaria Directa PSE (Colombia).
 */
class PSEPaymentStrategy implements PaymentStrategyInterface
{
    public function pay(float $amount, array $paymentDetails): PaymentResult
    {
        $bankCode = (string) ($paymentDetails['pse_bank'] ?? '');
        $docType = (string) ($paymentDetails['doc_type'] ?? 'CC');
        $docNumber = (string) ($paymentDetails['doc_number'] ?? '');

        if (empty($bankCode)) {
            return PaymentResult::failed('Debe seleccionar una entidad bancaria autorizada en la red PSE.');
        }

        if (empty($docNumber)) {
            return PaymentResult::failed('El documento de identidad es requerido para debitar vía PSE.');
        }

        $pseTicket = 'PSE-COL-' . strtoupper(substr(sha1(uniqid((string) mt_rand(), true)), 0, 12));
        $cusCode = (string) mt_rand(10000000, 99999999);

        return PaymentResult::success(
            transactionId: $pseTicket,
            message: "Transferencia bancaria PSE aprobada por la entidad {$bankCode}",
            payload: [
                'bank' => $bankCode,
                'doc_type' => $docType,
                'doc_number' => $docNumber,
                'cus' => $cusCode,
                'amount' => $amount,
                'currency' => 'COP',
                'state' => 'OK_APPROVED',
            ]
        );
    }

    public function getMethodCode(): string
    {
        return 'pse';
    }

    public function getMethodName(): string
    {
        return 'PSE - Débito Bancario en Línea (Bancolombia, Nequi, Davivienda, etc.)';
    }
}

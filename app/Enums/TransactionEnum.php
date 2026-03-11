<?php

namespace App\Enums;

enum TransactionEnum:string
{
    case REFERRAL = 'referral';
    case CASHBACK = 'cashback';
    case SALE_INCOME = 'sale_income';
    case WITHDRAWAL = 'withdrawal';
    case REFUND = 'refund';
    case ADJUSTMENT = 'adjustment';
    case PROMOCODE = 'promocode';
    case PROMO_PAYOUT = 'promo_payout';
}

<?php

namespace Srdorado\SiigoClient\Enum;

class ClientType
{
    public const TOKEN = \Srdorado\SiigoClient\Factory\Client\ClientTokenFactory::class;
    public const PRODUCT = \Srdorado\SiigoClient\Factory\Client\ClientProductFactory::class;
    public const CUSTOMER = \Srdorado\SiigoClient\Factory\Client\ClientCustomerFactory::class;
    public const GENERIC = \Srdorado\SiigoClient\Factory\Client\ClientGenericFactory::class;
    public const INVOICE = \Srdorado\SiigoClient\Factory\Client\ClientInvoiceFactory::class;
    public const CREDIT_NOTE = \Srdorado\SiigoClient\Factory\Client\ClientCreditNoteFactory::class;
    public const VOUCHER = \Srdorado\SiigoClient\Factory\Client\ClientVoucherFactory::class;
    public const JOURNAL = \Srdorado\SiigoClient\Factory\Client\ClientJournalFactory::class;
}

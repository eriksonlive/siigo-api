<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Generic
{
    public const TAXES = 'v1/taxes';

    public const GET_DOCUMENT_TYPES = 'v1/document-types?type=%s';
    public const GET_PRICE_LISTS = 'v1/price-lists';
    public const GET_USERS = 'v1/users?page=%s&page_size=%s';
    public const GET_PAYMENT_TYPES = 'v1/payment-types?document_type=%s';
    public const GET_COST_CENTERS = 'v1/cost-centers';
    public const GET_FIXED_ASSETS = 'v1/fixed-assets';

    public const HEADER_POST = [
        'Content-Type' => 'application/json',
        'Authorization' => ''
    ];
}

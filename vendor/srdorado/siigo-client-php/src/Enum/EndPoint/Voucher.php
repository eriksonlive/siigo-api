<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Voucher
{
    public const CREATE = 'v1/vouchers';
    public const CREATE_ADVANCED = 'v1/vouchers';
    public const GET_BY_ID = 'v1/vouchers/%s';

    public const GET_ALL = 'v1/vouchers?page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/vouchers?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/vouchers?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_START = 'v1/vouchers?date_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_END = 'v1/vouchers?date_end=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/vouchers?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/vouchers?updated_end=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_BRANCH_OFFICE = 'v1/vouchers?customer_branch_office=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_IDENTIFICATION = 'v1/vouchers?customer_identification=%s&page=%s&page_size=%s';
    public const GET_BY_DOCUMENT_ID = 'v1/vouchers?document_id=%s&page=%s&page_size=%s';
}

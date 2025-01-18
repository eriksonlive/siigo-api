<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class CreditNote
{
    public const CREATE = 'v1/credit-notes';
    public const GET_BY_ID = 'v1/credit-notes/%s';
    public const PDF = 'v1/invoices/credit-notes/pdf';

    public const GET_ALL = 'v1/credit-notes?page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/credit-notes?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/credit-notes?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_START = 'v1/credit-notes?date_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_END = 'v1/credit-notes?date_end=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/credit-notes?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/credit-notes?updated_end=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_BRANCH_OFFICE = 'v1/credit-notes?customer_branch_office=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_IDENTIFICATION = 'v1/credit-notes?customer_identification=%s&page=%s&page_size=%s';
    public const GET_BY_DOCUMENT_ID = 'v1/credit-notes?document_id=%s&page=%s&page_size=%s';
}

<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Invoice
{
    public const CREATE = 'v1/invoices';
    public const GET = 'v1/invoices/%s';
    public const UPDATE = 'v1/invoices/%s';
    public const DELETE = 'v1/invoices/%s';
    public const ANNUL = 'v1/invoices/%s/annul';
    public const MAIL = 'v1/invoices/%s/mail';
    public const STAMP_ERRORS = 'v1/invoices/%s/stamp/errors';
    public const PDF = 'v1/invoices/%s/pdf';

    public const GET_ALL = 'v1/invoices?page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/invoices?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/invoices?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_START = 'v1/invoices?date_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_END = 'v1/invoices?date_end=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/invoices?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/invoices?updated_end=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_BRANCH_OFFICE = 'v1/invoices?customer_branch_office=%s&page=%s&page_size=%s';
    public const GET_BY_NAME = 'v1/invoices?name=%s&page=%s&page_size=%s';
    public const GET_BY_CUSTOMER_IDENTIFICATION = 'v1/invoices?customer_identification=%s&page=%s&page_size=%s';
    public const GET_BY_DOCUMENT_ID = 'v1/invoices?document_id=%s&page=%s&page_size=%s';
}

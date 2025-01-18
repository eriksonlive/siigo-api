<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Customer
{
    public const CREATE = 'v1/customers';
    public const GET_ALL = 'v1/customers?page=%s&page_size=%s';
    public const GET_BY_ID = 'v1/customers/%s';
    public const GET_BY_BRANCH_OFFICE = 'v1/customers?branch_office=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/customers?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/customers?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/customers?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/customers?updated_end=%s&page=%s&page_size=%s';
    public const UPDATE = 'v1/customers/%s';
    public const DELETE = 'v1/customers/%s';

    public const HEADER_POST = [
        'Content-Type' => 'application/json',
        'Authorization' => ''
    ];
}

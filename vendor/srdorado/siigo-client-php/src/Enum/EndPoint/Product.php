<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Product
{
    public const CREATE = 'v1/products';
    public const GET_ALL = 'v1/products?page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/products?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/products?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/products?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/products?updated_end=%s&page=%s&page_size=%s';
    public const GET_BY_ID = 'v1/products/%s';
    public const GET_BY_CODE = 'v1/products?code=%s';
    public const UPDATE = 'v1/products/%s';
    public const DELETE = 'v1/products/%s';
    public const ACCOUNT_GROUPS = 'v1/account-groups';
    public const WAREHOUSES = 'v1/warehouses';

    public const HEADER_POST = [
        'Content-Type' => 'application/json',
        'Authorization' => ''
    ];
}

<?php

namespace Srdorado\SiigoClient\Enum\EndPoint;

class Journal
{
    public const CREATE = 'v1/journals';
    public const GET_BY_ID = 'v1/journals/%s';

    public const GET_ALL = 'v1/journals?page=%s&page_size=%s';
    public const GET_BY_CREATED_START = 'v1/journals?created_start=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_START = 'v1/journals?updated_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_START = 'v1/journals?date_start=%s&page=%s&page_size=%s';
    public const GET_BY_DATE_END = 'v1/journals?date_end=%s&page=%s&page_size=%s';
    public const GET_BY_CREATED_END = 'v1/journals?created_end=%s&page=%s&page_size=%s';
    public const GET_BY_UPDATED_END = 'v1/journals?updated_end=%s&page=%s&page_size=%s';
    public const GET_BY_DOCUMENT_ID = 'v1/journals?document_id=%s&page=%s&page_size=%s';
}

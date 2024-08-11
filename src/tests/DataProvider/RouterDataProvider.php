<?php

namespace Tests\DataProvider;

class RouterDataProvider
{
    public function routeCases(): array
    {
        return [
            ['/users', 'put'],
            ['/invoices', 'post'],
            ['/users', 'get'],
            ['/users', 'post'],
        ];
    }
}

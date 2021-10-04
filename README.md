# iqomp/growinc-pga

The library for working with GrowInc PGA.

## Installation

```bash
composer require iqomp/growinc-pga
```

## Usage

This module add new class that can be used to work with GrowInc PGA API.

```php

use Iqomp\GrowIncPGA\PGA;

$pga = new PGA($merc_code, $merc_secret);

// get all payment methods
$payment_methods = $pga->getPaymentMethods();

// create new bill
$body = [
    'invoice_no' => ::string,
    'description' => ::string,
    'amount' => ::int,
    'customer_name' => ::string,
    'customer_email' => ::string,
    'customer_phone' => ::string,
    'expire_in' => ::int,
    'payment_method_code' => ::string
];
$bill = $pga->createBill($body);

// check bill status
$body = [
    'reference_no' => ::string
];
$status = $pga->checkBill($body);

// get all exists transactions
$body = [
    'start_date' => ::string,
    'end_date' => ::string,
    'show_per_page' => ::int
];
$trans = $pga->getTransactions($body);
```

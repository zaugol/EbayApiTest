# eBay API Integration

This project provides a PHP class for integrating with the eBay API to retrieve the latest orders along with their tracking numbers. 

## Requirements

- PHP 7.4 or higher
- cURL extension enabled

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/zaugol/ebay-api-integration.git
   ```

2. Install dependencies:
   ```
   composer install
   ```

## Usage

1. Update the `example.php` file with your eBay API key.

2. Run the example script:
   ```
   php example.php
   ```

## Running Tests

To run the unit tests:

```
./vendor/bin/phpunit tests
```

## Class Documentation

### EbayApiIntegration

The main class for interacting with the eBay API.

#### Methods

- `__construct($apiKey)`: Initialize the class with your eBay API key.
- `getLatestOrders($limit = 10)`: Retrieve the latest orders from eBay. Returns an array of orders with their IDs and tracking numbers.
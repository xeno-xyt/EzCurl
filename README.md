# EzCurl - A Super Simple PHP HTTP Client

EzCurl is a lightweight, beginner-friendly PHP HTTP client library powered by cURL. It simplifies making HTTP requests in PHP while maintaining solid performance.

Created by **KJ @ DigitalOcean**.

## Why EzCurl?
- Simple and easy to use for beginners.
- Performance-focused.
- Automatically handles JSON, headers, and common cURL options.

---

## Features

- GET, POST, PUT, DELETE, etc.
- JSON and custom body support.
- Easy header manipulation.
- Auto JSON decoding (if `Accept: application/json` is set).
- Clean and chainable syntax.

---

## Installation

Just include the `EzCurl` class in your project:

```php
require_once 'EzCurl.php';
```

---

## Basic Usage

### Example: Simple GET request

```php
$client = new EzCurl();
$response = $client->makeRequest('GET', 'https://api.example.com/data')
                   ->Response();

print_r($response);
```

---

### Example: POST with JSON

```php
$client = new EzCurl();
$response = $client->addBody('application/json', ['name' => 'John', 'age' => 25])
                   ->accept('application/json')
                   ->makeRequest('POST', 'https://api.example.com/users')
                   ->Response();

print_r($response);
```

---

### Example: Set Custom Headers

```php
$client = new EzCurl();
$response = $client->addHeader('Authorization', 'Bearer your_token_here')
                   ->makeRequest('GET', 'https://api.example.com/protected')
                   ->Response();

print_r($response);
```

---

## Advanced

### Get HTTP Status Code

```php
$status = $client->getStatusCode();
echo "Status: $status";
```

### Close Connection (optional, auto done in destructor)

```php
$client->close();
```

---

## License

Free to use, modify, and distribute. Attribution is appreciated.

---

## Author

**KJ @ DigitalOcean**  
Making HTTP simpler for beginners.

---

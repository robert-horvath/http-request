[![Build Status](https://travis-ci.org/robert-horvath/http-request.svg?branch=develop)](https://travis-ci.org/robert-horvath/http-request)
[![Code Coverage](https://codecov.io/gh/robert-horvath/http-request/branch/develop/graph/badge.svg)](https://codecov.io/gh/robert-horvath/http-request)
[![Latest Stable Version](https://img.shields.io/packagist/v/robert/http-request.svg)](https://packagist.org/packages/robert/http-request)
# HTTP Request
The HTTP request module is a thin wrapper to access the HTTP request message information.
## Example Usage Of HTTP Request Class
The HTTP request message below
```http
POST /new-users?foo=bar HTTP/1.1
Host: example.com
Accept: plain/text;q=0.1
Content-Type: application/prs.api.ela.do+json;version=1
Content-Length: 13

{ "a": true }
```
and this PHP code would produce the following output.
```php
$req = RHo\Http\Request::init();
var_dump(
  $req->methdod(),               // string(4) "POST"
  $req->version(),               // string(8) "HTTP/1.1"
  $req->target(),                // string(18) "/new-users?foo=bar" 
  $req->queryStr("foo"),         // string(3) "bar"
  $req->queryStr("baz"),         // NULL
  $req->header("Accept"),        // string(16) "plain/text;q=0.1"
  $req->header("Authorization"), // NULL
  $req->msgBody()                // string(13) "{ "a": true }"
);
```
If the HTTP message body cannot be read, then a ```\RuntimeException``` exception is raised.

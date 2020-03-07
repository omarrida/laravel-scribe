![Scribe Banner](/scribe-banner-under-dev@2x.png)
# Laravel Scribe
Spend less time worrying about maintaining docs and more time writing great code.

## Getting Started
Install the package as a dev dependency to your project.

```
composer require --dev omarrida/laravel-scribe
```

Run  the `scribe:generate` command to generate API docs. You should see a `scribe.md` file appear in your project's root directory.

```
php artisan scribe:generate
```

## Usage
Right now the `MarkdownFormatter` is really simple. For each route, it shows:
- URI
- HTTP Method
- Validation rules from `FormRequest`

> Scribe tries to find the validation rules by reflecting on the controller method associated with the route and looking for a custom `FormRequest` in the typehint. If it finds one, it will call the `rules()` method on it and parse the return array of validation rules.

## Sample Docs
Here's some sample docs I generated from a real existing Laravel 6 project I'm working on. They're not pretty, but they're updated automagically!

> Update: We now blindly call POST on all routes to get any response at all. Brilliant!

## `api/auth/register`
**URI:** `api/auth/register`

**HTTP Method:** `POST`

**Validation Rules:**

| Param | Rules |
| ---- | ---- |
|first_name|required,string|
|last_name|required,string|
|email|required,email:rfc,dns,unique:users,email,max:64|
|password|required,string,regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/,max:64|

**Success Response:**

```
{
    "message": "The given data was invalid.",
    "errors": {
        "first_name": [
            "The first name field is required."
        ],
        "last_name": [
            "The last name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ],
        "country": [
            "The country field is required."
        ]
    }
}
```
---

## `api/auth/login`
**URI:** `api/auth/login`

**HTTP Method:** `POST`

**Validation Rules:**

| Param | Rules |
| ---- | ---- |
|email|required,string,email:rfc,dns|
|password|required,string|

**Success Response:**

```
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```
---

## `api/auth/logout`
**URI:** `api/auth/logout`

**HTTP Method:** `POST`

**Validation Rules:** n/a

**Success Response:**

```
{
    "message": "You have successfully logged out."
}
```
---

## Limitations
Scribe doesn't know how to generate sample responses yet. One day soon...

Avoid putting any kind of authentication logic within the `rules()` method of your custom `FormRequest`. Scribe uses reflection to access the information and will not have an authed user when it calls  the `rules()` method. This is a common cause of fatal errors when running `scribe:generate`.

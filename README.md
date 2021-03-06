![Scribe Banner](/scribe-banner-under-dev@2x.png)
# Laravel Scribe
Spend less time worrying about maintaining docs and more time writing great code.

## Getting Started

Install the package as a dev dependency to your project. Since the package isn't in any public registry, add this repository to your `composer.json`.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/omarrida/laravel-scribe"
        }
    ]
}
```

Now you can require it as a dev dependency. You should get the `dev-master` versionn.

```shell script
composer require --dev omarrida/laravel-scribe
```

Run  the `scribe:generate` command to generate API docs. A `scribe.md` file should appear in your project's root directory. If not, enjoy the errors!

```shell script
php artisan scribe:generate
```

## Usage
Right now the `MarkdownFormatter` is really simple. For each route, it shows:
- URI
- HTTP Method
- Validation rules from `FormRequest`
- Some sample responses for unauthed GET and POST requests.
- Few sample responses for authed GET and POST requests.

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
|country|required,string,exists:country,name|
|account_type|nullable,string,in:personal,business|

**Success Response:**

```
{
    "message": "Thanks for signing up! Please check your email to complete your registration."
}
```
---

## `api/auth/logout`
**URI:** `api/auth/logout`

**HTTP Method:** `POST`

**Validation Rules:** n/a

**Success Response:**

```json
{
    "message": "You have successfully logged out."
}
```
---

## Limitations
You MUST be using JWTAuth and have your user model located at `\App\Auth\User`  for Scribe to get responses for authed routes.

Scribe is still learning how to guess success responses. It can do basic GET and POST and authed GET and POST with a basic user.

Avoid putting any kind of authentication logic within the `rules()` method of your custom `FormRequest`. Scribe uses reflection to access the information and will not have an authed user when it calls  the `rules()` method. This is a common cause of fatal errors when running `scribe:generate`.

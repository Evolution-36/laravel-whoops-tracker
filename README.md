## Current status
- Assets can currently be compiled into a public directory within the package.
- With `php artisan vendor:publish --tag=public`, users can publish the assets to their project
- The dashboard can be found at /lwt

### Minimally still required
- Implementing a better/more interface to check out the whoopses
- Implement a menu and find a solution for users to navigate to and from it
- Add configuration for routes, amount of context-lines and perhaps more

## Installation
For installation in development see below
- `composer require evolution36/laravel-whoops-tracker`
- `php artisan migrate`

## Dev installation
- Clone the repository
- Add the following section to the composer.json of your project

```javascript
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-whoops-tracker",
            "options": {
                "symlink": true
            }
        }
    ],
```

- Add `evolution36/laravel-whoops-tracker` to the requires-section of composer.json of your project
- Run `composer update` in your project

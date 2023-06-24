# Task Api
## Tasks RESTful api for learning/practice purposes.

What you'll need to do in order to run this API in your environment.
``composer install``
``php artisan migrate``
``php artisan serve``

## Auth

### Routes for registering and logging into users.
``POST /register`` - Register user
- name (required)
- email (required)
- password (min:8|required)
- password_confirmation (must match password)


``POST /login`` - Login as user (cookie based session)
- email (required)
- password (min:8|required)

This API currently uses Sanctum to issues personal access tokens.
you can get the tokens from This route after being logged in a session.
``POST /tokens/create``
It will return a plain_text token based on the value ``api_token`` specifically made for your user.
After that, don't forget to add into the header
``Bearer {auth_token}`` so you are authenticated to use the ``/api/*`` routes

Obs. CSRF is currently disabled for the sake of testing.

## Collections
### Tasks
``GET /api/tasks`` - Gets all tasks
``POST /api/tasks`` - Creates Task
- name (required)
- description (min:8|required)
- deadline (date|required)

``GET /api/tasks/{id}`` - Gets specified task (acts as filter)
``PATCH /api/tasks/{id}`` - Alters Task
- name (required)
- description (min:8|required)
- deadline (date|required)

``PATCH /api/tasks/finish/{id}`` - Finishes Task
``DELETE /api/tasks/{id}`` - Deletes Task

## Troubleshooting

In case sanctum is not working execute ``php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`` then ``php artisan migrate``.

Don't forget to add a header for ``Accept application/json`` in postman in case the requests are failing.
Also, don't forget to add type Bearer token authorization and insert the text gotten from ``/tokens/create``

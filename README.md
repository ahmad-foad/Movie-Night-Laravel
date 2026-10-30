# Movie Night API

A REST API built with **Laravel 13** and **PostgreSQL** for the Information System Architecture midterm.

Movie Night is a clean-content movie catalog for family and friend movie nights. Each movie has content flags for **nudity**, **violence** and **language** (`none`, `mild`, `moderate` or `severe`), plus optional **clean edit** info for movies that have a trimmed or muted version. Users can filter the catalog to family-clean movies, mark movies as watched, and rate and review them.

It is a Laravel rebuild of my earlier Java Spring project, [Movie-Night](https://github.com/ahmad-foad/Movie-Night).

## What it uses from the course

| Week | Topic | Where it is used |
| --- | --- | --- |
| 1 | API-only Laravel, `routes/api.php`, JSON errors | `bootstrap/app.php`, `routes/api.php` |
| 2 | Controllers, Form Requests, API Resources | `app/Http/Controllers`, `app/Http/Requests`, `app/Http/Resources` |
| 3 | Migrations, models, seeders, route model binding | `database/migrations`, `app/Models`, `database/seeders` |
| 4 | `PATCH` partial updates, `DELETE` with 204 | `update` and `destroy` methods |

Added for this project:

- **PostgreSQL** instead of SQLite.
- **Four connected tables** using `hasMany` and `belongsTo` relationships.
- **Filters** on the movie list: `?genre=` and `?clean=true`.
- **The family-clean rule**: a movie is family clean when all three flags are `none` or `mild`, or when a clean edit is available. It lives in `Movie::isFamilyClean()` and the `familyClean()` query scope.
- **Average rating and review count** on each movie, using `withAvg` and `withCount` so the list runs in one query.
- **Swagger docs** generated automatically by [Scramble](https://scramble.dedoc.co) from the routes, Form Requests and Resources.

## Database

```
users                movies                           watches                   reviews
-----                ------                           -------                   -------
id                   id                               id                        id
username  (unique)   title                            user_id  FK -> users      user_id  FK -> users
email     (unique)   genre                            movie_id FK -> movies     movie_id FK -> movies
password  (hashed)   release_year                     watched_at                rating   (1-5)
timestamps           nudity_level     (none..severe)  timestamps                comment  (nullable)
                     violence_level   (none..severe)  UNIQUE(user_id,movie_id)  timestamps
                     language_level   (none..severe)
                     has_clean_edit   (true/false)
                     clean_edit_notes (nullable)
                     timestamps
```

Deleting a movie or a user also deletes its watches and reviews.

## Endpoints

All paths start with `/api`. Send `Accept: application/json` with every request.

| Method | Path | Result |
| --- | --- | --- |
| GET | `/hello` | 200, check that the API is running |
| GET | `/users` | 200, all users |
| GET | `/users/{user}` | 200, user with watch history; 404 if not found |
| POST | `/users` | 201, user created; 422 on invalid input |
| GET | `/movies` | 200, all movies; supports `?genre=` and `?clean=true` |
| GET | `/movies/{movie}` | 200, movie with average rating and reviews; 404 if not found |
| POST | `/movies` | 201 / 422 |
| PATCH | `/movies/{movie}` | 200, partial update / 422 |
| DELETE | `/movies/{movie}` | 204 |
| POST | `/movies/{movie}/watch` | body `{"user_id": 1}`; 201 first time, 200 if already watched |
| GET | `/movies/{movie}/reviews` | 200, reviews for the movie |
| POST | `/movies/{movie}/reviews` | body `{"user_id": 1, "rating": 5, "comment": "..."}`; 201 / 422 |
| PATCH | `/reviews/{review}` | 200 / 422 |
| DELETE | `/reviews/{review}` | 204 |

Example movie in a response:

```json
{
  "data": {
    "id": 13,
    "title": "Titanic",
    "genre": "Drama",
    "release_year": 1997,
    "nudity_level": "severe",
    "violence_level": "moderate",
    "language_level": "moderate",
    "has_clean_edit": true,
    "clean_edit_notes": "The drawing scene and the car scene are trimmed in the family edit.",
    "is_family_clean": true,
    "average_rating": 4,
    "reviews_count": 1
  }
}
```

## Requirements

- PHP 8.3 or newer with the `pdo_pgsql` and `pgsql` extensions turned on in `php.ini`.
- Composer 2.
- PostgreSQL (tested with version 18).
- Postman (optional; the Swagger page can also send requests).

## Setup

1. Install the packages and create `.env`:

   ```sh
   composer install
   php -r "file_exists('.env') || copy('.env.example', '.env');"
   php artisan key:generate
   ```

2. Open `.env` and put your PostgreSQL password in `DB_PASSWORD=`. The other database settings are already filled in:

   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=movie_night
   DB_USERNAME=postgres
   ```

3. Create an empty database called `movie_night` (in pgAdmin: right-click **Databases → Create → Database**). Then create the tables and the sample data:

   ```sh
   php artisan migrate
   php artisan db:seed
   ```

   The seeders add 3 users (`sara`, `karwan`, `lana`), 16 real movies and 8 reviews. Running `db:seed` again updates the same records instead of making duplicates.

4. Start the server:

   ```sh
   php artisan serve
   ```

   Open **http://127.0.0.1:8000/api/hello**. You should see `{"message":"Hello World"}`.

## API documentation (Swagger)

With the server running, open **http://127.0.0.1:8000/docs/api**.

Scramble reads the routes, the Form Request rules and the Resources, and builds the documentation from them. For example, `rating` shows as an integer from 1 to 5 because that is the rule in `StoreReviewRequest`. Use the **Try It** panel to send real requests from the browser.

The docs page loads its viewer from the internet. When you are offline, use Postman instead.

To save the OpenAPI file:

```sh
php artisan scramble:export
```

## Postman

Import `postman/MovieNight.postman_collection.json` into Postman. It has a request for every endpoint, including examples that return 404 and 422. Change the `movie_id`, `user_id` and `review_id` variables on the collection to try other records.

## Useful commands

| Command | Purpose |
| --- | --- |
| `php artisan serve` | Start the server |
| `php artisan route:list --path=api` | List all API routes |
| `php artisan migrate:status` | Check which migrations ran |
| `php artisan db:seed` | Add or refresh the sample data |
| `php artisan migrate:fresh --seed` | Delete all tables and rebuild them with sample data |

## Project structure

```
app/
  Http/Controllers/  UserController, MovieController, WatchController, ReviewController
  Http/Requests/     StoreUserRequest, StoreMovieRequest, UpdateMovieRequest,
                     WatchMovieRequest, StoreReviewRequest, UpdateReviewRequest
  Http/Resources/    UserResource, MovieResource, WatchResource, ReviewResource
  Models/            User, Movie, Watch, Review
database/
  migrations/        users, movies, watches, reviews
  seeders/           UserSeeder, MovieSeeder, ReviewSeeder
routes/api.php
postman/MovieNight.postman_collection.json
```

## Planned for the final project

- Login with tokens (Laravel Sanctum) instead of sending `user_id` in the body.
- Admin and user roles, with authorization policies.
- Search, sorting and pagination.
- Review rules: review only after watching, one review per movie.
- Automated tests.

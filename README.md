# Postagram — Backend

An Instagram-style social media REST API built with Laravel, powering a separate React frontend. Token-based authentication, multi-media posts, 24-hour expiring stories, likes, follows, comments, and notifications.

**Live API:** [https://postgram-backend-puce.vercel.app](https://postgram-backend-puce.vercel.app)
**Live app (frontend):** [https://postgram-laveeza.vercel.app](https://postgram-laveeza.vercel.app)
**API documentation:** [API.md](./API.md)

---

## Features

- **Authentication** — Laravel Sanctum token-based auth, with scoped token abilities (e.g. `posts:create`, `stories:delete`) rather than one all-or-nothing token
- **Posts** — full CRUD with **multi-media support**: a single post can hold any combination of images, videos, and text slides, stored through an ordered `post_media` relation
- **Comments** — full CRUD, with ownership-gated edit and delete
- **Likes** — toggleable likes on posts, with counts computed through `withCount`
- **Follows** — follow/unfollow toggle, with follower/following counts and an `is_following` flag resolved without N+1 queries across paginated lists (`scopeWithIsFollowedByAuth`)
- **Stories** — 24-hour expiring stories (image, video, or text with a background color), with per-story likes and idempotent view tracking; expired stories are purged by a scheduled Artisan command
- **Notifications** — new-follower notifications through Laravel's database and broadcast channels; new-comment notifications by email through a queued listener
- **Profile** — bio and avatar upload, plus a public profile endpoint with a user's posts, follower/following counts, and follow status
- **Search** — search posts by title or body, built into the existing paginated posts endpoint
- **Cursor Pagination** — feed and profile listings use cursor pagination instead of offset-based, which suits infinite scroll
- **File Storage** — image and video uploads go to S3-compatible object storage (Supabase Storage), so uploaded media survives redeploys
- **N+1 Query Prevention** — eager loading and `withCount` throughout, verified with query logging during development
- **Database Transactions** — multi-step writes (such as a post plus its media rows) are wrapped in transactions
- **API Resources** — consistent JSON response shapes through `PostResource`, `CommentResource`, `UserResource`, and `StoryResource`
- **Rate Limiting** — applied to auth endpoints and the general API surface
- **Automated Tests** — Pest test suite covering auth, token abilities, policies, and rate limiting

---

## Tech Stack

| Layer        | Technology                                      |
| ------------ | ----------------------------------------------- |
| Backend      | PHP 8.4, Laravel                                |
| Database     | MySQL (Aiven, managed)                          |
| Auth         | Laravel Sanctum (token-based, scoped abilities) |
| File Storage | S3-compatible object storage (Supabase Storage) |
| Queue        | Queued notifications and emails                 |
| Email        | Resend (production), Mailtrap (local testing)   |
| Testing      | Pest                                            |
| Deployment   | Vercel (Docker container running FrankenPHP)    |
| Frontend     | React, Vite, Tailwind CSS (separate repo)       |

---

## Architecture Notes

This project was built step by step, from vanilla PHP fundamentals through to Laravel, with a focus on understanding _why_ the framework is structured the way it is.

- **Authorization lives in Policies**, not scattered across controllers, so rules can't drift apart between endpoints.
- **Side effects are decoupled from the request cycle.** A new comment doesn't wait on an email being sent, and a new follower's notification is queued instead of delivered inline.
- **API Resources are the single source of truth for response shape.** Every endpoint that returns a user, post, comment, or story reuses the same Resource class, so one fix (for example, avatar URL formatting) applies everywhere that data appears.
- **Stories use computed expiry**, not a stored `expires_at` column: visibility is calculated from `created_at` on every query (`scopeActive()`), and a scheduled command separately deletes expired rows so the table doesn't grow without limit.
- **The app runs as a Docker container on Vercel** (FrankenPHP), not on a traditional always-on server. This meant solving real constraints: no persistent local disk (hence S3-compatible storage), PHP upload limits, and environment-variable collisions with the platform's own reserved `AWS_*` names.

---

## Local Setup

```bash
git clone https://github.com/Lveeza/postgram-backend.git
cd postgram-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Set your `DB_*`, `S3_*` (file storage), and `MAIL_*` credentials in `.env`, then:

```bash
php artisan serve
php artisan queue:work   # separate terminal, processes queued notifications and emails
```

---

## Running Tests

```bash
./vendor/bin/pest
```

---

## API

See [API.md](./API.md) for full endpoint documentation, including authentication, request and response examples, token abilities, and rate limiting.

---

## License

Open-sourced for portfolio and educational purposes.

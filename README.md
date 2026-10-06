# Product API (LavaLust)

JWT-protected product CRUD API. MySQL on Aiven, hosted on Render.

| Method | Endpoint | Auth |
|---|---|---|
| POST | /api/auth/register | no |
| POST | /api/auth/login | no |
| POST | /api/auth/refresh | no |
| POST | /api/auth/logout | no |
| GET | /api/auth/me | Bearer |
| GET / POST | /api/products | Bearer |
| GET / PUT / PATCH / DELETE | /api/products/{id} | Bearer |

Environment variables: see `.env.example`. See the deployment guide for full steps.

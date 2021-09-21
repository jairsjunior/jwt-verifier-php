# JWT Verifier Middleware

Laravel/Lumen sample project with a middleware to verify JWT tokens using public JWK.

### Requirements
- Git
- Docker
- docker-compose
- OAuth Server

## Getting Started

1. At `docker-compose.yml` file fill the Environment Variables with your information
    - JWT_AUDIENCE : Audience (aud) of your token
    - JWT_ISSUER: Issuer (iss) of your token
    - JWKS_URL: URL to the JWKS keys at your OAuth2 server

2. Start the containers using the `docker-compose` command
```
docker-compose up
```

3. Make a curl to the address `http://localhost:8000` without a `Authorization` header. You will receive a 401 error with a message.
```
curl http://localhost:8000
```

4. Make a curl to the address `http://localhost:8000` with a valid Bearer token at the `Authorization` header. You will receive a 200 response.
```
curl -H 'Authorization: Bearer $ACCESS_TOKEN' http://localhost:8000
```


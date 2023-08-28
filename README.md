## Laravel SSO Client

Sample Laravel Client app that implements oAuth2 with SSO. (see also [Laravel SSO Auth](https://github.com/carlostarinsure/laravel_sso_auth))

## Getting Started

Create a file for your environment variables.
```sh
cp .env.docker.example .env
```
Input client id and client secret in .env created from Laravel SSO Auth Server (sample)
```
APP_CLIENT_ID=9a0046e0-e46b-4159-bc71-99eee4c75aff
APP_CLIENT_SECRET=Y3Poq9LSuIrURQMG9WCDOpwEs6BJqsa1ptGSzcoX
```
Install Dependencies
```sh
composer install
```
```sh
npm install
```
Run server
```sh
php artisan serve --port=8080
```
```sh
npm run build or npm run dev
```

## Reference
- [Build oAuth 2.0 SSO with PHP](https://www.youtube.com/playlist?list=PLC-R40l2hJfdyfZ3jkDKOcyoqmIgw2wda)


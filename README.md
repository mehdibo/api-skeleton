# Symfony API skeleton ![Tests CI](https://github.com/mehdibo/api-skeleton/workflows/Tests%20CI/badge.svg) ![Run PHPStan](https://github.com/mehdibo/api-skeleton/workflows/Run%20PHPStan/badge.svg) ![Docker Image CI](https://github.com/mehdibo/api-skeleton/workflows/Docker%20Image%20CI/badge.svg)

This is a skeleton to help you start making an API using Symfony.

It contains oAuth2 and Rate limit, also some tools and helpers to get you started quickly.

# Setup

Follow these steps to get up and running

## oAuth2

### Configure public and private keys

You can follow [this guide](https://oauth2.thephpleague.com/installation/#generating-public-and-private-keys)
to generate a pair of keys.

And then on your .env file change `OAUTH2_PUBLIC_KEY` and `OAUTH2_PRIVATE_KEY` with your keys paths.

### Configure encryption key

Follow [this guide](https://oauth2.thephpleague.com/installation/#string-password) to generate an encryption key
and set it to `OAUTH2_ENCRYPTION_KEY` in your .env file 

## Database

This repo comes configured with MySQL in `docker-compose`, it is filled with default values to work
in the development environment.

**But you have to override them when deploying to production with more secure values** using the `-e` flag
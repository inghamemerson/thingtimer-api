# Thing Timer GraphQL

## About

The Thing Timer GraphQL backend was designed for the [Thing Timer client](https://github.com/inghamemerson/thingtimer-client) application. It is a simple Laravel application that serves a GraphQL endpoint to make a `Thing` and associate a `Timer` with the `Thing`. It lives [here](https://api.thingtimer.com/graphql-playground) and does not have any security.

## What it uses

- [PHP](https://www.php.net) You could probably get away without installing this and just relying on Docker.
- [Laravel](https://laravel.com/) PHP framework foundation.
- [GraphQL](https://graphql.org) ain’t no REST for the query… get it?
- [Lighthouse](https://lighthouse-php.com) GraphQL for Laravel.
- [Laradock](https://laradock.io) dev environment using [Docker](https://www.docker.com).
- [Heroku](https://www.heroku.com) remote hosting platform, I’m running a hobby dyno.
- [Postgres](https://www.postgresql.org) database, I’m running the cheapest on Heroku.
- [Composer](https://getcomposer.org) for PHP dependency management. You could install with [Homebrew](https://brew.sh) on a Mac.

## Getting Started

To get a local instance of the backend running, you need [Docker](https://docs.docker.com/docker-for-mac/install/) locally. Once you’ve got that set up and the repo cloned, open up your terminal and `cd` into this bad Larry.

1. First, from the root directory, let’s copy our environment variable file over since we’re upstanding, security minded devs who don’t commit that. We’ll copy the example file instead of renaming it, which would be mean to anyone else who wants to use this. Don’t worry about adding to this yet.
```
cp .env.example .env
```

2. Next up let’s get our environment running. Run the code below in the `laradock/` directory of the project, hopefully 🤞you don’t bump into any major issues here.
```
cd laradock/
cp env-example .env
docker-compose up -d nginx postgres workspace
```

3. Then, we’ll jump into the container our app is running in.
```
docker-compose exec workspace bash
```

4. Now that you’re in your workspace, we can install dependencies, generate an app key, and migrate the database. The credentials in the example .env file should work for the Docker DB but make sure you get those changed if you need to.

```
composer install
php artisan key:generate
php artisan migrate
```

6. Awesome! Things should be up and running and you should be able to go to the [playground](http://localhost/graphql-playground).

## Where the meat is

### Models
The models are straightforward for both the [Thing](https://github.com/inghamemerson/thingtimer-api/blob/master/app/Models/Thing.php) and the [Timer](https://github.com/inghamemerson/thingtimer-api/blob/master/app/Models/Timer.php). The most important details here are that we have the relationships as Laravel likes them, we’re casting timestamps, and we’re using softdeletes.

### Observers
The [ThingObserver](https://github.com/inghamemerson/thingtimer-api/blob/master/app/Observers/ThingObserver.php) is important to familiarize with since it is responsible for behind the scenes magic. When we create a new `Thing`, it generates the `uuid` when the create event is running (during the event and not after since the DB requires a `uuid`), so we don’t need to worry about it client-side. It also deletes every `Timer` associated when the `Thing` itself is deleted, so that we don’t have some garbage floating around in the database. I know, softdeletes so everything still sticks around…

### Schema
The [Schema](https://github.com/inghamemerson/thingtimer-api/blob/master/graphql/schema.graphql) defines the functionality that our client can leverage using GraphQL. Nothing too out of the ordinary here other than some extra juju Lighthouse adds to make this work with Laravel. The `Types` reflect the models, our `Mutations` cover creating, updating, and deleting, returning the object on complete. We can query all of the `Things`, get a single `Thing` based on the `uuid` and we can get an individual `Timer` based on its `id`.

Feel free to try some queries out in the [playground](http://localhost/graphql-playground).

#### Get Things
```
query getTheThings {
  things(
        orderBy: [
            {
                field: “created_at”,
                order: DESC
            }
        ]
    ) {
    id
    uuid
    title
    quantity
    created_at
    timers {
      id
      name
      started_at
      ended_at
    }
  }
}
```

#### Make Thing
```
mutation makeAThing{
  createThing(title: “Test thing”, quantity: “10”) {
    id
    uuid
    title
    quantity
  }
}
```

#### Make a Timer
Make sure the ID you pass here is the ID of an existing thing and that you replace the date string with an actual date.
```
mutation makeATimer{
  createTimer(id: 1, started_at: “YYYY-MM-DD HH:mm:ss”){
    name
    thing_id
    started_at
    ended_at
  }
}
```

## What's next
Some notes I have in mind for the future if I continue to tinker on this:
- [ ] Tighten up security either through CORS or user login or both
- [ ] Ensure Things are not global, associating them with those who create them (users or some UUID stored locally)
- [ ] Add more strict [validation](https://lighthouse-php.com/master/security/validation.html#validating-arguments) in GraphQL queries
- [ ] Integrate unit testng adn get some code coverage

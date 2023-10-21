---
title: Where To Host My App
author: 🦊 The Fox
date: 2023-10-21
tags: 
  - hosting
  - heroku
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

There comes a time in every man's life when he has to decide where to host his applications. Over the years, I went 
from shared VMs and cutesy "CPanel" installers to using containerized solutions. I never got that into the heavy 
DevOps, Kubernetes, clusters, or whatnots, but in this post, I'll go over how I came to launch my current site and 
what thought process I went through to get to the final deploy.

## Early WAMP

- My first foray was with WAMP/LAMP 
- I remember being foiled by the database connection many times
- No interest in setting up caching or anything other than a database and web server.
- Used Linode and a VM that was pretty nice to have multiple 

## Dr. Jekyll and Mr. Static Site

- I thought making a static site would be a better fit for a blog
- Used GitHub Pages and Jekyll for a while.

## Dating Docker

- Professionally, I ended up using Docker-based local dev environments
- Mainly Drupal-related but it helped with matching what's on production.

## No-code Apps

- I started using Medium.com and got into Webflow.
- Much better with distributing content and being included in publications
- Can't actually change anything about the site though
- Can get surprised with platform changes
- Need to use something else for dynamic code experiments.

## Whirlwind language and framework quest

- Got lucky that a job let me explore replacing a Drupal CMS with whatever else
- Had to be hosted on Heroku
- node.js and Strapi
- Elixir and Phoenix
- Next.js and friends
- Ruby on Rails
- Back to Drupal
- Desire to learn Python
- Setting on Symfony

## Where to host Symfony

- I looked at Digital Ocean
- Then remembered fly.io and render.com
- Comparing plans and addons, I went back to Heroku
- Redis Cloud addon sold me - link to databasing article

## Configuration Caveats

- The same bundles won't load on Heroku
- I have to comment out the dev bundles for the app to install
- Need the symfony/apache-pack package to add .htaccess

After choosing Heroku, I did have some trouble with deploying the application to Herkou. Heroku picked up that the 
application was PHP and grabbed the right PHP version from reading the `composer.json` file. It's nice not having to 
specifiy a config file for simple projects, but I did have to add a `Procfile` in order for the web server to work.

```
web: heroku-php-apache2 public
```

By adding that line in a `Procfile` at the root of my codebase, Heroku knows to point Apache at the `/public` for 
web requests. You can add lines for background workers and other services you want kicked off whenever a container 
is built, but I only need a web service for now.

### Dev Bundles


---
title: Picking Your Database From Needs
author: 🦊 The Fox
date: 2023-10-14
tags: 
  - databases
  - redis
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

Once a basic site structure and layout are created, the next thoughts usually go towards database needs. You need to 
store your content in some form, so it can be retrieved later at run time. I want to keep things as simple as 
possible for this site, and I don't want to get into complicated dynamicism just for the sake of it. Picking the 
right database backend can be tricky. 

In this post, I will go over my thought process for adding storage to my personal site comparing and contrasting all 
the solutions I thought about.

## Markdown Files

I wanted to write my blog posts in markdown files since I am very familiar with the syntax, it is the best syntax, 
IMHO, for writing blog posts, and it also can allow your site to be served in a static manner.

In fact, I originally thought that this site could be static, and I even went through some experiments to see if I 
could create a simple static site generator. However, there's too much trickery and gotchas to glue on a custom 
static site generator to a traditional web server. I'll write about that journey in another post, and at least I got 
a sitemap generator out of it I will keep using...also worth a post to discuss.

But back to content storage...Markdown files can work for blogs, and you can even pre-process the front matter to 
create pages or whatever during a build step. However, loading and processing files will never be as fast as caching 
the result and serving it from a database, and that's why so many web applications rely on caching to handle high 
loads.

So, my conclusion was: yes, markdown files are great for writing, but they fall down when you consider scaling and 
trying to dynamically do things with content from the markdown front matter. 

## SQLite

Once past static markdown files, I immediately thought of SQLite. The small library runs in a process and writes to 
a single file. Pretty simple, check! I could even include the database with my project without having to export 
database backups, how neat!

However...and there seems to be a rash of me howevering in this post...if you deploy your application to a hosting 
provider that uses containers and blows away your container at random times, you can never really store anything 
permanently. I guess you could use SQLite as a cache or something, but I want full persistence.

## PostGres

Stepping up a level, we get to Postgres. I've been using Postgres more and more for larger projects as it is 
increasingly becoming the default for many projects. I created this site using a Symfony starter template, and the 
configuration recommends Postgres complete with sample Docker files and all. 

I have no dislike of Postgres, but when I look at online providers, the Postgres service is always more expensive 
than some other options and Postgres is known more as a traditional database than a caching layer. I want to cache 
data that takes time to process from files, so I'm looking more for a caching tool.

## Redis

Finally, I arrived at Redis. I've been using Redis for years mostly in a very simple way, but it's always been 
considered cool in my book. The name sounds cool, the logo is pretty tight, and Redis actually has a decent amount 
of features.


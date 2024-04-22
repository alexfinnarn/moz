---
title: Building Moz
tags: 
  - moz 
  - rust 
  - wasm
date: 2023-09-26
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the 
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a 
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post 
  offers valuable insights for both novice and seasoned developers.
---

Here I sit beginning my journey to build a website that will host my future consulting business. I call it "Moz" 
since I decided to use Symfony and pay homage to Mozart, a badass composer of yore. Just like good ole' Mozy Mozart, 
I plan to turn it up to 11 in creating this site and really showcasing my ideas as well as technical expertise.

...more like field of dreams with if you build it they will come...and scene of building with wood. Well, the 
digital wood is code.

I originally was going to build this site using Python and Django, but there's no way I have enough time to learn a 
different programming language, learn a different framework, develop it for years, and then land a job working on 
Django sites. However, it is plausible to hire me to work on sites using PHP and involving Symfony since I've 
technically been using Symfony for years while using Drupal 8+. I feel pretty comfortable using Symfony and PHP so 
hopefully this goes a little bit smoother than if I were to have continued down the Django route.

...make it more like tools of the trade...

Lastly, I did consider Laravel since it is a popular PHP framework and they just came out with their Folio, 
page-based routing package that mimics what frameworks like Next.js are doing on the frontend/node.js. However, I 
always get confused using Laravel, and I've seen enough Laravel hate to make me go blind ten times over...so I guess 
I will avoid Laravel again, although I don't mean to throw any shade. It's just that Symfony is more straightforward 
for me to work with than Laravel.

## After Install - Layouts

Everyone knows or can look up how to create a new Symfony project with the `--web-app` option, but what do you do 
after that point. I tend to think about the layout of the site just to get that out of the way. For example, will I 
use a CSS framework? And how fancy am I trying to get with styles and layouts?

The answer always is and will be: KISS! 

So, I will default to adding Tailwind to my project and creating layouts for a homepage as well as blog posts. All I 
want to do at this point is publish this post I'm writing as a teaser on the homepage and have that link to the 
complete post. Both pages will have the same header menu with a logo linking to the home page and a primary menu 
that now only contains a link to "Posts".

Luckily for me, I found a framework guide in the Tailwind CSS docs section: https://tailwindcss.com/docs/guides/symfony

But as I read through how to add Tailwind with Webpack and all the processing, I thought to myself...do I really 
need Tailwind to do a simple site? Am I adding a decent bit of complexity and extra dependencies for the benefit of 
really learning CSS?

Oh yeah, now I remember that when I last used Tailwind I ended up ditching it because some CSS concepts, like CSS 
grid are impossible to translate to Tailwind without splitting them up and breaking the cohesiveness of the original 
CSS design.

```css
.grid-container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  grid-template-rows: 1fr 1fr 1fr;
  grid-template-areas:
    "header header header"
    "main main sidebar"
    "footer footer footer";
}
```

I'm not sure how to represent grid template areas in Tailwind [look this up...]

```bash
symfony console make:controller Home
symfony console make:controller Posts
```

With those two commands, we now have the home page and blog posts controllers complete with Twig templates as well. 
I will start by focusing on the Posts controller and related view.

When I look in my `index.html.twig` file to show Posts, I see a piece of code that tells me where the layout file lives:

```html
{% extends 'base.html.twig' %}
```

And sure enough in the `/templates` directory, there is a file called `base.html.twig`. I won't go into the details 
of how Twig works, but you'll see several block elements, like `{% block title %}`, that other templates can 
override when they extend the base template. The most important blocks for us to know about now are the "title" and 
"body" blocks. 

## Converting Markdown

While Markdown is a nice format to write in, the browser needs HTML to display to the user in the Twig template's 
output. For this task, we will need to use a library that supports converting markdown to HTML, and I chose the 
`league/commonmark` library to do the trick. 

I looked on packagist.org for a few Markdown-related projects to compare, and the League's package was by far the 
most popular and had the most recent updates. It is a very extensible option and tries to support GFM, "GitHub-flavored 
Markdown", which is the version of Markdown I'm most familiar with...since I use it all the time on GitHub. There 
are simpler packages out there to use, but I'm going with popularity and extensibility for now.

### Loading File Contents

I decided to write my blog posts in files with the format of `YYYY-MM-DD_slug.md` so they would stay ordered by date.
I might decide I want some other format later, but for now it seemed like a good idea to order the posts by dates. 

However, this means that we need to take more things into account when looking for the right file to load. I'm using 
the `KernelInterface` to locate the project's root directory and searching the `/posts` directory from that point.

```php
$projectDir = $kernel->getProjectDir();
$directory = $projectDir . '/posts';
$files = glob($directory . '/*.md');
```

Then, I need to loop through all the files found with that pattern and see if they match the slug. We will keep the 
date information just in case we need to use it, and because the destructing syntax is a nice, concise way to 
structure the code and make it more readable. I will want to use the date information at some point in the future.

```php
$matchingFile = null;
foreach ($files as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
    preg_match('/(\d{4}-\d{2}-\d{2})_(.*)/', $filename, $matches);
    if (!$matches) {continue;}
    [$full, $fileDate, $fileSlug] = $matches;
    if ($slug === $fileSlug) {
        $matchingFile = $file;
        break;
    }
}
```

Now that we have the file path, we can load the contents, parse the markdown, and pass it back to the Twig template 
for rendering. 

```php
$fileContent = file_get_contents($matchingFile);
$htmlContent = $commonMarkConverter->convert($markdownContent);
return $this->render('posts/show.html.twig', [
  'content' => $htmlContent,
]);
```

And if we now load the route "/posts/our-slug", it should return our markdown in HTML form. It will still look a bit 
ugly until more design choices are made, but it's a start. Except...

```
Cannot autowire argument $commonMarkConverter of "App\Controller\PostsController::show()": it references class 
"League\CommonMark\CommonMarkConverter" but no such service exists.
```

While services are autowired by default in the `/config/services.yaml` file, if we don't create a service first, the 
container can't find what we're looking for. In this case, `CommonMarkConverter` has some configuration options, so 
we can define some configuration at the same time we fix the autowiring error.

```yaml
services:
  # Other services...
  
  # Fix autowiring and add some options...
  League\CommonMark\CommonMarkConverter:
    arguments:
      config:
        html_input: 'strip'
        allow_unsafe_links: false
```

With that added service definition we should be good to go...but no, now we get another error:

```
Invalid service "League\CommonMark\CommonMarkConverter": did you forget to add the "$" prefix to argument "config"?
```

I gotta say that for an error message, this is a pretty good one. I'm not sure how long it would have taken me to 
figure out what's going on with just "Invalid service" as an error. 

In PHP, parameters passed to functions are positional and so Symfony needs the extra "$" symbol to denote that we 
are passing named arguments, not positional ones. We could rewrite the service definition to make the arguments into 
an array, but instead, we'll keep with the config key and update the definition to be:

```yaml
services:
  # Other services...
  
  # Fix autowiring and add some options...
  League\CommonMark\CommonMarkConverter:
    arguments:
      $config:
        html_input: 'strip'
        allow_unsafe_links: false
```

Now when we load the "/posts/building-moz" route, success! We get back markdown. Hooray!

You can see the full controller show action in the codebase here:


In the next post, I'll add a list of blog posts the homepage so we can click on a teaser and load the full post. 

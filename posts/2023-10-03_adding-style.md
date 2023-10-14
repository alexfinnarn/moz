---
title: Adding Style
author: 🦊 The Fox
date: 2023-10-03
tags: 
  - css
  - twig
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

In the last post, I started setting up my site to load markdown files so I could publish my blog posts. I went as 
far as getting the markdown parsed and HTML to render, but I didn't add anything else to the page. No header, no 
footer, and no way to get around the site.

In this post, I will add what I need around the main content area and add a bit of style to the site in the process. 
I was thinking about using Tailwind for styling, but as I mentioned in my last post, the complexity for me isn't 
really worth installing those dependencies. This site won't need a ton of styles or a complicated build process. 
Dear God, help us if I add a frontend build process to this site. :pray-hands

## Adding A Menu

Once I had the content from my blog post displaying correctly on my screen, I immediately wanted to improve the look 
and feel while also adding a menu in the header. After all, without any menu, how the heck you gonna navigate around 
the site?

I first have to create a `menu.html.twig` template, and then include that in the `base.html.twig` template so it 
shows up no matter what route is requested.

```html
   <!-- in base.html.twig -->
    <body>
        {% include 'partials/menu.html.twig' %}
        {% block body %}{% endblock %}
        {% include 'partials/footer.html.twig' %}
    </body>
```

I ended up choosing to `include` the templates vs. `embed` the templates since they are static and don't need 
anything overridden. However, if I had a reason to change the logo on the header menu, I could `embed` the menu 
template like so:

```html
   <!-- in base.html.twig -->
    <body>
        {% embed 'partials/menu.html.twig' %}
            {% block logo %}
                <a href="{{ base_url }}"><img src="{{ base_url }}/images/logo.png" alt="Logo"></a>
            {% endblock %}
        {% endembed %}
        {% block body %}{% endblock %}
        {% include 'partials/footer.html.twig' %}
    </body>
```

Certainly a contrived example, but it should be easy to see how other templates can benefit from exposing blocks 
that can be overridden inline...and I really just wanted to show off I knew more than one way to include a template ;)

```html
<!-- header.html.twig -->
<nav class="header container">
  <a href="/" class="logo">Logo</a>
  <ul class="site-menu">
    <li><a href="/">Home!!!</a></li>
    <li><a href="{{ path('app_posts', {limit: 10}) }}">Posties</a></li>
    <li><a href="/contact">Contact</a></li>
  </ul>
</nav>
```

My header is very simple, just a logo and a few links. I'm not trying to style up a storm here so I'll keep with the 
classic logo on left, site menu on left look. In order to add styles, I came to another fork in the road: component 
styling. I didn't want to use a CSS utility framework, which would have made styling individual components easy, so 
what should I do now?

## Component Styling

For now, I decided to throw caution to the wind and just include my styles in the templates. I want to keep my 
styles next to the HTML, and the simplest way I could think to do that was including the style tag above the 
template HTML.

```html
<style>
    header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 1rem;
        max-width: var(--max-width-screen-lg);
        width: 100%;
    }
</style>
<nav>
  <!-- The menu -->
</nav>
```

With this method it is easy for me to adjust the styles when I adjust the HTML and I don't have to look at two files 
while doing so. This principle is called "Locality of Behavior" and I think it makes maintaining codebases more 
pleasant than religiously trying to adhere to separation of concerns.

### CSS Variables and Scales

We'll get back to component styling and where I ended up on that, but I wanted to go over my approach to maintaining 
the CSS via use of CSS Custom Properties, a.k.a. CSS variables. 

The main reason I liked using Tailwind was for the variables and spacing scales. Tailwind provides the utilitiy 
classes in a way where you can adjust the sizing along with the type of CSS property. For example, I would commonly 
use `p-4` to end up with `padding: 1 rem;` which under the hood looked like `padding: var(--spacing-4)`. If I didn't 
like the way that looked, I would simply try `p-3` or maybe `p-5` without having to worry about what the actual 
values are.

If you've ever worked with a designer who gave you some kind of "living style guide" in some tool where the elements 
are absolutely positioned using pixels, then you've probably experienced the pain of the exact same component having 
slightly different pixel values where you wish they were all the same. 

Couple that with scaling. I'm no designer and I certainly don't pretend to understand spacing, scales, and how those 
play into visual aesthetics when designing websites. However, the scale in Tailwind is proportional, so a really 
cool thing you can do is create ratios of properties like `px-2 py-4` and they properly scale when you try that at 
twice the proportion `px-4 py-8`. So having a scale really, really helps me with designing a site and is 90% of what 
I liked about Tailwind.

```css
:root {
    /* Spacing scale excerpt based on Tailwind's default spacing scale */
    --space-4: 1rem;
    --space-5: 1.25rem;
    --space-6: 1.5rem;
    --space-7: 1.75rem;
    --space-8: 2rem;
    --space-9: 2.25rem;
    --space-10: 2.5rem;

    --max-width-screen-sm: 640px;
    --max-width-screen-md: 768px;
    --max-width-screen-lg: 1024px;
    --max-width-screen-xl: 1280px;

    --primary: #007bff;
    --secondary: #6c757d;
    --success: #28a745;
    --info: #17a2b8;
}
```

I will keep adding variables as I go along developing this site, but for now I mainly have a spacing scale, max 
widths, and colors in variables. I need to add font-size and line-height amongst other properties. 

## Component Styling Issues

Back to the component styling, we have a couple issues to solve that you may have thought of already:

1. The style tags will repeat if we have a component that gets displayed multiple times on a page. 
2. The style tags could collide with other styles somewhere else on the page.
3. The style tags don't "bubble up" which is helpful when trying to de-duplicate style tags.

Let's go over these concerns in order.

### Duplicate Style Tags

By including the style tag in the template, it will be repeated every time the template is rendered. While a few 
duplicate style tags isn't a big deal, it can become a performance as well as page weight issue as the template gets 
repeated.

Initially, I thought I should create some kind of custom Twig tag that could hold the style tag and only redner it 
once per component per request. I went through a few iterations but ended up with a tag that takes a parameter for 
an ID. While it might be nice to auto-magically include the template name as the ID, that takes more trouble than 
it's worth code-wise, IMAO.

```html
{% style 'component1' %}
<style>
  .component1 { width: 100%; }
</style>
{% endstyle %}
```

Then, when the request runs, only one copy of the component's styles are included in the response. It's not a great 
solution, but it does meet the needs of keeping things DRY.

### Style Tag Collisions 

The second issue of tag collision can be mitigated with discipline and scoping the CSS by component name. I could 
add some kind of auto-magical CSS scoping by pre-processing the style tags, but since I'm trying to keep it simple, 
always remembering to scope the CSS myself. Not much more to say other than discipline can save you a ton of 
maintenance burden from the demon complexity spirit.

### Global Style Tags

Our last issue relates to the position of the component in the DOM hierarchy. If we have a component in a list and 
we only include the style tag in the first rendering:

```html
<ul>
  <li>
    <style>.component1 { width: 100%; }</style>
    <a href="/foo">Foo</a>
  </li>
  <li>
    <!-- No styles... -->
    <a href="/bar">Bar</a>
  </li>
</ul>
```

Then the second component and onwards wouldn't get the proper styles applied to the anchor element. We need to have 
the styles "bubble up" to at least one level higher in this case, and while we could refactor the Twig tag to handle 
moving the CSS higher up on the chain, there's a simpler way. 

By adding an attribute to ID the component's style tag, we can ditch the Twig tag altogether and still get the 
results we want. With the help of an event listener, the styles tags are aggregated and moved into the head of the 
response.

```php
public function onKernelResponse(ResponseEvent $event)
{
    $response = $event->getResponse();
    $content = $response->getContent();
    $unique_styles = [];
    
    // Extract all <style> tags with a 'comp' attribute
    if (preg_match_all('/<style comp="([^"]+)">([\s\S]*?)<\/style>/', 
      $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            // Store unique styles keyed by $comp => $style_content
            $unique_styles[$match[1]] = $match[2];
        }
    }
    
    // Aggregate the unique styles into a single <style> block.
    $all_styles = '';
    foreach ($unique_styles as $comp => $style_content) {
        $all_styles .= "<style comp=\"$comp\">$style_content</style>\n";
    }
    
    // Remove all original <style> blocks with a 'comp' attribute
    $content = preg_replace('/<style comp="[^"]+">[\s\S]*?<\/style>/', '', $content);
    
    // Insert the aggregated styles at the end of the </head> element
    $content = preg_replace('/<\/head>/', "$all_styles</head>", $content);
    
    // Update the Response object
    $response->setContent($content);
}
```

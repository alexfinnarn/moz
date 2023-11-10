---
title: Partial Page Updates
author: 🦊 The Fox
date: 2023-10-23
tags: 
  - hosting
  - heroku
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

- wanted to load posts on the homepage
- started using htmx 
- decided to pull in the list of posts as a part of the homepage using htmx
- had a recursive call that kept loading the whole site within a div
- racked my brain, but htmx can solve this with `hx-select`
- then I had an issue with styling not applying to the new page content since the initial homepage request doesn't 
  have the posts list on it
- the components have a `<style>` tag above them but no wrapper
- adding a wrapper kind of defeats the KISS purpose and then the CSS would get weird fast
- I can solve this with
  - sending the list of CSS style tags in any htmx request `htmx:configRequest`
  - listening for that value on the server and merging in new style tags in the response
  - listening on the front-end for the returned style tags and then replacing the styles in the head
- Much simpler to use an "out-of-band" swap to automagically update the styles
  - this is easiest if the style tag is the first part of the `<body>` tag.
- Then had the problem of the styles tag being included in partial reuqests
  - I thought the `oob-swap` would replace the whole element instead of leaving it in the response
  - For a list of items replaced via `hx-get` I ended up with the styles div in each item
- Maybe I can remove the div with JS in the process of loading?
- Instead of trying that, I removed the event listener and wrapped the component in a container div
  - The styles tag still applies to the HTML since it still comes first
  - The container class still is applied and keeps isolation 

---
title: 'Re-imagining Contact Forms'
author: 🦊 The Fox
date: 2023-11-13
tags: 
  - hosting
  - heroku
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

While re-building my personal site for the ten billion-ith time, I decided I would start out with three sections for 
an initial MVP. Three is a pretty good number and it is used rhetorically in many famous works of art as well as 
modern advertising. Of course, you need a homepage to orient the user about what's on the site, and I know I want a blog 
section to host posts like these. However, a third section is not something I've done well in the past.

I could create a "Projects" section for my past client projects...except, I don't really have any past clients of 
note. Maybe I could post about my open source projects? But all of those are half-baked at the moment and not 
something I'd want to highlight. 

So the next section that came to mind is the tried and true "Contact Us" section. In past versions of my website, I 
posted my resume with an attached email address reducing the need to create a proper contact form. Plus, you need to 
have code take the form input and do something with it, and I wasn't trying to make any money back then so why bother?

In this post, I will bother with the Contact Form. I will bother so hard with it, that by the end you'll see a new 
way to craft a contact us experience while also covering spam bot needs. And I'm just getting warmed up too. I 
promise you this won't be the last time I bother the Contact Form, but let's get on with the story...

## Form and Function

I couldn't simply make a standard Contact Form. I just couldn't. You need spam protection anyways, so I need to add 
some dependency to create a working Contact Form that's worth a damn, right? Well, maybe not. 

I do agree that in 2023, you better have some kind of spam protection on your publicly accessible forms. There are 
many bots out there, and with the rise of AI tools and assistants, these bots are only going to get more pesky. 

You can add a CAPTCHA, which is an acronym I still don't understand after all these years, but I'm a bit annoyed by 
CAPTCHAs these days. They are getting difficult enough that in a short time maybe only machines will be able to pass 
the test. With the "click on squares with" type, I can never tell if I'm supposed to include the entire total object or 
err a bit and only click on the obvious squares. At any case, I'm sure most users of the internet hate CAPTCHAs by 
now.  

Another method is called a "honeypot". You add a hidden form field that no actual user will fill in, and the form 
submission is ignored on the backend. This is a pretty good and unobtrusive method, but it can be bypassed if the 
bot can detect CSS visibility rules. I might add a honeypot field to my Contact Form, but I would combine it with 
another method for maximum effectiveness.

You can also try adding a "human detector" by looking for key movements. Set a variable to false and then listen for 
when a key press, mouse, or touch event flips the variable to true. Only allow the Contact Form to be submitted if 
you've detected user interaction. However, once again, I'm not sure this method holds water anymore. I believe you 
can simulate user interactions with JS, and I bet bots have gotten past it.

I want to really think about the actual Contact Form and bother its essence so much, but for now, I'm just gonna 
deal with this stupid spam protection conundrum and call it a day.

## What If?

CAPTCHA, honeypot, and "human detector" are all methods I grifted off of projects I've worked on before, but what 
if there were a better way? What if we didn't accept the solutions I've been using for the past ten years and 
demanded a better spam protection experience? What if we thought outside the box a bit and tried to make the user's 
experience a little better while protecting our spam? What if we made the experience fun?

Fun? How could filling out a CAPTCHA be fun, you might ask? Well, fuck your CAPTCHA! I've got something better. 

### The Candidates



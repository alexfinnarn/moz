---
title: 'Turning Obsidian into Workflowy'
author: 🦊 The Fox
date: 2023-11-25
tags: 
  - 'task management'
  - obsidian
  - workflowy
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

It's the end of another year, and it's time for me to pay Workflowy again so I can use their task management application. Or is it? 
Maybe this time I can hack something together until I can build my own task management app.

[read more about my app](2023-11-25_task_management_app.md)

Workflowy is an outlining tool where you can make bullet lists, zoom in/out on each list, complete or delete the items, and a whole
bunch of other features I don't use. I even looked at the Workflowy website to see if I was missing something, but all the "cool"
features I saw were for collaborating with others, and that's something I don't need. I just want to make notes myself and make
sure I deal with those notes and complete my todo lists.

## Enter Obsidian

Obsidian is a...well, I'll let the more dogmatic users of Obsidian tell you what it is. To me, it seems like a pluggable markdown
editor. Just the same as Vim is the most configurable IDE, Obsidian seems to be the most configurable markdown editor. In that analogy, 
Workflowy is more like the IDE PHPStorm than Vim in that it comes fully loaded with plugins you can't really uninstall but need to do 
most development tasks. Worfklowy is a tool for people who like outlining, like me, and comes fully loaded without you needing to configure
anything. 

While Obsidian has many plugins installed by default that provide all kinds of functionality, you can also make your own or choose from a 
huge list of community plugins. In this post, I'll try using a few Obsidian plugins to turn Obsidian into the Workflowy I know and use
every day. 

## What is Outlining?

You may have never heard about a category of applications called "outliners", and to be honest, I didn't hear about this until I started
looking into Obsidian and Googling "turn Obsidian into Workflowy". It was then that I saw people classifying Workflowy as an outlining app.

- Outline
  - I am
  - creating an
    - outline
    - cake
  - for
    - mom
    - dad
    - brother
    - ...

The basic premise is that you nest notes underneath headings that help to organize your thoughts. Outlining is most common in author
circles, students, or really anyone who is working on a large project with many moving pieces. I tend to use the outlines both for task
management and keeping track of notes.

## My System

Over the years of using Workflowy, I've come up with a system of top-level "buckets" to put all my notes under. I'll go over each list
in the example below and explain the purpose of each list. Each list has a maximum amount of items that can exist at one time to limit 
scope creep and keep me honest about what I can accomplish.

- Ongoing
  - These are usually things that I will never be able to "complete" but I need to reference.
- Todo
  - These are the items I want to get done today
- Days
  - These are tasks that I spread out throughout my week.
- Triage
  - These are tasks not yet put into my weekly schedule
- Projects
  - I usually have three or so projects that I pull items out of and into Todo, Days, or Triage
- Check It Out
  - Things to check out periodically
- Buy It
  - Things I want to buy at some point
- Know It
  - Things to know, like wifi passwords or my shoe size for a particular brand
- Backlog
  - Projects that stalled but I might go back to at some point in the future.

I basically move items between those lists via drag-n-drop, check them off as completed, or delete them entirely. I 
don't use any of the other Workflowy features so I figured Obsidian might be able to meet my needs rather quickly.

## Installing Obsidian

You can download and install Obsidian from the main website, https://obsidian.md/, where you can also read about its core
features where you'll find out...that to sync your notes across apps it will cost...just as much as Workflowy...hmm.

So, the tale ends here. A short tale about how open source products can sound great until you think through hosting
and maintaining them. Plus, there's the getting used to a new tool and workflow.

## Future Work

I will go back to my task management app work now. I've renewed my Workflowy subscription for another year, and I've
already found a reason to continue building my own app.

Workflowy has a nice feature where you can add notes to any task, and it truncates most of the note allowing you to 
easily click to reveal the whole note. I just used the note feature to put the email confirmation text next to an 
event-related task, but I noticed the note lost all it's formatting. 

Well, in my app that won't happen. But until then, I'll gladly use Workflowy for my "glorified bullet list" needs.
you use Obsidian, drop me a line and let me know how that's going for you.




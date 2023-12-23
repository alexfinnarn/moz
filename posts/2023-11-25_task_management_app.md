---
title: 'My Very Own Task Management Application'
author: 🦊 The Fox
date: 2023-11-25
tags: 
  - rails
  - 'task management'
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

I've been using a task management app called Workflowy for years. We tend to call these apps "todo apps", and Todo 
MVC [link] is a very famous project to showcase how a todo app could be created across programming languages and 
frameworks.

The general idea is to write down tasks and then cross them off your list once done. We tend to mimic real-life todo 
lists created on little sheets of paper. Growing up, my Dad had a lot of post-it notes around the house reminding 
him about tasks he needed to complete. Today I still need post-it help, but fortunately for me, I can keep my notes 
in a more private place on the internet and recall them with a flick of the wrist on my mobile phone. 

In this post, I'll go over my plan to replace my current todo application with a new one I call "Flowy" since I am 
ripping off my current best todo app: Workflowy.

## Todo, To-don't

I've tried out various todo apps over the course of my professional life. I forget the first one I tried, but names 
like Asana and Todoist come to mind for the types of apps I'm generically referring to. 

You aren't really signing up to list out your todos and check them off. Rather you are signing up to their 
philosophy of task management. It is reasonable to make that leap as an application developer. Otherwise, what are 
you really creating that isn't already in basic, open source solutions? 

You kind of have to impart a philosophy as you develop features, and based on that philosophy your users will follow 
suit or ditch your app for a different flavor. I bet many users of these apps don't have a workstyle of their own 
and so adopting someone else's is the best thing they can do to stay organized. 

I, however, struggled with all the bells and whistles that a lot of these apps feel necessary to put in. For example,
I could never get over how hard it was to move a todo from one list to the other. I commonly will put a todo item in 
some list and then not do it. Rather than beat myself up for not completing the task, I just move it to the next day.

But boy oh boy is it pretty complicated to move tasks around most todo apps these days. You end up getting into bulk 
update operations, when I just want to be able to drag things around and call it a day.

[screenshot of moving tasks between lists]

## Enter Workflowy

I struggled with these apps until I found Workflowy. I always describe Workflowy as a glorified bullet list creator. 
You essentially create a huge tree of unordered lists with items that you can move across lists via drag n drop. You 
can also zoom in and out of lists to only show the items you need to see for any given task. 

I think it is common for Workflowy users to create their own list sections, iterate on their initial idea, and end 
up with a pretty streamlined way of adding todo items, notes, bookmarks, and really any text that you need to 
remember and recall at a later date.

Workflowy has tagging, search, Kanban boards, and a whole host of other features that I don't use. I've tried 
tagging items, but once I'm done with a task, I rarely need to find it again. My lists acts as "tags" by including 
every item that would hold that tag in the list, so I'm not sure why I would need to put un-related items in 
different lists...but other people love the features and use a lot of them.

## Paired Down Workflowy

Since I never use a lot of the Workflowy features, I wondered what it would be like to try and create my own version.
In full disclosure, my Workflowy membership is set to renew in a few months, and I also wondered if I could create 
an app, so I don't have to keep paying them money. 

I only really needed a few features:

- Unordered lists in a tree structure 
- A way to mark items completed
- A way to delete items from the list
- Ability to re-order lists and drag items between them

And with those four simple features, I could manage all my projects and tasks just like I have for the last several 
years while using Workflowy.


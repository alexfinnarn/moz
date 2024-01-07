---
title: 'Dependabot Dependency Updates'
author: 🦊 The Fox
date: 2023-11-25
tags: 
  - 'dependency management'
  - github
  - security
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

There comes a time in every project where you wonder if your dependencies are up-to-date. If you're working on a 
professional project, then it is often the case you are legally obligated to make sure the dependencies are 
up-to-date and secure. If you're working on a personal project, however, keeping things current can be a barrier
to accomplishing anything, and it is quite common to let things slide.

But you don't have to fuss with updates when Dependabot has your back. Dependabot is an automated dependency checker
that can make pull requests against your codebase at a certain frequency with plenty of rules for further
customization. 

It works on GitHub, where I host my code, so I am only going to discuss automated dependency updates on GitHub, but
I'm sure GitLab and other code hosting platforms have similar solutions.

## Initial Configuration

There isn't much you need to do to add Dependabot updates to a project. For my project I am using PHP but also 
sometimes have some JS dependencies thrown in. At a bare minimum, you only need to define three things for
Dependabot to start doing its job. 

- package-ecosystem - 
- directory - 
- schedule.interval - 

I generally leave these 

```yaml
version: 2
updates:
  - package-ecosystem: "composer"
    directory: "/"
    schedule:
      interval: "weekly"
    open-pull-requests-limit: 10
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "daily"
    open-pull-requests-limit: 10
```

## Security Updates


## Automating the Updates

## Commit Cred Inflation Bonus

Once you get good at clicking the merge button, or have automated the checks and merging in of Dependabot updates,
you'll start to notice that 

Does the automated way still preserve the commits since you aren't merging them in?


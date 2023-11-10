---
title: Cypress Testing Tips
author: 🦊 The Fox
date: 2023-10-28
tags: 
  - hosting
  - heroku
summary: Embark on a web development journey with "Moz," a consulting site built using Symfony. Find out why the
  author picked Symfony over Django and Laravel, and navigate the pros and cons of using Tailwind CSS. Get a
  behind-the-scenes look at handling Markdown content and troubleshooting common Symfony issues. This blog post
  offers valuable insights for both novice and seasoned developers.
---

## Test Creation Conventions

### Naming and Organization

- hard to know where to put tests
- several spec files that relate to each other
- makes the test run slower and harder to maintain when you duplicate setup steps
- add example of where you can insert your assertions into an existing test vs creating a new one

### Which User To Test As

- try to test as the lowest level user possible
- the administrator has all permissions so use that user as a last resort

## Common Pitfalls

### Not writing a unit or lower level test

- you should always try not to write an end-to-end test
- instead use PHPUnit and its test base classes
- this will greatly speed up the overall CI pipeline run
- also will make tests less flaky
- also keeps the tests by the code for better organization
- needs work to get to this point though

### Short Tests

- lead to 2.5x longer tests
- can easily see where tests break
- find the Cypress doc on this and link to it

### Premature Optimization

- don't add before, beforeEach if you only have one test
- duplicate things at least three times before refactoring
- link to the principle of locality of behavior and how abstracting things makes tests harder to read
- when refactoring, if the tests are too abstracted then you have to spend extra time
- test code is not application code
- link to DAMP vs DRY with the context of test writing

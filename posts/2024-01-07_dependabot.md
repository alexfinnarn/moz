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

- package-ecosystem - Which package manager are you using? For me, it is `composer`
- directory - Where is your package information? For me and most people it is at the root.
- schedule.interval - How often should Dependabot look for updates? This can differ depending on your needs.

I generally only use these three configuration options, but I will get into some more detailed configuration
later in this post.

After [reading the docs](https://docs.github.com/en/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file) 
for a little while, I generated this file and put it at `.github/dependabot.yml` in my codebase.

```yaml
version: 2
updates:
  - package-ecosystem: "composer"
    directory: "/"
    schedule:
      interval: "daily"
    open-pull-requests-limit: 10
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "daily"
    open-pull-requests-limit: 10
```

Almost immediately after I uploaded my new commit, I saw many dependency updates. This is why the 
`open-pull-request-limit` option can be handy if you don't want to deal with too many dependency updates at once.

You can also change the frequency to `weekly`, but remember that will likely make you have to review more updates
each time Dependabot runs its check. You'll likely have to play around with the settings to get your desired cadence, 
but at least this is better than manually checking yourself.

## Release Notes

While I could try `composer outdated` to show me what I likely should update, one of the advantages of using Dependabot
is that it shows you the release notes and commits right there on GitHub.

![dependabot release notes](/images/posts/dependabot-release-notes.png)

It would take me some time to find the release notes if I wanted to scan a list of outdated dependencies, and I would have
to look up each one to check. Granted, I don't really read the release notes anyway, but it's nice to have there if you
want to take a look.

## Security Updates

Security updates are a bit different than the standard Dependabot pull requests. If you go to the `/security` section
of your GitHub project, you'll see a bunch of security features you can turn on.

![GitHub security page](/images/posts/github-security.png)

Let's go through the listed options:

- **Security policy** - If you are collaborating with other people, then you might want to set up a policy, but for
a personal project, I am going to skip this section. 
- **Security advisories** - I had this enabled and turned it on at some point. 
- **Private vulnerability reporting** - It sets up a way for other people to securely report issues, but for me, this would 
be overkill.
- **Dependabot alerts** - I enable this so I can see if any dependency has a vulnerability. 
- **Code scanning alerts** - These can't hurt so I always have it turned on, but I will explain more about this in another post
[link to post](#)
- **Secret scanning alerts** - This will catch when you expose credentials in pull requests so I usually keep it on. Sometimes
it will mark local development environmental variables, like `DATABASE_URL`, as exposed, but I think once you dismiss the alert
it won't check that value any more.

Wouldn't all security updates be included in the regular PR version updates Dependabot already makes, you might ask?

Dependabot security alerts go far beyond what's listed in the require sections of a `composer.json` file. The whole lock file
is scanned for security issues, and then Dependabot can tell you if anything in the lock file has a vulnerability. 

Relying on the regular version updates will probably catch the vulnerability eventually, but with security updates, you 
can catch the alert potentially even before the maintainer of the package you rely on sees it. This practice hopefully speeds 
up the turnaround time for vulnerabilities get reported and when OSS maintainers do something about them.

## Automating the Updates

I've been using Dependabot for years, and it can get quite old logging into GitHub and seeing tons of update PRs in the 
queue. If you have tests for your project, you wonder: how can I automate this? 

Well, you can create a GitHub workflow to merge the code for you.

```yaml
name: Dependabot auto-merge
on: pull_request

permissions:
  contents: write
  pull-requests: write

jobs:
  dependabot:
    runs-on: ubuntu-latest
    if: ${{ github.actor == 'dependabot[bot]' }}
    steps:
      - name: Dependabot metadata
        id: metadata
        uses: dependabot/fetch-metadata@v1
        with:
          github-token: '${{ secrets.GITHUB_TOKEN }}'
      - name: Enable auto-merge for Dependabot PRs
        run: gh pr merge --auto --squash "$PR_URL"
        env:
          PR_URL: ${{github.event.pull_request.html_url}}
          GITHUB_TOKEN: ${{secrets.GITHUB_TOKEN}}
```

That configuration should allow Dependabot to merge in the PR even though it is an outside collaborator. 

The key is to make sure you have branch protection enabled and "Require status checks to pass before merging" checked
with at least one status check.

![Branch protection configuration](images/posts/branch-protection.png)

## Commit Cred Inflation Bonus

Once you get good at clicking the merge button, or have automated the checks and merging in of Dependabot updates,
you'll start to notice that 

Does the automated way still preserve the commits since you aren't merging them in?

## Caveats

There are some caveats to be aware of when using Dependabot. 

I don't think it will show you updates to major versions if your package management file has version constraints tied
to a minor version, which most projects will have. Sometimes, you might only want to update the patch version and 
leave minor version updates out all together. It just depends on how you add dependencies to your projects.


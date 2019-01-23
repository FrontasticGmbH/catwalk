# Frontastic Coding Guide

This guide defines common standards for Frontastic development. The guidelines
are mandatory for development inside of Frontastic and are highly recommended
for project development.

## Git Workflow

Frontastic follows a "master based development" flow (originally known as
"trunk based development"). That means, branches are generally discouraged. All
code should go directly into master. This requires each push (ideally each
commit) to leave the code base fully functional.

See the end of this chapter for some hints that can help you working this way.

### Commit Guidelines

1. Pull before you push.

2. Rebase unpushed changes in favor of merge (set `pull.rebase` globally to
   `true`).

3. Structure your work in logical steps and commit parts of your work together
   which deal for a common purpose.

4. Frequent, smaller commits are preferred over large batches of work.

5. Push frequently, but always ensure a working state in `master`.

### Commit Message Guidelines

1. Every commit messages consist at least of a subject line explaining the
   change in a few meaningful words.

2. Limit the subject line to 50 characters (soft limit) respectively 80
   characters (hard limit)

3. Capitalize the subject line

4. Use past tense in the subject (`Fixed schema to complete defaults` instead
   of `Fixes schema to complete defaults`)

4. If you are working on a ticket, prefix the subject by the ticket number
   using a `#` (e.g. `#4223 Implemented model for product types`)

5. Add a body to your commit to explain the reasons for your change if you feel
   its necessary (e.g. removing a feature, changing a behavior for certain
   reasons, etc.)

6. Divide the subject from the body using a blank line.

7. Use of Markdown style elements in the body is permitted (e.g. lists)

### Master Based Development Guidelines

- Run (all/component) tests before pushing (`$ ant test`)
- Use an iterative development approach (start with the smallest functional
  feature possible and extend it subsequently)
- Create tests for all new code to ensure it is basically working (no need for
  full code-coverage or similar)
- Implement code without integrating it directly into the app before it is
  functional (use tests!)
- Deactivate the effect of your code using a feature-flag if it could disturb
  others while being under development
- If you are unsure about changing existing code and it does not have (enough)
  tests: create tests first
- Always test the affected front-end parts your change affects in your
  development VM/container before pushing

If you are unsure if a specific part of your code is the right way of doing it,
feel free to create a minimal branch for that specific piece of code and post a
link to `#devops` on Slack for discussion.

## Programming Language Crossing Coding Style

Frontastic encourages Clean Code as described in https://www.amazon.de/dp/0132350882.

Most importantly, the following rules should by applied to any kind of code:

* Stick to the patterns you find in existing code
* If you find code places that can be optimized for cleanness, go on and optimize them (boyscout rule)
* Use meaningful names for all code entities (classes, methods, fields, variables, files, ...)
* Avoid and encapsulate side-effects
* Use exceptions for errors
* Avoid comments that repeat code
* Add comments where you do something unusual
* Keep comments short and to the point
* Frequently run the code analysis tools available (`$ ant test`)

## PHP Coding Style

* Stick to PSR-1, PSR-2 and PSR-4
* Implement tests using PHPUnit
* See the guidelines in README.md

## JavaScript Coding Style

%% TODO %%

## (S)CSS Coding Style

%% TODO %%

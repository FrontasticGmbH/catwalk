# Beta Notes

Welcome, esteemed tester of Frontastic Developer Edition! **Please read
and follow these notes carefully!**

## Beta Quality

This is beta grade software. Beta means that the sofware is not considered
stable for productive usage and that APIs might change due to critical issues.
In this release, the following APIs of and mechanism can be considered *beta*
quality:

- Implementing and registering Tastics as described in [Implementing a Simple
  Tastic](tutorials/10_simple_tastic.md) and our [Reactive
  Docs](http://frontastic.io.local/?type=playground)
- and acessing product data from the stream abstraction of the streams `product`
  and `product-list`.

Other APIs and mechanism included in Frontastic Catwalk might still be subject
to change (*alpha quality*). This especially includes:

- Any API on the PHP level (aka Symfony services)
- The PHP level extension mechanisms

## Feedback

As a beta tester we would love if you give us feedback on your experience. Just
throw anything that comes to your mind at us using one of these channels:

- [#dev-support](https://frontastic.slack.com/messages/CBY0NS0UW) Slack Channel,
- e-mail [toby@frontastic.io](mailto:toby@frontastic.io) or
- notify [@tobyS](https://github.com/tobyS) directly on Github.

Thanks in advance for any feedback.

## Tastic Previewing

We are currently working on providing a show-case environment for developer
accounts. Since this mechanism is not ready, yet, you will not be able to
preview changes outside of your VM.

For an easy start we recommend our [Reactive
Docs](http://frontastic.io.local/?type=playground) which provide you with
everything you need to get directly started with coding on Tastics.

To see your own Tastics in action, please follow the [Testing a
Tastic](tutorials/20_testing_tastic.md) tutorial and try your Tastics locally.

We will keep you posted when the Staging environemnt is in place.

## Code Review

Note that we are hosting your code base within our Github organization in order
to *see how you are using* Frontastic Catwalk. This will help us to improve our
development experience and allow us to support you directly when questions occur.

Please be so kind and **push your code** to the Github repository we are
providing to you. This also helps you to easily get support by our code
developers on the Slack channel.

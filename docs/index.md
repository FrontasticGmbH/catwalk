# Frontastic Catwalk Development

!!! important "Frontastic Developer Edition Beta"
    **This is a beta preview.** Please follow the [Beta Notes](beta_notes.md)!

Welcome, valued Catwalk-Developer! This documentation is meant to give you an
overview on how to develop in Frontastic Catwalk and shall guide you to an
excelent developer experience.

## Getting Started

!!! info "Frontastic Reactive Docs"
    **If you are new to Frontastic Catwalk you most propbably want to take our
    [Reactice Docs](http://frontastic.io.local/?type=playground) tutorial
    first.**

After that we recommend that you:

- become familiar with the [Frontastic Architecture](architecture.md),
- get an overview on the [Technical Concepts](concepts.md),
- and potentially snuffle into our [Tutorials](tutorials/index.md).

You can find an overview of your VM and much more useful info at
[http://frontastic.io.local](http://frontastic.io.local).

## Helpful Commands

When working in Frontastic Catwalk, the following shell commands are quite
helpful. Please make sure to execute them in your VM:

- `$ ant -p` tells you which build commands are available, like executing
  tests, checking the code style, etc.
- In any project directory or in `paas/catwalk/`: `$ bin/console` is the Symfony
  Console that supports many Frontastic related commands and can perform
  general tasks like purging the cache.
- Using `$ sudo supervisorctl status` displays the status of all supervisor
  jobs, e.g. webpack. `supervisorctl` can also start/stop/restart these jobs.
- If you want to peak at the Catwalk databases use `$ mysql -u root -proot` in
  the VM

# Upgrade Guide

Find instructions for special upgrade below.

# Developer Preview Beta 2018-08-01 to 2018-08-15

We changed the PHP namespace structure of Catwalk. This heavily affects caching
and serialized PHP data. Therefore you need to reset your catwalk data entirely
after the upgrade using the following commands in the Frontastic root dir in
your VM:

```bash
$ sudo supervisorctl stop all
$ ant reset
$ rm -r /var/cache/frontastic/*/*
```

Please reboot your VM afterwards.

No worries, all data from Backstage will be replayed into your VM automatically
by the replicator.

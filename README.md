This is a tutorial source code related to [this blog post][post].

# Usage

First you need to start Vagrant Virtual Machine:

```bash
$ cd vagrant
$ vagrant up
```

# Install

Then, you can log in to the Virtual Machine and run the following commands:

```bash
$ vagrant ssh

$ cd /vagrant
$ make install
```

By default, port-forwarding is activated on 8000, so you should access to:
<http://localhost:8000/>

[post]: http://jeremybarthe.com/2015/01/14/exemple-implementation-api-symfony2-et-client-angularjs/

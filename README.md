Plv, a Programming language version check tools
===============================================

Requirements
============

Plv requires PHP 5.3.3(or later)

Installation
============

Install composer in your directory:

```
curl -s http://getcomposer.org/installer | php
```

[Download][1] latest version

or

Create project via composer:

```
php composer.phar create-project plv/plv /path/to/install version
```

Usage
=====

Check programming language version

```
/path/to/install/bin/plv check [nodejs|perl|php|python|ruby]
```

List of programming language that supports

```
/path/to/install/bin/plv language
```

[1]: https://github.com/isam/plv/releases



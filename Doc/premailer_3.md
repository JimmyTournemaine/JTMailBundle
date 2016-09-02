# PreMailer

## Download

The PreMailer service use [SensioBuzzBundle](https://github.com/sensiolabs/SensioBuzzBundle) to send requests to the [PreMailer API](http://premailer.dialect.ca/api).

```bash
composer require "sensio/buzz-bundle" : "~1.1"
```

Then, follow the [installation guide](https://github.com/sensiolabs/SensioBuzzBundle).


## Activate PreMailer

```yaml
# app/config/config.yml
jt_mail:
    pre_mailer: ~
```

From now, when you send and email from the JTMailer service. Your HTML message will be edit by PreMailer before being send.
It also generate a text alternative if you have not provide a text content to the JTMailer service.

## Complete configuration

You can configure many of premailer behaviour thanks to the bundle configuration.
See all feature by executing :

```bash
php bin/console config:dump-reference JTMailBundle
```


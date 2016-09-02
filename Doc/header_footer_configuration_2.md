# Header and Footer configurations

If you do not need to dynamics parameters in your header or footer of your emails, you can edit your configuration :

```yaml
# app/config/config.yml
jt_mail:
    header:
        template: 'email/header.html.twig'
    footer:
        template: 'email/footer.html.twig'
        parameters:
            - { key: 'contact_addr', value: "%delivery_address%" }
            - { key: 'locale', value: "%locale%" }
```

## Next Step

- [Use PreMailer](premailer_3.md)
#Installation

JTMailBundle allow you to send mail easily. You can use [our pre-mailer to build your message style](https://github.com/JimmyTournemaine/PreMailer).

##Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

	$ composer require jimmytournemaine/jt-mail-bundle "~1.0"

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

##Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:


	<?php
	// app/AppKernel.php
	
	// ...
	class AppKernel extends Kernel
	{
	    public function registerBundles()
	    {
	        $bundles = array(
	            // ...
	            new JT\MailBundle\JTMailBundle(),
	        );
	
	        // ...
	    }
	
	    // ...
	}
	```

##Step 3: Use the mailer

- [Read the documentation](Doc/how_to_use_1.md)

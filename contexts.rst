Contexts
========

There are multiple contexts (extensions) to the language, which provides context specific variables and functions. Most of them are SMTP extensions and executed with a SMTP session or SMTP message, providing you with information about the session and message in order for you to either accept or reject the message::


	  .--------------------------------. <-- IP context
	  | ...                            |
	> | MAIL FROM: <john@example.org>  |
	< | 250 OK                         |
	> | RCPT TO: <jane@example.com>    | <-- RCPT TO context
	< | 250 OK                         |
	> | DATA                           |
	< | 354 Feed me                    |
	> | Subject: Lunch                 |
	> |                                |
	> | Lunch on friday?               |
	> | .                              | <-- DATA context
	< | 250 Accepted                   |
	  `--------------------------------´
	               |
	   ,-------> queue
	   |           |                     <-- Pre-delivery context
	   |   delivery attempt
	   |           |                     <-- Post-delivery context
	   \__________/ \_____ done

.. toctree::

	ip
	auth
	rcptto
	data
	predelivery
	postdelivery
	api

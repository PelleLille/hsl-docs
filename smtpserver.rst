SMTP server
===========

There are multiple extensions to the language, which provides context specific variables and functions. The `smtpd` process implements the :doc:`connect <connect>`, :doc:`proxy <proxy>`, :doc:`HELO <helo>`, :doc:`AUTH <auth>`, :doc:`MAIL FROM <mailfrom>`, :doc:`RCPT TO <rcptto>` and :doc:`end-of-DATA <eod>` context. These scripts operates on an SMTP connection. The ``$transaction["id"]`` variable is set when connecting and may be regenerated upon the client sending a RSET command. There is also a ``$context`` variable which is bound to a connection and may be used in any script, which is useful for passing data between phases.

::

	  .--------------------------------. <-- connect context
	< | 220 example.org ESMTP          |
	> | HELO example.com               | <-- HELO context
	< | 250 OK                         |
	  | ...                            |
	> | AUTH LOGIN                     | <-- AUTH context
	  | ...                            |
	< | 250 OK                         |
	> | MAIL FROM: <john@example.org>  | <-- MAIL FROM context
	< | 250 OK                         |
	> | RCPT TO: <jane@example.com>    | <-- RCPT TO context
	< | 250 OK                         |
	> | DATA                           |
	< | 354 Feed me                    |
	> | Subject: Lunch                 |
	> |                                |
	> | Lunch on friday?               |
	> | .                              | <-- end-of-DATA context
	< | 250 Accepted                   |
	  `--------------------------------´
	               |
	   ,-------> queue
	   |           |                     <-- Pre-delivery context
	   |   delivery attempt
	   |           |                     <-- Post-delivery context
	   \__________/ \______ done

.. toctree::

	connect
	proxy
	helo
	auth
	mailfrom
	rcptto
	eod
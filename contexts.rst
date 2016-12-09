Contexts
========

There are multiple contexts (extensions) to the language, which provides context specific variables and functions. The `smtpd` process implements the :doc:`CONNECT <connect>`, :doc:`AUTH <auth>`, :doc:`MAIL FROM <mailfrom>`, :doc:`RCPT TO <rcptto>` and :doc:`DATA <data>` context. These contexts operates on an SMTP connection. The ``$messageid`` variable is set when connecting and may be regenerated upon the client sending a RSET command. There is also a ``$context`` variable which is bound to a connection and may be changed in any flow, this is useful for passing data between flows.

::

	  .--------------------------------. <-- CONNECT context
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
	> | .                              | <-- DATA context
	< | 250 Accepted                   |
	  `--------------------------------´
	               |
	   ,-------> queue
	   |           |                     <-- Pre-delivery context
	   |   delivery attempt
	   |           |                     <-- Post-delivery context
	   \__________/ \______ done

The `mailqueued` process implements the :doc:`Pre- <predelivery>` and :doc:`Post-delivery <postdelivery>` contexts. These contexts operates on a message in queue. A message in queue is not directly bound to an inbound SMTP connection, hence its delivery is not done inline.

.. toctree::

	connect
	auth
	mailfrom
	rcptto
	data
	predelivery
	postdelivery
	api
	firewall

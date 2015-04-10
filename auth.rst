AUTH
====

The AUTH flow allows trusted SMTP clients.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each `AUTH` command.

============= ======= =============== ===========
Variable      Type    Example         Description
============= ======= =============== ===========
$senderip     string  "192.168.1.11"  IP address of the connected client
$tlsstarted   boolean false           Whether or not the SMTP session is using TLS
$saslusername string  "mailuser"      SASL username
$saslpassword string  "secret"        SASL password
$serverid     string  "mailserver\:1" ID of the mailserver profile 
============= ======= =============== ===========

Functions
---------

.. function:: Accept()

  Authorize the login request.

  :return: doesn't return, script is terminated

.. function:: Reject([message])

  Reject the login request.

  :param string message: the reject message
  :return: doesn't return, script is terminated

On script error
---------------

On script error ``Reject()`` is called.

.. module:: auth

AUTH
====

The AUTH context allows trusted SMTP clients.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each `AUTH` command.

============= ======= =============== ===========
Variable      Type    Example         Description
============= ======= =============== ===========
$senderip     string  "192.168.1.11"  IP address of the connected client
$serverip     string  "10.0.0.1"      IP address of the mailserver
$serverid     string  "mailserver\:1" ID of the mailserver profile
$tlsstarted   boolean false           Whether or not the SMTP session is using TLS
$saslusername string  "mailuser"      SASL username
$saslpassword string  "secret"        SASL password
============= ======= =============== ===========

Functions
---------

.. function:: Accept()

  Authorize the login request.

  :return: doesn't return, script is terminated

.. function:: Reject([reason])

  Reject the login request.

  :param string reason: the reject message
  :return: doesn't return, script is terminated

.. function:: Defer([reason])

  Defer the login request with a temporary (454) error.

  :param string reason: the defer message
  :return: doesn't return, script is terminated

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Reject()`` is called.

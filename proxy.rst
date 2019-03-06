.. module:: proxy

Proxy
=====

This advanced and powerful script is can be executed before an SMTP command is sent to the internal state machine of the SMTP server (hence before the command is procesed by the SMTP server). It can be used to built custom SMTP commands or modify the current command line before processing.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each command. Depending on when the proxy script is executed, the variables may contain different information.

Connection
^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the server
$serverport       number  25                         TCP port of the server
$serverid         string  "mailserver\:1"            ID of the server
$senderhelo       string  "mail.example.com"         HELO hostname of sender
$tlsstarted       boolean false                      Whether or not the SMTP session is using TLS
$saslauthed       boolean true                       Whether or not the SMTP session is authenticated (SASL)
$saslusername     string  "mailuser"                 SASL username
================= ======= ========================== ===========

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     Connection-bound variable
================= ======= ===========

Transaction
^^^^^^^^^^^

.. include:: var_transaction.rst

Arguments
^^^^^^^^^

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$command            string  "XCLIENT ADDR=1.1.1.1"     The SMTP command line issued
=================== ======= ========================== ===========

Functions
---------

.. function:: Pass([options])

  Pass the ``$command`` to the SMTP server's state machine.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **command** (string) The SMTP command. The default is ``$command``.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

.. function:: Reply([reason, [options]])

  Send a reply to the client (The default is code 250). The ``$command`` is not passed to the SMTP server's state machine.

  :param reason: the message to reply
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

On script error
---------------

On script error :func:`Reply` is called with a generic 421 response.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Pass` is called.
